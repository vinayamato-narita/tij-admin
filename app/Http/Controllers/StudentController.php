<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Models\Student;
use App\Models\StudentPublicCommentForTeacher;
use App\Models\LessonHistory;
use App\Models\LessonCancelHistory;
use App\Models\LessonHistoryOld;
use App\Models\StudentPointHistory;
use App\Models\LessonSchedule;
use App\Http\Requests\StudentCommentRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
                    ->orWhere($this->escapeLikeSentence('teacher_nickname', $request['search_input']));
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


    public function lessonHistory(Request $request, $id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_lesson_history_list', $id],
        ]);
        $pageLimit = $this->newListLimit($request);

        $studentInfo = Student::where('id', $id)->firstOrFail();

        $queryBuilder = LessonHistory::select('lesson_histories.id as id', 'lessons.lesson_name as lesson_name',
            'lesson_schedules.lesson_starttime as lesson_starttime', 'lesson_schedules.lesson_endtime as lesson_endtime',
            'lesson_schedules.lesson_date as lesson_date', 'courses.course_name as course_name',
            'lesson_texts.lesson_text_name as lesson_text_name', 'teachers.teacher_name as teacher_name',
            'point_subscription_histories.set_course_id as set_course_id',
            'lesson_histories.skype_voice_rating_from_teacher as skype_voice_rating_from_teacher')
            ->leftJoin('courses', function($join) {
                $join->on('lesson_histories.course_id', '=', 'courses.course_id');
            })
            ->leftJoin('lesson_schedules', function($join) {
                $join->on('lesson_histories.lesson_schedule_id', '=', 'lesson_schedules.id');
            })
            ->leftJoin('lessons', function($join) {
                $join->on('lesson_schedules.lesson_id', '=', 'lessons.id');
            })
            ->leftJoin('lesson_texts', function($join) {
                $join->on('lesson_schedules.lesson_text_id', '=', 'lesson_texts.id');
            })
            ->leftJoin('teachers', function($join) {
                $join->on('lesson_schedules.teacher_id', '=', 'teachers.id');
            })
            ->leftJoin('student_point_histories', function($join) {
                $join->on('lesson_schedules.id', '=', 'student_point_histories.lesson_schedule_id');
            })
            ->leftJoin('point_subscription_histories', function($join) {
                $join->on('student_point_histories.point_subscription_id', '=', 'point_subscription_histories.id');
            })
            ->where('lesson_histories.student_lesson_reserve_type', 3)
            ->where('lesson_histories.student_id', $id);

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lesson_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lesson_text_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher_name', $request['search_input']));
            });
        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "lesson_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_schedules.lesson_date','ASC') : $queryBuilder->orderBy('lesson_schedules.lesson_date','DESC');
            }
            if ($request['sort'] == "lesson_starttime") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_schedules.lesson_starttime','ASC') : $queryBuilder->orderBy('lesson_schedules.lesson_starttime','DESC');
            }
            if ($request['sort'] == "course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('courses.course_name','ASC') : $queryBuilder->orderBy('courses.course_name','DESC');
            }
            if ($request['sort'] == "lesson_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lessons.lesson_name','ASC') : $queryBuilder->orderBy('lessons.lesson_name','DESC');
            }
            if ($request['sort'] == "lesson_text_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_texts.lesson_text_name','ASC') : $queryBuilder->orderBy('lesson_texts.lesson_text_name','DESC');
            }
            if ($request['sort'] == "teacher_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('teachers.teacher_name','ASC') : $queryBuilder->orderBy('teachers.teacher_name','DESC');
            }
            if ($request['sort'] == "set_course_id") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('point_subscription_histories.set_course_id','ASC') : $queryBuilder->orderBy('point_subscription_histories.set_course_id','DESC');
            }
        }
        $lessonHistoryList = $queryBuilder->sortable(['lesson_starttime' => 'desc'])->paginate($pageLimit);

        return view('student.lesson-history', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'lessonHistoryList' => $lessonHistoryList,
            'studentInfo' => $studentInfo,
        ]);
    }

    public function showLessonHistory($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        

        $lessonHistoryInfo = LessonHistory::select('lesson_histories.id as id', 'lessons.lesson_name as lesson_name',
            'lesson_schedules.lesson_starttime as lesson_starttime', 'lesson_schedules.lesson_endtime as lesson_endtime',
            'courses.course_name as course_name', 'lesson_histories.student_id as student_id', 'students.student_name as student_name',
            'lesson_texts.lesson_text_name as lesson_text_name', 'teachers.teacher_name as teacher_name',
            'courses.course_id as course_id', 'lesson_histories.teacher_rating as teacher_rating',
            'lesson_histories.teacher_attitude as teacher_attitude', 'lesson_histories.teacher_punctual as teacher_punctual',
            'lesson_histories.skype_voice_rating_from_student as skype_voice_rating_from_student', 'lesson_histories.comment_from_student_to_office as comment_from_student_to_office',
            'lesson_histories.comment_from_teacher_to_student as comment_from_teacher_to_student', 'lesson_histories.skype_voice_rating_from_teacher as skype_voice_rating_from_teacher',
            'lesson_histories.comment_from_admin_to_student as comment_from_admin_to_student')
            ->leftJoin('courses', function($join) {
                $join->on('lesson_histories.course_id', '=', 'courses.course_id');
            })
            ->leftJoin('lesson_schedules', function($join) {
                $join->on('lesson_histories.lesson_schedule_id', '=', 'lesson_schedules.id');
            })
            ->leftJoin('lessons', function($join) {
                $join->on('lesson_schedules.lesson_id', '=', 'lessons.id');
            })
            ->leftJoin('lesson_texts', function($join) {
                $join->on('lesson_schedules.lesson_text_id', '=', 'lesson_texts.id');
            })
            ->leftJoin('teachers', function($join) {
                $join->on('lesson_schedules.teacher_id', '=', 'teachers.id');
            })
            ->leftJoin('students', function($join) {
                $join->on('lesson_histories.student_id', '=', 'students.id');
            })
            ->where('lesson_histories.id', $id)->firstOrFail();

        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_lesson_history_list', $lessonHistoryInfo->student_id],
            ['name' => 'show_student_lesson_history', $id],
        ]);

        $lessonHistoryInfo->_token = csrf_token();
        $lessonHistoryInfo->average = ($lessonHistoryInfo->teacher_rating + $lessonHistoryInfo->teacher_attitude + $lessonHistoryInfo->teacher_punctual + $lessonHistoryInfo->skype_voice_rating_from_student)/4;

        return view('student.show-lesson-history', [
            'breadcrumbs' => $breadcrumbs,
            'lessonHistoryInfo' => $lessonHistoryInfo,
        ]);
    }

    public function updateLessonsHistory(Request $request)
    {
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);          
        }
        $lessonHistoryInfo = LessonHistory::where('id', $request->id)->first();
        if ($lessonHistoryInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $lessonHistoryInfo->comment_from_admin_to_student = $request->comment_from_admin_to_student;

        $lessonHistoryInfo->save();  

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function cancelLessonsHistory(Request $request)
    {
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);          
        }
        $lessonHistoryInfo = LessonHistory::select('lesson_histories.*',
            'lesson_schedules.lesson_starttime as lesson_starttime', 'lesson_schedules.lesson_date as lesson_date',
            'lesson_schedules.teacher_id as teacher_id')
            ->leftJoin('lesson_schedules', function($join) {
                $join->on('lesson_histories.lesson_schedule_id', '=', 'lesson_schedules.id');
            })
            ->where('lesson_histories.id', $request->id)->first();
        DB::beginTransaction();
        try {

            if ($request->cancel_type == 1) {
                $cancel = new LessonCancelHistory;
                $cancel->student_id = $lessonHistoryInfo->student_id;
                $cancel->teacher_id = $lessonHistoryInfo->teacher_id;
                $cancel->lesson_date = $lessonHistoryInfo->lesson_date;
                $cancel->lesson_starttime = $lessonHistoryInfo->lesson_starttime;
                $cancel->reserve_date = $lessonHistoryInfo->reserve_date;
                $cancel->cancel_date = Carbon::now();
                $cancel->cancel_student_comment = "";
                $cancel->cancel_admin_comment = "";
                $cancel->cancel_teacher_comment = "";

                $cancel->save();    
            }

            $lessonOld = new LessonHistoryOld;
            $lessonOld->lesson_history_id = $lessonHistoryInfo->id;
            $lessonOld->lesson_schedule_id = $lessonHistoryInfo->lesson_schedule_id;
            $lessonOld->student_id = $lessonHistoryInfo->student_id;
            $lessonOld->comment_from_student_to_teacher = $lessonHistoryInfo->comment_from_student_to_teacher;
            $lessonOld->comment_from_teacher_to_student = $lessonHistoryInfo->comment_from_teacher_to_student;
            $lessonOld->comment_from_admin_to_student = $lessonHistoryInfo->comment_from_admin_to_student;
            $lessonOld->comment_from_admin_to_teacher = $lessonHistoryInfo->comment_from_admin_to_teacher;
            $lessonOld->note_from_student_to_teacher = $lessonHistoryInfo->note_from_student_to_teacher;
            $lessonOld->teacher_rating = $lessonHistoryInfo->teacher_rating;
            $lessonOld->student_rating = $lessonHistoryInfo->student_rating;
            $lessonOld->note_from_teacher_to_student = $lessonHistoryInfo->note_from_teacher_to_student;
            $lessonOld->student_lesson_reserve_type = $lessonHistoryInfo->student_lesson_reserve_type;
            $lessonOld->reserve_date = $lessonHistoryInfo->reserve_date;
            $lessonOld->accept_comment_to_student = $lessonHistoryInfo->accept_comment_to_student;
            $lessonOld->accept_comment_to_teacher = $lessonHistoryInfo->accept_comment_to_teacher;
            $lessonOld->skype_voice_rating_from_student = $lessonHistoryInfo->skype_voice_rating_from_student;
            $lessonOld->skype_voice_rating_from_teacher = $lessonHistoryInfo->skype_voice_rating_from_teacher;
            $lessonOld->teacher_attitude = $lessonHistoryInfo->teacher_attitude;
            $lessonOld->teacher_punctual = $lessonHistoryInfo->teacher_punctual;
            $lessonOld->comment_from_student_to_office = $lessonHistoryInfo->comment_from_student_to_office;
            $lessonOld->comment_from_teacher_to_office = $lessonHistoryInfo->comment_from_teacher_to_office;
            $lessonOld->course_id = $lessonHistoryInfo->course_id;
            $lessonOld->marks = $lessonHistoryInfo->marks;

            $lessonOld->save();   

            LessonSchedule::where('id', $lessonHistoryInfo->lesson_schedule_id)->delete();
            StudentPointHistory::where('lesson_schedule_id', $lessonHistoryInfo->lesson_schedule_id)->delete();
            LessonHistory::where('id', $lessonHistoryInfo->id)->delete();
        
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'NG',
            ], StatusCode::INTERNAL_ERR);
        }

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
