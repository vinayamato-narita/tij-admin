<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Models\Student;
use App\Models\StudentPublicCommentForTeacher;
use App\Http\Requests\StudentCommentRequest;
use Log;

class StudentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function comment(Request $request, $id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_comment_list', $id],
        ]);
        $pageLimit = $this->newListLimit($request);

        $studentInfo = Student::where('id', $id)->firstOrFail();

        $queryBuilder = StudentPublicCommentForTeacher::select('student_public_comment_for_teachers.id as id', 'student_public_comment_for_teachers.created_at as created_at', 'student_public_comment_for_teachers.updated_at as updated_at', 'comment', 'teachers.teacher_nickname as teacher_nickname')
        ->leftJoin('teachers', function($join) {
                $join->on('student_public_comment_for_teachers.teacher_id', '=', 'teachers.id');
        })->where('student_public_comment_for_teachers.student_id', $id);

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('comment', $request['search_input']))
                    ->orWhere('teacher_nickname', $request['search_input']);
            });
        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "teacher_nickname") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('teachers.teacher_nickname','ASC') : $queryBuilder->orderBy('teachers.teacher_nickname','DESC');
            }
        }
        $commentList = $queryBuilder->sortable(['updated_at' => 'desc'])->paginate($pageLimit);

        return view('student.comment', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'commentList' => $commentList,
            'studentInfo' => $studentInfo,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createComment($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_comment_list', $id],
            ['name' => 'student_create_comment', $id],
        ]);

        $studentInfo = Student::select('id', 'student_name')->where('id', $id)->firstOrFail();
        $studentInfo->_token = csrf_token();
        $studentInfo->comment = "";

        return view('student.create-comment', [
            'breadcrumbs' => $breadcrumbs,
            'studentInfo' => $studentInfo,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComment(StudentCommentRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);           
        }
        $studentInfo = Student::select('id', 'student_name')->where('id', $request->id)->firstOrFail();
        if ($studentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        $comment = new StudentPublicCommentForTeacher;
        $comment->student_id = $request->id;
        $comment->comment = $request->comment;
        $comment->save();     

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editComment($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_comment_list', $id],
            ['name' => 'student_create_comment', $id],
        ]);
        $commentInfo = StudentPublicCommentForTeacher::select('student_public_comment_for_teachers.id as id', 
            'student_public_comment_for_teachers.student_id as student_id', 'students.student_name as student_name',
            'teachers.teacher_nickname as teacher_nickname', 'student_public_comment_for_teachers.created_at as created_at',
            'student_public_comment_for_teachers.updated_at as updated_at', 'student_public_comment_for_teachers.comment as comment')
        ->leftJoin('teachers', function($join) {
            $join->on('student_public_comment_for_teachers.teacher_id', '=', 'teachers.id');
        })
        ->leftJoin('students', function($join) {
            $join->on('student_public_comment_for_teachers.student_id', '=', 'students.id');
        })
        ->where('student_public_comment_for_teachers.id', $id)->firstOrFail();

        $commentInfo->_token = csrf_token();
        
        return view('student.edit-comment', [
            'breadcrumbs' => $breadcrumbs,
            'commentInfo' => $commentInfo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateComment(StudentCommentRequest $request)
    {
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);          
        }
        $commentInfo = StudentPublicCommentForTeacher::where('id', $request->id)->first();
        if ($commentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $commentInfo->comment = $request->comment;

        $commentInfo->save();  

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyComment($id)
    {
        try {
            StudentPublicCommentForTeacher::where('id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => 'コメント削除が完了しました。',
        ], StatusCode::OK);
    }
}
