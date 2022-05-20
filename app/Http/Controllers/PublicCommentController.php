<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\StudentPublicCommentForTeacher;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use Carbon\Carbon;
use DB;
use Log;

class PublicCommentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'public_comment_list']
        ]);
        $pageLimit = $this->newListLimit($request);

        $queryBuilder = StudentPublicCommentForTeacher::select(
            'student_public_comment_for_teacher_id',
            'student.student_nickname',
            'student_public_comment_for_teacher.student_id',
            'teacher.teacher_nickname',
            'student_public_comment_for_teacher.create_date',
            'student_public_comment_for_teacher.update_date',
            'comment'
        )
        ->leftJoin('student', 'student.student_id', '=', 'student_public_comment_for_teacher.student_id')
        ->leftJoin('teacher', 'teacher.teacher_id', '=', 'student_public_comment_for_teacher.teacher_id');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student.student_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_id', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher.teacher_nickname', $request['search_input']));
            });
        }
        if (isset($request['sort'])) {
            if($request['sort'] == "student_nickname") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student.student_nickname','ASC') : $queryBuilder->orderBy('student.student_nickname','DESC');
            }
            if($request['sort'] == "teacher_nickname") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('teacher.teacher_nickname','ASC') : $queryBuilder->orderBy('teacher.teacher_nickname','DESC');
            }
        }
        
        $comments = $queryBuilder->sortable(['update_date' => 'desc'])->paginate($pageLimit);

        return view('publicComment.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'comments' => $comments,
        ]);
    }
}
