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
use App\Enums\StudentEntryType as StudentEntryTypeEnum;
use App\Enums\LangType;
use Hash;
use Log; 
use App\Components\CommonComponent;
use App\Components\DateTimeComponent;
use Response;
use App\Enums\AdminRole;
use App\Enums\LmsUserEnum;
use Auth;
use App\Models\Country;
 
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

        $studentInfo = Student::where('student_id', $id)->firstOrFail();

        $queryBuilder = StudentPublicCommentForTeacher::select('student_public_comment_for_teacher.student_public_comment_for_teacher_id as id', 'student_public_comment_for_teacher.create_date as create_date', 'student_public_comment_for_teacher.update_date as update_date', 'comment', 'teacher.teacher_nickname as teacher_nickname')
        ->leftJoin('teacher', function($join) {
                $join->on('student_public_comment_for_teacher.teacher_id', '=', 'teacher.teacher_id');
        })->where('student_public_comment_for_teacher.student_id', $id);

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('comment', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher_nickname', $request['search_input']));
            });
        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "teacher_nickname") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('teacher.teacher_nickname','ASC') : $queryBuilder->orderBy('teacher.teacher_nickname','DESC');
            }
        }
        $commentList = $queryBuilder->sortable(['update_date' => 'desc'])->paginate($pageLimit);

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

        $studentInfo = Student::select('student_id', 'student_name')->where('student_id', $id)->firstOrFail();
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
        $studentInfo = Student::select('student_id', 'student_name')->where('student_id', $request->student_id)->firstOrFail();
        if ($studentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        $comment = new StudentPublicCommentForTeacher;
        $comment->student_id = $request->student_id;
        $comment->create_date = Carbon::now();
        $comment->update_date = Carbon::now();
        $comment->comment = $request->comment ?? "";
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
        
        $commentInfo = StudentPublicCommentForTeacher::select('student_public_comment_for_teacher.student_public_comment_for_teacher_id as id', 
            'student_public_comment_for_teacher.student_id as student_id', 'student.student_name as student_name',
            'teacher.teacher_nickname as teacher_nickname', 'student_public_comment_for_teacher.create_date as create_date',
            'student_public_comment_for_teacher.update_date as update_date', 'student_public_comment_for_teacher.comment as comment')
        ->leftJoin('teacher', function($join) {
            $join->on('student_public_comment_for_teacher.teacher_id', '=', 'teacher.teacher_id');
        })
        ->leftJoin('student', function($join) {
            $join->on('student_public_comment_for_teacher.student_id', '=', 'student.student_id');
        })
        ->where('student_public_comment_for_teacher.student_public_comment_for_teacher_id', $id)->firstOrFail();

        $commentInfo->_token = csrf_token();

        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_comment_list', $commentInfo->student_id],
            ['name' => 'student_create_comment', $id],
        ]);

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
        $commentInfo = StudentPublicCommentForTeacher::where('student_public_comment_for_teacher_id', $request->id)->first();
        if ($commentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $commentInfo->comment = $request->comment;
        $commentInfo->update_date = Carbon::now();

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
            StudentPublicCommentForTeacher::where('student_public_comment_for_teacher_id', $id)->delete();

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

        $studentInfo = Student::where('student_id', $id)->firstOrFail();

        $queryBuilder = LessonHistory::select('lesson_history.lesson_history_id as lesson_history_id', 'lesson.lesson_name as lesson_name',
            'lesson_schedule.lesson_starttime as lesson_starttime', 'lesson_schedule.lesson_endtime as lesson_endtime',
            'lesson_schedule.lesson_date as lesson_date', 'course.course_name as course_name',
            'lesson_text.lesson_text_name as lesson_text_name', 'teacher.teacher_name as teacher_name',
            'point_subscription_history.set_course_id as set_course_id',
            'lesson_history.skype_voice_rating_from_teacher as skype_voice_rating_from_teacher')
            ->leftJoin('course', function($join) {
                $join->on('lesson_history.course_id', '=', 'course.course_id');
            })
            ->leftJoin('lesson_schedule', function($join) {
                $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id');
            })
            ->leftJoin('lesson', function($join) {
                $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
            })
            ->leftJoin('lesson_text', function($join) {
                $join->on('lesson_schedule.lesson_text_id', '=', 'lesson_text.lesson_text_id');
            })
            ->leftJoin('teacher', function($join) {
                $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
            })
            ->leftJoin('student_point_history', function($join) {
                $join->on('lesson_schedule.lesson_schedule_id', '=', 'student_point_history.lesson_schedule_id');
            })
            ->leftJoin('point_subscription_history', function($join) {
                $join->on('student_point_history.point_subscription_id', '=', 'point_subscription_history.point_subscription_history_id');
            })
            ->where('lesson_history.student_lesson_reserve_type', 3)
            ->where('lesson_history.student_id', $id);

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
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_schedule.lesson_date','ASC') : $queryBuilder->orderBy('lesson_schedule.lesson_date','DESC');
            }
            if ($request['sort'] == "lesson_starttime") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_schedule.lesson_starttime','ASC') : $queryBuilder->orderBy('lesson_schedule.lesson_starttime','DESC');
            }
            if ($request['sort'] == "course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('course.course_name','ASC') : $queryBuilder->orderBy('course.course_name','DESC');
            }
            if ($request['sort'] == "lesson_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson.lesson_name','ASC') : $queryBuilder->orderBy('lesson.lesson_name','DESC');
            }
            if ($request['sort'] == "lesson_text_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lesson_text.lesson_text_name','ASC') : $queryBuilder->orderBy('lesson_text.lesson_text_name','DESC');
            }
            if ($request['sort'] == "teacher_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('teacher.teacher_name','ASC') : $queryBuilder->orderBy('teacher.teacher_name','DESC');
            }
            if ($request['sort'] == "set_course_id") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('point_subscription_history.set_course_id','ASC') : $queryBuilder->orderBy('point_subscription_history.set_course_id','DESC');
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
        
        $lessonHistoryInfo = LessonHistory::select('lesson_history.lesson_history_id as lesson_history_id', 'lesson.lesson_name as lesson_name',
            'lesson_schedule.lesson_starttime as lesson_starttime', 'lesson_schedule.lesson_endtime as lesson_endtime',
            'course.course_name as course_name', 'lesson_history.student_id as student_id', 'student.student_name as student_name',
            'lesson_text.lesson_text_name as lesson_text_name', 'teacher.teacher_name as teacher_name',
            'course.course_id as course_id', 'lesson_history.teacher_rating as teacher_rating',
            'lesson_history.teacher_attitude as teacher_attitude', 'lesson_history.teacher_punctual as teacher_punctual',
            'lesson_history.skype_voice_rating_from_student as skype_voice_rating_from_student', 'lesson_history.comment_from_student_to_office as comment_from_student_to_office',
            'lesson_history.comment_from_teacher_to_student as comment_from_teacher_to_student', 'lesson_history.skype_voice_rating_from_teacher as skype_voice_rating_from_teacher',
            'lesson_history.comment_from_admin_to_student as comment_from_admin_to_student')
            ->leftJoin('course', function($join) {
                $join->on('lesson_history.course_id', '=', 'course.course_id');
            })
            ->leftJoin('lesson_schedule', function($join) {
                $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id');
            })
            ->leftJoin('lesson', function($join) {
                $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
            })
            ->leftJoin('lesson_text', function($join) {
                $join->on('lesson_schedule.lesson_text_id', '=', 'lesson_text.lesson_text_id');
            })
            ->leftJoin('teacher', function($join) {
                $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
            })
            ->leftJoin('student', function($join) {
                $join->on('lesson_history.student_id', '=', 'student.student_id');
            })
            ->where('lesson_history.lesson_history_id', $id)->firstOrFail();

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
        $lessonHistoryInfo = LessonHistory::where('lesson_history_id', $request->lesson_history_id)->first();
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
        $lessonHistoryInfo = LessonHistory::select('lesson_history.*',
            'lesson_schedule.lesson_starttime as lesson_starttime', 'lesson_schedule.lesson_date as lesson_date',
            'lesson_schedule.teacher_id as teacher_id')
            ->leftJoin('lesson_schedule', function($join) {
                $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id');
            })
            ->where('lesson_history.lesson_history_id', $request->lesson_history_id)->first();
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
            $lessonOld->lesson_history_id = $lessonHistoryInfo->lesson_history_id;
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

            LessonSchedule::where('lesson_schedule_id', $lessonHistoryInfo->lesson_schedule_id)->delete();
            StudentPointHistory::where('lesson_schedule_id', $lessonHistoryInfo->lesson_schedule_id)->delete();
            LessonHistory::where('lesson_history_id', $lessonHistoryInfo->lesson_history_id)->delete();
        
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

        $studentInfo = Student::where('student_id', $id)->firstOrFail();

        $queryBuilder = PointSubscriptionHistory::select('point_subscription_history.point_subscription_history_id as point_subscription_history_id', 
            'point_subscription_history.management_number as management_number', 
            'point_subscription_history.corporation_code as corporation_code', 
            'point_subscription_history.amount as amount', 
            'point_subscription_history.payment_date as payment_date',
            'point_subscription_history.begin_date as begin_date',
            'point_subscription_history.point_expire_date as point_expire_date',
            'point_subscription_history.receive_payment_date as receive_payment_date',
            'point_subscription_history.set_course_id as set_course_id',
            'course.course_name as course_name',
            'course.course_type',
            DB::raw('(CASE WHEN point_subscription_history.payment_way = 2 THEN point_subscription_history.payment_way + point_subscription_history.paid_status ELSE point_subscription_history.payment_way END) AS j_paid_status')
        )
        ->leftJoin('course', function($join) {
            $join->on('point_subscription_history.course_id', '=', 'course.course_id');
        })
        ->where('point_subscription_history.del_flag', 0)
        ->where('point_subscription_history.student_id', $id)
        ->groupBy('point_subscription_history_id');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course_name', $request['search_input']));
            });
        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "j_paid_status") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('j_paid_status','ASC') : $queryBuilder->orderBy('j_paid_status','DESC');
            }
        }
        $paymentHistoryList = $queryBuilder->sortable(['point_subscription_history_id' => 'desc'])->paginate($pageLimit);

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

        $studentInfo = Student::where('student_id', $id)->firstOrFail();

        $paymentType = [];
        $courseList = [];
        if ($studentInfo->is_lms_user) {
            $paymentType = PaidStatus::asSelectArray();
            $courseList = DB::select('CALL sp_admin_get_course_list_lms(?,?)', array($studentInfo->student_id, COURSE_FREE_ID));
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

        $studentInfo = Student::where('student_id', $request->student_id)->firstOrFail();

        if ($studentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        if ($studentInfo->is_lms_user) {
            $courseList = DB::select('CALL sp_admin_get_course_list_lms(?,?)', array($studentInfo->student_id, COURSE_FREE_ID));
        }else {
            $courseList = DB::select('CALL sp_admin_get_course_list');
        }

        $orderId = $studentInfo->student_id . "alc" . Carbon::now()->format("YmdHis");

        while(strlen($orderId) < 27) {
            $random = rand(0, 9);
            $orderId = "$random"."$orderId";
        }

        $course = Course::where('course_id', $request->course_id)->first();

        $listCourseBySetCourse = [];
        if($course && $course->is_set_course) {
            $listCourseBySetCourse = CourseSetCourse::select('course.course_name as course_name',
                'course.course_id as course_id', 'course.amount as amount')
            ->leftJoin('course', function($join) {
                $join->on('course_set_course.course_id', '=', 'course.course_id');
            })
            ->where('course_set_course.set_course_id', $request->course_id)
            ->where('course_set_course.delete_flag', 0)
            ->orderBy('course.course_name','ASC') 
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
                    $studentInfo->student_id,
                    $request->course_id,
                    0,
                    $course->project_course_id,
                    $course->parent_id,
                    $request->payment_type,
                    $request->payment_date,
                    '',
                    isset($request->begin_date) ? $request->begin_date : $request->start_date,
                    $request->amount,
                    "",
                    ''
                ));
        } else {
            foreach($listCourseBySetCourse as $courseSetCourse) {
                DB::select('CALL sp_admin_insert_payment_history(?,?,?,?,?,?,?,?,?,?,?,?,?)', 
                    array(
                        $orderId,
                        $studentInfo->student_id,
                        $courseSetCourse['course_id'],
                        $request->course_id,
                        $course->project_course_id,
                        $course->parent_id,
                        $request->payment_type,
                        $request->payment_date,
                        '',
                        isset($request->begin_date) ? $request->begin_date : $request->start_date,
                        $request->amount,
                        "",
                        ''
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

        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_payment_history_list', $paymentInfo->student_id],
            ['name' => 'edit_student_payment_history', $id],
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

        $paymentInfo = PointSubscriptionHistory::getPaymentHistoryInfo($request->point_subscription_history_id);

        if ($paymentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        $request->point_expire_date = (new Carbon($request->point_expire_date))->format('Y-m-d 23:59:59');
        if ($request->point_expire_date != $paymentInfo->point_expire_date) {
            $studentPointHistoryIds = StudentPointHistory::where('point_subscription_id', $request->point_subscription_history_id)
                ->where('pay_type', 5)
                ->pluck('student_point_history_id')
                ->toArray();

            StudentPointHistory::whereIn('student_point_history_id', $studentPointHistoryIds)->delete();
        }
       
        DB::select('CALL sp_admin_update_payment_history(?,?,?,?,?,?,?,?,?,?,?)', 
            array(
                $paymentInfo->student_id,
                $paymentInfo->point_subscription_history_id,
                $request->payment_type,
                $request->payment_date,
                $request->start_date,
                $request->begin_date,
                $request->point_expire_date,
                $request->amount,
                $request->tax,
                '',
                ''
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

        DB::select('CALL sp_admin_delete_student_point_subscription(?,?)', array($request->point_subscription_history_id, $request->cancel_type));
        
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

        $queryBuilder = Student::select('student.student_id as student_id',
                'student.student_name as student_name',
                'student.student_nickname as student_nickname',
                'student.student_email as student_email',
                'student.last_login_date as last_login_date',
                DB::raw("COUNT(DISTINCT lesson_history.lesson_history_id) AS lesson_count"),
                'student.create_date as create_date',
                DB::raw("MAX(lesson_history.reserve_date) AS last_reserve_date"),
                'student.is_tmp_entry as is_tmp_entry',
                DB::raw("IF(student.course_id > 1,'有料','無料') AS course_name"),
                DB::raw("MIN(CASE WHEN lesson_history.student_lesson_reserve_type = 3 THEN lesson_schedule.lesson_starttime END) AS first_lesson_date"),
                'student.company_name as custom_company_name'
            )
            ->leftJoin('lesson_history', function($join) {
                $join->on('student.student_id', '=', 'lesson_history.student_id')
                    ->where('lesson_history.student_lesson_reserve_type', '<>', 2);
            })
            ->leftJoin('lesson_schedule', function($join) {
                $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id')
                    ->where('lesson_history.student_lesson_reserve_type', '<>', 2);
            })
            ->groupBy('student.student_id');

        $queryBuilderCount = Student::where('student.course_id', '>', 1);

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('student.student_id', '=',$request['search_input'])
                    ->orWhere($this->escapeLikeSentence('student.student_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_email', $request['search_input']));
            });

            $queryBuilderCount = $queryBuilderCount->where(function ($query) use ($request) {
                $query->where('student.student_id', '=', $request['search_input'])
                    ->orWhere($this->escapeLikeSentence('student.student_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_email', $request['search_input']));
            });
        }
        if (isset($request['student_id'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student.student_name', $request['student_name']))
                    ->where($this->escapeLikeSentence('student.student_nickname', $request['student_nickname']))
                    ->where($this->escapeLikeSentence('student.student_email', $request['student_email']))
                    ->where($this->escapeLikeSentence('student.company_name', $request['custom_company_name']));

                    if ($request['student_id'] != "") {
                        $query->where('student.student_id', '=', $request['student_id']);
                    }
                    if ($request['create_date'] != "") {
                        $query->where('student.create_date', '>=', $request['create_date']);
                    }
            });

            $queryBuilderCount = $queryBuilderCount->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student.student_name', $request['student_name']))
                    ->where($this->escapeLikeSentence('student.student_nickname', $request['student_nickname']))
                    ->where($this->escapeLikeSentence('student.student_email', $request['student_email']))
                    ->where($this->escapeLikeSentence('student.company_name', $request['custom_company_name']));

                    if ($request['student_id'] != "") {
                        $query->where('student.student_id', '=', $request['student_id']);
                    }
                    if ($request['create_date'] != "") {
                        $query->where('student.create_date', '>=', $request['create_date']);
                    }
            });

        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "custom_company_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('custom_company_name','ASC') : $queryBuilder->orderBy('custom_company_name','DESC');
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
            if ($request['sort'] == "student_id") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_id','ASC') : $queryBuilder->orderBy('student_id','DESC');
            }
            if ($request['sort'] == "student_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_name','ASC') : $queryBuilder->orderBy('student_name','DESC');
            }
            if ($request['sort'] == "student_nickname") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_nickname','ASC') : $queryBuilder->orderBy('student_nickname','DESC');
            }
            if ($request['sort'] == "create_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('create_date','ASC') : $queryBuilder->orderBy('create_date','DESC');
            }
            if ($request['sort'] == "last_login_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('last_login_date','ASC') : $queryBuilder->orderBy('last_login_date','DESC');
            }
            if ($request['sort'] == "is_tmp_entry") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('is_tmp_entry','ASC') : $queryBuilder->orderBy('is_tmp_entry','DESC');
            }
        }

        Session::put('sessionStudent', collect($request));

        $studentList = $queryBuilder->paginate($pageLimit);
        $number_published = $queryBuilderCount->count();
        $number_unpublished = $studentList->total() - $number_published;

        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;

        return view('student.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'studentList' => $studentList,
            'number_published' => $number_published,
            'number_unpublished' => $number_unpublished,
            'adminSystem' => $adminSystem,
        ]);
    }

    public function export()
    {
        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;
        if (!$adminSystem) {
            return;
        }
        $request = Session::get('sessionStudent');
        $fileName = "student_list_".date("Y-m-d").".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $header = [
            $this->convertShijis("学習者番号"), 
            $this->convertShijis("学習者名"), 
            $this->convertShijis("学習者メール"), 
            $this->convertShijis("法人名"), 
            $this->convertShijis("初回登録日"), 
            $this->convertShijis("最終ログイン日"), 
            $this->convertShijis("初回受講日"), 
            $this->convertShijis("通算受講回数"), 
            $this->convertShijis("登録状態"), 
            $this->convertShijis("有料/無料"), 
            $this->convertShijis("DMステータス"), 
            $this->convertShijis("連絡事項")
        ]; 

        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $header);

        $queryBuilder = Student::select('student.student_id as student_id',
            'student.student_name as student_name',
            'student.student_email as student_email',
            DB::raw("(CASE WHEN student.is_lms_user = 0 THEN student.company_name ELSE '' END) AS custom_company_name"),
            'student.create_date as create_date',
            'student.last_login_date as last_login_date',  
            DB::raw("MIN(CASE WHEN lesson_history.student_lesson_reserve_type = 3 THEN lesson_schedule.lesson_starttime END) AS first_lesson_date"),  
            DB::raw("COUNT(DISTINCT lesson_history.lesson_history_id) AS lesson_count"),  
            'student.is_tmp_entry as is_tmp_entry',  
            DB::raw("IF(student.course_id > 1,'有料','無料') AS course_name"),  
            'student.direct_mail_flag as direct_mail_flag',  
            'student.student_comment_text as student_comment_text',  
        )
        ->leftJoin('lesson_history', function($join) {
            $join->on('student.student_id', '=', 'lesson_history.student_id')
                ->where('lesson_history.student_lesson_reserve_type', '<>', 2);
        })
        ->leftJoin('lesson_schedule', function($join) {
            $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id')
                ->where('lesson_history.student_lesson_reserve_type', '<>', 2);
        })
        ->groupBy('student.student_id');
        	
        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('student.student_id', '=',$request['search_input'])
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_nickname', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_email', $request['search_input']));
            });
        }
        if (isset($request['search_detail'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where(CommonComponent::escapeLikeSentence('student.student_name', $request['student_name']))
                    ->where(CommonComponent::escapeLikeSentence('student.student_nickname', $request['student_nickname']))
                    ->where(CommonComponent::escapeLikeSentence('student.student_email', $request['student_email']))
                    ->where(CommonComponent::escapeLikeSentence('student.company_name', $request['custom_company_name']));

                    if ($request['student_id'] != "") {
                        $query->where('student.student_id', '=', $request['student_id']);
                    }
                    if ($request['create_date'] != "") {
                        $query->where('student.create_date', '>=', $request['create_date']);
                    }
            });
        }

        $studentList = $queryBuilder->get()->map(function($item, $key) {
            $item['create_date'] = DateTimeComponent::getDate($item['create_date']);
            $item['first_lesson_date'] = DateTimeComponent::getDate($item['first_lesson_date']);
            $item['is_tmp_entry'] = StudentEntryTypeEnum::getDescription($item['is_tmp_entry']);

            if (is_null($item['direct_mail_flag']) === true) {
                $item['direct_mail_flag'] = "";
            } else if(!$item['direct_mail_flag']) {
                $item['direct_mail_flag'] = "0";
            } else {
                $item['direct_mail_flag'] = $item['direct_mail_flag'];
            }
        	return $item;
        });

        foreach ($studentList as &$item) {
            $input = [];
            $input["学習者番号"] = $this->convertShijis($item['student_id']);
            $input["学習者名"] = $this->convertShijis($item['student_name']);
            $input["学習者メール"] = $this->convertShijis($item['student_email']);
            $input["法人名"] = $this->convertShijis($item['custom_company_name']);
            $input["初回登録日"] = $this->convertShijis($item['create_date']);
            $input["最終ログイン日"] = $this->convertShijis($item['last_login_date']);
            $input["初回受講日"] = $this->convertShijis($item['first_lesson_date']);
            $input["通算受講回数"] = $this->convertShijis($item['lesson_count']);
            $input["登録状態"] = $this->convertShijis($item['is_tmp_entry']);
            $input["有料/無料"] = $this->convertShijis($item['course_name']);
            $input["DMステータス"] = $this->convertShijis($item['direct_mail_flag']);
            $input["連絡事項"] = $this->convertShijis($item['student_comment_text']);
            fputcsv($file, $input);
        }

        return Response::download(public_path().'/csv_file/users/'.$fileName, $fileName, $header);
    }

    public function edit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'edit_student', $id],
        ]);

        $studentInfo = Student::where('student_id', $id)->firstOrFail();

        $studentInfo->student_entry_types = StudentEntryType::pluck('student_entry_type_name', 'student_entry_type_id')->toArray();
        $studentInfo->lang_types = LangTypeOption::asSelectArray();
        $studentInfo->lms_prefectures = LmsPrefecture::where('delete_flag', 0)->pluck('prefecture_name', 'prefecture_id')->toArray();
        $studentInfo->time_zones = TimeZone::pluck('timezone_name_native', 'timezone_id')->toArray();
        $studentInfo->lms_project_students = LmsProjectStudent::select('lms_company.company_name as company_name',
            'lms_project.project_code as project_code',
            'lms_company.legal_code as legal_code',
            'lms_project.corporation_flag as corporation_flag',
            'lms_project.buy_course_continue as buy_course_continue',
            'lms_project_student.project_student_id as project_student_id',
            'lms_project_student.buy_course_flag as buy_course_flag',
            'lms_project_student.department_name as department_name',
            'lms_project_student.employee_number as employee_number',
            'lms_project_student.department_number as department_number',
        )
            ->Join('student', function($join) {
                $join->on('lms_project_student.student_id', '=', 'student.student_id');
            })
            ->Join('lms_project', function($join) {
                $join->on('lms_project_student.project_id', '=', 'lms_project.project_id');
            })
            ->leftJoin('lms_company', function($join) {
                $join->on('lms_project_student.company_id', '=', 'lms_company.company_id');
            })
            ->where('lms_project_student.student_id', $id)
            ->where('lms_project_student.delete_flag', 0)
            ->get()
            ->toArray();
        
        $studentInfo->_token = csrf_token();
        $studentInfo->lms_user = LmsUserEnum::CORPORATION;

        $studentInfo->student_first_name=explode(' ', $studentInfo->student_name,2)[0];
        $studentInfo->student_last_name=explode(' ', $studentInfo->student_name,2)[1];

        $studentInfo->countries = Country::pluck('country_name', 'country_id');

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
 
        $studentInfo = Student::where('student_id', $request->student_id)->first();
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
        $studentInfo->student_nickname = $request->student_nickname;
        $studentInfo->student_email = $request->student_email;
        $studentInfo->student_sex = $request->student_sex;
        if (isset($request->company_name)) {
            $studentInfo->company_name = $request->company_name;
        }
        $studentInfo->student_introduction = $request->student_introduction;
        $studentInfo->student_home_tel = $request->student_home_tel;
        $studentInfo->is_sending_dm = $request->is_sending_dm;
        $studentInfo->direct_mail_flag = $request->direct_mail_flag;
        $studentInfo->lang_type = $request->lang_type;
        $studentInfo->timezone_id = $request->timezone_id;
        $studentInfo->student_comment_text = $request->student_comment_text;
        $studentInfo->in_japan_flag = $request->in_japan_flag;
        $studentInfo->country_id = $request->country_id;
        $studentInfo->city = $request->city;
        $studentInfo->is_lms_user = $request->is_lms_user;

        $studentInfo->save();  

        if($studentInfo['is_lms_user']) {
            foreach($request->lms_project_students as $lmsProject) {
                $lmsProjectInfo = LmsProjectStudent::where('project_student_id', $lmsProject['project_student_id'])->first();

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
            'message' => '学習者削除が完了しました',
        ], StatusCode::OK);
    }

    public function updatePassword(Request $request)
    {
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);          
        }
        
        $studentInfo = Student::where('student_id', $request->id)->first();
       
        if ($studentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $studentInfo->password = Hash::make($request->password);
        $studentInfo->save();  
        
        $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo(MailType::CHANGEPASSSTUDENT, $studentInfo->lang_type);

        if ($mailPattern) {
            $mailSubject = $mailPattern[0]->mail_subject;
            $mailBody = $mailPattern[0]->mail_body;
            $mailBody = str_replace("#STUDENT_NAME#", $studentInfo->student_name, $mailBody);
            $mailBody = str_replace("#STUDENT_MAIL#", $studentInfo->student_email, $mailBody);
            $mailBody = str_replace("#LOGIN#", env('APP_URL_STUDENT'), $mailBody);
            
            Mail::raw($mailBody, function ($message) use ($studentInfo, $mailSubject) {
                $message->to($studentInfo->student_email)
                    ->subject($mailSubject);
            });
        }

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function pointHistory(Request $request, $id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_point_history_list', $id],
        ]);
        $pageLimit = $this->newListLimit($request);

        $studentInfo = Student::where('student_id', $id)->firstOrFail();

        $queryBuilder = StudentPointHistory::select('student_point_history.student_point_history_id', 
            'student_point_history.point_subscription_id',
            'student_point_history.pay_date',
            'student_point_history.pay_description',
            'student_point_history.point_count',
            'student_point_history.expire_date',
            'point_subscription_history.set_course_id',
            'course.course_name'
        )
            ->leftJoin('course', function($join) {
                $join->on('student_point_history.course_id', '=', 'course.course_id');
            })
            ->leftJoin('point_subscription_history', function($join) {
                $join->on('student_point_history.point_subscription_id', '=', 'point_subscription_history.point_subscription_history_id');
            })
            ->where('student_point_history.student_id', $id);

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course_name', $request['search_input']));
            });
        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('course.course_name','ASC') : $queryBuilder->orderBy('course.course_name','DESC');
            }
            if ($request['sort'] == "set_course_id") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('point_subscription_history.set_course_id','ASC') : $queryBuilder->orderBy('point_subscription_history.set_course_id','DESC');
            }
        }
        $pointHistoryList = $queryBuilder->sortable(['pay_date' => 'desc'])->paginate($pageLimit);

        return view('student.point-history', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'pointHistoryList' => $pointHistoryList,
            'studentInfo' => $studentInfo,
        ]);
    }

    public function showPointHistory($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        
        $pointHistoryInfo = StudentPointHistory::select('student_point_history.student_point_history_id', 
            'student_point_history.student_id',
            'student_point_history.point_subscription_id',
            'student_point_history.pay_date',
            'student_point_history.expire_date',
            'student_point_history.pay_description',
            'student_point_history.point_count',
            'student_point_history.admin_note',
            'student.student_name',
            'course.course_name')
            ->leftJoin('course', function($join) {
                $join->on('student_point_history.course_id', '=', 'course.course_id');
            })
            ->leftJoin('student', function($join) {
                $join->on('student_point_history.student_id', '=', 'student.student_id');
            })
            ->where('student_point_history.student_point_history_id', $id)->firstOrFail();

        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'student_list'],
            ['name' => 'student_point_history_list', $pointHistoryInfo->student_id],
            ['name' => 'show_student_point_history', $id],
        ]);

        $pointHistoryInfo->_token = csrf_token();
       
        return view('student.show-point-history', [
            'breadcrumbs' => $breadcrumbs,
            'pointHistoryInfo' => $pointHistoryInfo,
        ]);
    }

    public function updatePointHistory(Request $request)
    {
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);          
        }
        $pointHistoryInfo = StudentPointHistory::where('student_point_history_id', $request->student_point_history_id)->first();
        if ($pointHistoryInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $pointHistoryInfo->admin_note = $request->admin_note;

        $pointHistoryInfo->save();  

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function cancelPointHistory(Request $request)
    {
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);          
        }
        
        StudentPointHistory::where('student_point_history_id', $request->student_point_history_id)->delete();

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
