<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Enums\PaidStatus;
use App\Models\Student;
use App\Models\StudentPublicCommentForTeacher;
use App\Models\LessonHistory;
use App\Models\LessonCancelHistory;
use App\Models\LessonHistoryOld;
use App\Models\StudentPointHistory;
use App\Models\LessonSchedule;
use App\Models\PointSubscriptionHistory;
use App\Http\Requests\StudentCommentRequest;
use App\Http\Requests\PaymentHistoryRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\CourseSetCourse;
use App\Models\StudentList;
use App\Exports\StudentExport;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use App\Models\LmsPrefecture;
use App\Models\TimeZone;
use App\Models\LmsProjectStudent;
use App\Enums\LangTypeOption;
use App\Http\Requests\StudentEditRequest;
use App\Enums\MailType;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail; 
use App\Models\SendRemindMailPattern;
use App\Models\StudentEntryType;
use Hash;
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

    public function updateLessonHistory(Request $request)
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

    public function cancelLessonHistory(Request $request)
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

    public function paymentHistory(Request $request, $id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_payment_history_list', $id],
        ]);
        $pageLimit = $this->newListLimit($request);

        $studentInfo = Student::where('id', $id)->firstOrFail();

        $queryBuilder = PointSubscriptionHistory::select('point_subscription_histories.id as id', 
            'point_subscription_histories.management_number as management_number', 
            'point_subscription_histories.corporation_code as corporation_code', 
            'point_subscription_histories.amount as amount', 
            'point_subscription_histories.payment_date as payment_date',
            'point_subscription_histories.begin_date as begin_date',
            'point_subscription_histories.point_expire_date as point_expire_date',
            'point_subscription_histories.receive_payment_date as receive_payment_date',
            'point_subscription_histories.set_course_id as set_course_id',
            'courses.course_name as course_name',
            'student_point_histories.start_date as start_date',
            DB::raw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN point_subscription_histories.payment_way + point_subscription_histories.paid_status ELSE point_subscription_histories.payment_way END) AS j_paid_status')
        )
        ->leftJoin('courses', function($join) {
            $join->on('point_subscription_histories.course_id', '=', 'courses.course_id');
        })
        ->leftJoin('student_point_histories', function($join) {
            $join->on('point_subscription_histories.id', '=', 'student_point_histories.point_subscription_id');
        })
        ->where('point_subscription_histories.del_flag', 0)
        ->where('point_subscription_histories.student_id', $id)
        ->groupBy('id');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course_name', $request['search_input']));
            });
        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "start_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_point_histories.start_date','ASC') : $queryBuilder->orderBy('student_point_histories.start_date','DESC');
            }
            if ($request['sort'] == "j_paid_status") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('j_paid_status','ASC') : $queryBuilder->orderBy('j_paid_status','DESC');
            }
        }
        $paymentHistoryList = $queryBuilder->sortable(['id' => 'desc'])->paginate($pageLimit);

        return view('student.payment-history', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'paymentHistoryList' => $paymentHistoryList,
            'studentInfo' => $studentInfo,
        ]);
    }

    public function createPaymentHistory($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_payment_history_list', $id],
            ['name' => 'create_student_payment_history', $id],
        ]);

        $studentInfo = Student::where('id', $id)->firstOrFail();

        $paymentType = [];
        $courseList = [];
        if ($studentInfo->is_lms_user) {
            $studentInfo->course_begin_month = Carbon::now();
            $paymentType = PaidStatus::asSelectArray();
            $courseList = DB::select('CALL sp_admin_get_course_list_lms(?,?)', array($studentInfo->id, COURSE_FREE_ID));
        }else {
            $paymentType = [
                0 => 'G',
                1 => 'CSV'
            ];

            $courseList = DB::select('CALL sp_admin_get_course_list');
        }

        $studentInfo->_token = csrf_token();
        $studentInfo->payment_type_list = $paymentType;
        $studentInfo->payment_type = PaidStatus::G;
        $studentInfo->course_id = 0;
        $studentInfo->course_list = $courseList;
        $studentInfo->payment_date = Carbon::now();
        $studentInfo->start_date = Carbon::now();
        $studentInfo->begin_date = Carbon::now();

        return view('student.create-payment-history', [
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
    public function storePaymentHistory(PaymentHistoryRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);           
        }

        $studentInfo = Student::where('id', $request->id)->firstOrFail();

        if ($studentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        if ($studentInfo->is_lms_user) {
            $courseList = DB::select('CALL sp_admin_get_course_list_lms(?,?)', array($studentInfo->id, COURSE_FREE_ID));
        }else {
            $courseList = DB::select('CALL sp_admin_get_course_list');
        }

        $orderId = $studentInfo->id . "alc" . Carbon::now()->format("YmdHis");

        while(strlen($orderId) < 27) {
            $random = rand(0, 9);
            $orderId = "$random"."$orderId";
        }

        $course_begin_month = "";
        if (isset($request->course_begin_month)) {
            $course_begin_month = (new Carbon($request->course_begin_month))->format('Ym');
        }

        $course = Course::where('course_id', $request->course_id)->first();

        $listCourseBySetCourse = [];
        if($course && $course->is_set_course) {
            $listCourseBySetCourse = CourseSetCourse::select('courses.course_name as course_name',
                'courses.course_id as course_id', 'courses.amount as amount')
            ->leftJoin('courses', function($join) {
                $join->on('course_set_course.course_id', '=', 'courses.course_id');
                $join->whereNull('courses.deleted_at');
            })
            ->where('course_set_course.set_course_id', $request->course_id)
            ->whereNull('course_set_course.deleted_at')
            ->orderBy('courses.course_name','ASC') 
            ->get()
            ->toArray();
        }
      
        foreach($courseList as $course1) {
            if($course1->course_id == $course->course_id){
                $course->project_course_id = isset($course1->project_course_id) ? $course1->project_course_id : 0;
                $course->parent_id = isset($course1->parent_id) ? $course1->parent_id : 0;
            }
        }

        if(empty($listCourseBySetCourse)) {
            DB::select('CALL sp_admin_insert_payment_history(?,?,?,?,?,?,?,?,?,?,?,?,?)', 
                array(
                    $orderId,
                    $studentInfo->id,
                    $request->course_id,
                    0,
                    $course->project_course_id,
                    $course->parent_id,
                    $request->payment_type,
                    $request->payment_date,
                    $request->start_date,
                    isset($request->begin_date) ? $request->begin_date : $request->start_date,
                    $request->amount,
                    $request->management_number ?? "",
                    $course_begin_month
                ));
        } else {
            foreach($listCourseBySetCourse as $courseSetCourse) {
                DB::select('CALL sp_admin_insert_payment_history(?,?,?,?,?,?,?,?,?,?,?,?,?)', 
                    array(
                        $orderId,
                        $studentInfo->id,
                        $courseSetCourse['course_id'],
                        $request->course_id,
                        $course->project_course_id,
                        $course->parent_id,
                        $request->payment_type,
                        $request->payment_date,
                        $request->start_date,
                        isset($request->begin_date) ? $request->begin_date : $request->start_date,
                        $request->amount,
                        $request->management_number ?? "",
                        $course_begin_month
                    ));
            }
        }

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function editPaymentHistory($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $paymentInfo = PointSubscriptionHistory::getPaymentHistoryInfo($id);

        if ($paymentInfo == null) {
            return redirect()->route('student.paymentHistoryList', $id); 
        }
        $paymentType = [];
        if ($paymentInfo->is_lms_user) {
            $paymentType = PaidStatus::asSelectArray();
        }else {
            $paymentType = [
                0 => 'G',
                1 => 'CSV'
            ];

        }

        $paymentInfo->_token = csrf_token();
        $paymentInfo->payment_type_list = $paymentType;
        $paymentInfo->is_payment_expired = 0;
        if (!empty($paymentInfo->point_expire_date)) {
            $today = Carbon::today()->format('Y-m-d');
            $currentExpireDate = (new Carbon($paymentInfo->point_expire_date))->format('Y-m-d'); 
            if ($currentExpireDate < $today) {
                $paymentInfo->is_payment_expired = 1;
            }
        }
        if(!empty($paymentInfo->course_begin_month)) {
            $paymentInfo->course_begin_month = Carbon::createFromFormat('Ymd', $paymentInfo->course_begin_month . "01")->format('Y-m');
        }else {
            $paymentInfo->course_begin_month = "";
        }

        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_payment_history_list', $paymentInfo->student_id],
            ['name' => 'edit_student_payment_history', $paymentInfo->student_id],
        ]);
        return view('student.edit-payment-history', [
            'breadcrumbs' => $breadcrumbs,
            'paymentInfo' => $paymentInfo,
        ]);
    }

    public function updatePaymentHistory(PaymentHistoryRequest $request) {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);           
        }

        $paymentInfo = PointSubscriptionHistory::getPaymentHistoryInfo($request->id);

        if ($paymentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        $request->point_expire_date = (new Carbon($request->point_expire_date))->format('Y-m-d 23:59:59');
        if ($request->point_expire_date != $paymentInfo->point_expire_date) {
            $studentPointHistoryIds = StudentPointHistory::where('point_subscription_id', $request->id)
                ->where('pay_type', 5)
                ->pluck('id')
                ->toArray();

            StudentPointHistory::whereIn('id', $studentPointHistoryIds)->delete();
        }
        $course_begin_month = "";
        if (isset($request->course_begin_month)) {
            $course_begin_month = (new Carbon($request->course_begin_month))->format('Ym');
        }

        $management_number = $request->management_number ?? "";
        DB::select('CALL sp_admin_update_payment_history(?,?,?,?,?,?,?,?,?,?,?)', 
            array(
                $paymentInfo->student_id,
                $paymentInfo->id,
                $request->payment_type,
                $request->payment_date,
                $request->start_date,
                $request->begin_date,
                $request->point_expire_date,
                $request->amount,
                $request->tax,
                $management_number,
                $course_begin_month
            ));

        return response()->json([
                'status' => 'OK',
            ], StatusCode::OK);
    }

    public function destroyPaymentHistory(Request $request) {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);           
        }

        DB::select('CALL sp_admin_delete_student_point_subscription(?,?)', array($request->id, $request->cancel_type));
        
        return response()->json([
                'status' => 'OK',
            ], StatusCode::OK);
    }

    public function index(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
        ]);
        $pageLimit = $this->newListLimit($request);

        $queryBuilder = StudentList::select('student_list.student_id as student_id',
            'student_list.student_name as student_name',
            'student_list.student_email as student_email',
            'student_list.student_nickname as student_nickname',
            'student_list.student_skypename as student_skypename',
            'student_list.create_date as create_date',
            'student_list.last_login_at as last_login_at',
            'student_list.is_tmp_entry as is_tmp_entry',
            'student_list.direct_mail_flag as direct_mail_flag',
            'student_list.student_comment_text as student_comment_text',
            DB::raw("(CASE WHEN student_list.is_lms_user = 0 THEN student_list.company_name ELSE '' END) AS custom_company_name"),
            DB::raw("CONCAT('/',GROUP_CONCAT(DISTINCT lms_projects.project_code SEPARATOR '/'),'/') as all_project_code"),
            DB::raw("CONCAT('/',GROUP_CONCAT(DISTINCT lms_companies.company_name SEPARATOR '/'),'/') as all_project_company_name"),
            DB::raw("CONCAT('/',GROUP_CONCAT(DISTINCT NULLIF(IF(is_lms_user = 1, lms_companies.legal_code, point_subscription_histories.corporation_code),'') SEPARATOR '/'),'/') as company_code"),
            DB::raw("MAX(lesson_histories.reserve_date) AS last_reserve_date"),
            DB::raw("MIN(CASE WHEN lesson_histories.student_lesson_reserve_type = 3 THEN lesson_schedules.lesson_starttime END) AS first_lesson_date"),
            DB::raw("COUNT(DISTINCT lesson_histories.id) AS lesson_count"),
            DB::raw("IF(student_list.course_id > 1,'有料','無料') AS course_name"),
            DB::raw("COALESCE(MIN(IF(point_subscription_histories.course_id = 1 AND student_list.is_lms_user = 0, NULL, point_subscription_histories.payment_date)),'---') AS first_payment_date"),
        )
        ->leftJoin('point_subscription_histories', function($join) {
            $join->on('student_list.student_id', '=', 'point_subscription_histories.student_id')
                ->where('point_subscription_histories.del_flag', '=', 0);
        })
        ->leftJoin('lesson_histories', function($join) {
            $join->on('student_list.student_id', '=', 'lesson_histories.student_id')
                ->where('lesson_histories.student_lesson_reserve_type', '<>', 2);
        })
        ->leftJoin('lesson_schedules', function($join) {
            $join->on('lesson_histories.lesson_schedule_id', '=', 'lesson_schedules.id')
                ->where('lesson_histories.student_lesson_reserve_type', '<>', 2);
        })
        ->leftJoin('lms_project_students', function($join) {
            $join->on('student_list.student_id', '=', 'lms_project_students.student_id');
        })
        ->leftJoin('lms_projects', function($join) {
            $join->on('lms_project_students.project_id', '=', 'lms_projects.id');
        })
        ->leftJoin('lms_companies', function($join) {
            $join->on('lms_projects.company_id', '=', 'lms_companies.id');
        })
        ->groupBy('student_list.student_id');

        $queryBuilderCount = DB::table('student_list')->leftJoin('point_subscription_histories', function($join) {
            $join->on('student_list.student_id', '=', 'point_subscription_histories.student_id')
                ->where('point_subscription_histories.del_flag', '=', 0);
        })
        ->leftJoin('lms_project_students', function($join) {
            $join->on('student_list.student_id', '=', 'lms_project_students.student_id');
        })
        ->leftJoin('lms_projects', function($join) {
            $join->on('lms_project_students.project_id', '=', 'lms_projects.id');
        })
        ->leftJoin('lms_companies', function($join) {
            $join->on('lms_projects.company_id', '=', 'lms_companies.id');
        })
        ->where('student_list.course_id', '>', 1)
        ->distinct('student_list.student_id');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('student_list.student_id', '=',$request['search_input'])
                    ->orWhere($this->escapeLikeSentence('student_list.student_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student_list.student_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student_list.student_email', $request['search_input']));
            });

            $queryBuilderCount = $queryBuilderCount->where(function ($query) use ($request) {
                $query->where('student_list.student_id', '=', $request['search_input'])
                    ->orWhere($this->escapeLikeSentence('student_list.student_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student_list.student_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student_list.student_email', $request['search_input']));
            });
        }
        if (isset($request['student_id'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student_list.student_name', $request['student_name']))
                    ->where($this->escapeLikeSentence('student_list.student_nickname', $request['student_nickname']))
                    ->where($this->escapeLikeSentence('student_list.student_skypename', $request['student_skypename']))
                    ->where($this->escapeLikeSentence('student_list.student_email', $request['student_email']))
                    ->where($this->escapeLikeSentence('lms_companies.company_name', $request['all_project_company_name']))
                    ->where($this->escapeLikeSentence('student_list.company_name', $request['custom_company_name']))
                    ->where($this->escapeLikeSentence('lms_projects.project_code', $request['all_project_code']));

                    if(!isset($request['check_company_code'])) {
                        $query->where(function($query) use ($request) {
                            $query->orWhere($this->escapeLikeSentence('lms_companies.legal_code', $request['company_code']))
                                ->orWhere($this->escapeLikeSentence('point_subscription_histories.corporation_code', $request['company_code']));
                        });
                    }
                    if(isset($request['check_company_code'])) {
                        $query->where(function($query) {
                            $query->orWhere('lms_companies.legal_code', '=', '')
                                ->orWhereNull('lms_companies.legal_code')
                                ->orWhere('point_subscription_histories.corporation_code', '=', '')
                                ->orWhereNull('point_subscription_histories.corporation_code');
                        });
                    }
                    if ($request['student_id'] != "") {
                        $query->where('student_list.student_id', '=', $request['student_id']);
                    }
                    if ($request['first_lesson_date'] != "") {
                        $query->where('student_list.create_date', '>=', $request['first_lesson_date']);
                    }
            });

            $queryBuilderCount = $queryBuilderCount->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student_list.student_name', $request['student_name']))
                    ->where($this->escapeLikeSentence('student_list.student_nickname', $request['student_nickname']))
                    ->where($this->escapeLikeSentence('student_list.student_skypename', $request['student_skypename']))
                    ->where($this->escapeLikeSentence('student_list.student_email', $request['student_email']))
                    ->where($this->escapeLikeSentence('lms_companies.company_name', $request['all_project_company_name']))
                    ->where($this->escapeLikeSentence('student_list.company_name', $request['custom_company_name']))
                    ->where($this->escapeLikeSentence('lms_projects.project_code', $request['all_project_code']));

                    if(!isset($request['check_company_code'])) {
                        $query->where(function($query) use ($request) {
                            $query->orWhere($this->escapeLikeSentence('lms_companies.legal_code', $request['company_code']))
                                ->orWhere($this->escapeLikeSentence('point_subscription_histories.corporation_code', $request['company_code']));
                        });
                    }
                    if(isset($request['check_company_code'])) {
                        $query->where(function($query) {
                            $query->orWhere('lms_companies.legal_code', '=', '')
                                ->orWhereNull('lms_companies.legal_code')
                                ->orWhere('point_subscription_histories.corporation_code', '=', '')
                                ->orWhereNull('point_subscription_histories.corporation_code');
                        });
                    }

                    if ($request['student_id'] != "") {
                        $query->where('student_list.student_id', '=', $request['student_id']);
                    }
                    if ($request['first_lesson_date'] != "") {
                        $query->where('student_list.create_date', '>=', $request['first_lesson_date']);
                    }
            });

        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "custom_company_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('custom_company_name','ASC') : $queryBuilder->orderBy('custom_company_name','DESC');
            }
            if ($request['sort'] == "all_project_code") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('all_project_code','ASC') : $queryBuilder->orderBy('all_project_code','DESC');
            }
            if ($request['sort'] == "all_project_company_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('all_project_company_name','ASC') : $queryBuilder->orderBy('all_project_company_name','DESC');
            }
            if ($request['sort'] == "company_code") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('company_code','ASC') : $queryBuilder->orderBy('company_code','DESC');
            }
            if ($request['sort'] == "last_reserve_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('last_reserve_date','ASC') : $queryBuilder->orderBy('last_reserve_date','DESC');
            }
            if ($request['sort'] == "first_lesson_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('first_lesson_date','ASC') : $queryBuilder->orderBy('first_lesson_date','DESC');
            }
            if ($request['sort'] == "lesson_count") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_count','ASC') : $queryBuilder->orderBy('lesson_count','DESC');
            }
            if ($request['sort'] == "course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('course_name','ASC') : $queryBuilder->orderBy('course_name','DESC');
            }
        }

        Session::put('sessionStudent', json_encode($request));

        $studentList = $queryBuilder->sortable(['student_id' => 'desc'])->paginate($pageLimit);
        $number_published = $queryBuilderCount->count();
        $number_unpublished = $studentList->total() - $number_published;
        return view('student.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'studentList' => $studentList,
            'number_published' => $number_published,
            'number_unpublished' => $number_unpublished,
        ]);
    }

    public function export()
    {
        $request = Session::get('sessionStudent');
        $fileName = "student_list_".date("Y_m_d").".csv";
        return Excel::download(new StudentExport($request), $fileName);
    }

    public function edit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'edit_student', $id],
        ]);

        $studentInfo = Student::where('id', $id)->firstOrFail();

        $studentInfo->student_entry_types = StudentEntryType::pluck('student_entry_type_name', 'student_entry_type_id')->toArray();
        $studentInfo->lang_types = LangTypeOption::asSelectArray();
        $studentInfo->lms_prefectures = LmsPrefecture::whereNull('deleted_at')->pluck('prefecture_name', 'id')->toArray();
        $studentInfo->time_zones = TimeZone::where('id', 1)->pluck('timezone_name_native', 'id')->toArray();
        if ($studentInfo->is_lms_user) {
            $studentInfo->lms_project_students = LmsProjectStudent::select('lms_companies.company_name as company_name',
                'lms_projects.project_code as project_code',
                'lms_companies.legal_code as legal_code',
                'lms_projects.corporation_flag as corporation_flag',
                'lms_projects.buy_course_continue as buy_course_continue',
                'lms_project_students.id as id',
                'lms_project_students.buy_course_flag as buy_course_flag',
                'lms_project_students.department_name as department_name',
                'lms_project_students.employee_number as employee_number',
                'lms_project_students.department_number as department_number',
            )
                ->Join('students', function($join) {
                    $join->on('lms_project_students.student_id', '=', 'students.id');
                })
                ->Join('lms_projects', function($join) {
                    $join->on('lms_project_students.project_id', '=', 'lms_projects.id');
                })
                ->leftJoin('lms_companies', function($join) {
                    $join->on('lms_project_students.company_id', '=', 'lms_companies.id');
                })
                ->where('lms_project_students.student_id', $id)
                ->whereNull('lms_project_students.deleted_at')
                ->get()
                ->toArray();
        }
        
        $studentInfo->_token = csrf_token();

        return view('student.edit', [
            'breadcrumbs' => $breadcrumbs,
            'studentInfo' => $studentInfo,
        ]);
    }

    public function update(StudentEditRequest $request)
    {
        if(!$request->isMethod('PUT')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);          
        }
 
        $studentInfo = Student::where('id', $request->id)->first();
        if ($studentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        $studentInfo->is_tmp_entry = $request->is_tmp_entry;
        $studentInfo->student_first_name = $request->student_first_name;
        $studentInfo->student_last_name = $request->student_last_name;
        $studentInfo->student_name = $request->student_first_name ." ". $request->student_last_name;
        $studentInfo->student_first_name_kata = $request->student_first_name_kata;
        $studentInfo->student_last_name_kata = $request->student_last_name_kata;
        $studentInfo->student_name_kana = ($request->student_first_name_kata ?? "") ." ".  ($request->student_last_name_kata ?? "");
        $studentInfo->student_skypename = $request->student_skypename;
        $studentInfo->student_nickname = $request->student_nickname;
        $studentInfo->student_email = $request->student_email;
        if (isset($request->company_name)) {
            $studentInfo->company_name = $request->company_name;
        }
        $studentInfo->student_introduction = $request->student_introduction;
        $studentInfo->student_home_tel = $request->student_home_tel;
        $studentInfo->postcode = $request->postcode;
        $studentInfo->prefecture_number = $request->prefecture_number;
        $studentInfo->student_address = $request->student_address;
        $studentInfo->student_address1 = $request->student_address1;
        $studentInfo->student_address2 = $request->student_address2;
        $studentInfo->student_address3 = $request->student_address3;
        $studentInfo->is_sending_dm = $request->is_sending_dm;
        $studentInfo->direct_mail_flag = $request->direct_mail_flag;
        $studentInfo->lang_type = $request->lang_type;
        $studentInfo->timezone_id = $request->timezone_id;
        $studentInfo->student_comment_text = $request->student_comment_text;
        $studentInfo->in_japan_flag = $request->in_japan_flag;

        $studentInfo->save();  

        if($studentInfo['is_lms_user']) {
            foreach($request->lms_project_students as $lmsProject) {
                $lmsProjectInfo = LmsProjectStudent::where('id', $lmsProject['id'])->first();

                $lmsProjectInfo['department_name'] = $lmsProject['department_name'];
                $lmsProjectInfo['employee_number'] = $lmsProject['employee_number'];
                $lmsProjectInfo['department_number'] = $lmsProject['department_number'];
                $lmsProjectInfo['buy_course_flag'] = $lmsProject['buy_course_flag'];

                $lmsProjectInfo->save();
            }
        }

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function destroy($id)
    {
        try {
            Student::where('id', $id)->delete();
            StudentPointHistory::where('student_id', $id)->delete();
            LmsProjectStudent::where('student_id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => '生徒削除が完了しました',
        ], StatusCode::OK);
    }

    public function updatePassword(Request $request)
    {
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);          
        }
        
        $studentInfo = Student::where('id', $request->id)->first();
        
        if ($studentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $studentInfo->password = Hash::make($request->password);
        $studentInfo->save();  
        $langType = 'jp';
        if ($studentInfo->lang_type == 2) {
            $langType = 'en';
        } else if ($studentInfo->lang_type == 3) {
            $langType = 'vn';
        }
        $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo(MailType::CHANGEPASSSTUDENT, $langType);
        if ($mailPattern) {
            $mailSubject = $mailPattern->mail_subject;
            $mailBody = $mailPattern->mail_body;
            $mailBody = str_replace("#STUDENT_NAME#", $studentInfo->student_name, $mailBody);
            
            Mail::raw($mailBody, function ($message) use ($studentInfo, $mailSubject) {
                $message->to($studentInfo->student_email)
                    ->subject($mailSubject);
            });
        }

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
