<?php
namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\CourseTypeEnum;
use App\Models\LessonSchedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GroupLessonExport;
use Session;
use App\Models\StudentPointHistory;
use App\Models\LessonHistory;
use App\Enums\StatusCode;
use App\Enums\AdminRole;
use Auth;
use Log;
use DB;
use Response;

class GroupLessonHistoryController extends BaseController
{ 
    public function index(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'group_lesson_history']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = (new LessonSchedule())::with('teacher', 'course', 'lesson', 'studentPointHistory')->whereHas('lesson')->whereHas('teacher')->whereHas('course')->withCount(
                [
                    'studentPointHistory' => function($query) {
                        $query->select(DB::raw('count(distinct(student_id))'));
                    }, 
                    'lessonHistories' => function($query) {
                        $query->whereNotNull('student_lesson_start');
                    }
                ]
            );

        if (!empty($request['search_input'])) {
            $queryBuilder = $queryBuilder->whereHas('course', function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course_name', $request['search_input']));
            })->orWhereHas('teacher', function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('teacher_name', $request['search_input']));
            })->orWhereHas('lesson', function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('lesson_name', $request['search_input']));
            });
        }

        if (!empty($request['time_from']) || !empty($request['time_to'])) {
            $from = Carbon::createFromTimestamp($request['time_from']);
            $to = Carbon::createFromTimestamp($request['time_to']);
            if (!empty($request['time_from']) && !empty($request['time_to'])) {
                $queryBuilder = $queryBuilder->whereBetween('lesson_starttime', [$from, $to]);
            }
            else {
                if (!empty($request['time_from']))
                    $queryBuilder = $queryBuilder->whereDate('lesson_starttime', '>=', $from);
                if (!empty($request['time_to']))
                    $queryBuilder = $queryBuilder->whereDate('lesson_starttime', '<=', $to);
            }
        }

        Session::put('exportGroupLesson', collect($request));

        $groupLessonHistoryList = $queryBuilder->whereHas('course', function ($q) {
            return $q->where('course_type', CourseTypeEnum::GROUP_COURSE);
        })->where('lesson_endtime' , '<', Carbon::now())->sortable(['last_update_date' => 'desc'])->paginate($pageLimit);

        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;

        return view('groupLessonHistory.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'groupLessonHistoryList' => $groupLessonHistoryList,
            'adminSystem' => $adminSystem,
        ]);

    }

    public function exportGroupLesson()
    {
        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;
        if (!$adminSystem) {
            return;
        }
        $request = Session::get('exportGroupLesson');
        $fileName = "group_lesson".date("Y-m-d").".csv";

        $header = [
            "レッスンコード",
            "レッスン日",
            "レッスン時間",
            "レッスン予約時間",
            "レッスン名",
            "テキスト名",
            "講師コード",
            "講師名",
            "講師メールアドレス"
        ];

        if (!file_exists(public_path() . '/csv_file/users')) {
            mkdir(public_path() . '/csv_file/users', 0777, true);
        }
        $localPath = public_path() . '/csv_file/users/' . $fileName;

        $queryBuilder = LessonSchedule::select('lesson.lesson_id', 'lesson_schedule.lesson_date', 'lesson_schedule.lesson_starttime', 'lesson_schedule.lesson_endtime', 'lesson.lesson_name', 'lesson_text.lesson_text_name', 'teacher.teacher_id', 'teacher.teacher_name', 'teacher.teacher_email')
        ->leftJoin('teacher', function($join) {
            $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
        })
        ->join('course', function($join) {
            $join->on('lesson_schedule.course_id', '=', 'course.course_id')
            ->where('course.course_type', CourseTypeEnum::GROUP_COURSE);
        })
        ->leftJoin('lesson', function($join) {
            $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
        })
        ->leftJoin('lesson_text', function($join) {
            $join->on('lesson_schedule.lesson_text_id', '=', 'lesson_text.lesson_text_id');
        })
        ->where('lesson_endtime' , '<', Carbon::now());

        if (!empty($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course.course_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher.teacher_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lesson.lesson_name', $request['search_input']));
            });
        }

        if (isset($request['time_from']) || isset($request['time_to'])) {
            $from = Carbon::createFromTimestamp($request['time_from']);
            $to = Carbon::createFromTimestamp($request['time_to']);
            if (!empty($request['time_from']) && !empty($request['time_to'])) {
                $queryBuilder = $queryBuilder->whereBetween('lesson_schedule.lesson_starttime', [$from, $to]);
            }
            else {
                if (!empty($request['time_from']))
                    $queryBuilder = $queryBuilder->whereDate('lesson_schedule.lesson_starttime', '>=', $from);
                if (!empty($request['time_to']))
                    $queryBuilder = $queryBuilder->whereDate('lesson_schedule.lesson_starttime', '<=', $to);
            }
        }

        $dataExport = $queryBuilder->get()->map(function($item, $key) {
            $item['lesson_date'] = isset($item['lesson_starttime']) ? date('Y-m-d', strtotime($item['lesson_starttime'])) : "";
            $item['lesson_starttime'] = isset($item['lesson_starttime']) ? date('H:i:s', strtotime($item['lesson_starttime'])) : "";
            $item['lesson_endtime'] = isset($item['lesson_endtime']) ? date('H:i:s', strtotime($item['lesson_endtime'])) : "";
            return $item;
        })->toArray();

        $this->writecsv($dataExport, $header, $fileName, $localPath);

        return Response::download($localPath, $fileName);
    }

    public function studentAttendance(Request $request, $id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'group_lesson_history'],
            ['name' => 'student_attendance', $id]
        ]);

        $pageLimit = $this->newListLimit($request);

        $queryBuilder = Student::select('lesson_history.lesson_history_id', 'student.student_id', 'student.student_name', 'lesson_history.student_lesson_start', 'student_point_history.lesson_schedule_id')
            ->join('student_point_history', function($join) use ($id) {
                $join->on('student.student_id', '=', 'student_point_history.student_id')
                ->where('student_point_history.lesson_schedule_id', $id);
            })
            ->leftJoin('lesson_history', function($join) use ($id) {
                $join->on('student.student_id', '=', 'lesson_history.student_id')
                ->where('lesson_history.lesson_schedule_id', $id);
            })
            ->groupBy('student.student_id');

        if (!empty($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student.student_id', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_name', $request['search_input']));
            });
        }

        $studentList = $queryBuilder->sortable(['student_id' => 'asc'])->paginate($pageLimit);

        return view('groupLessonHistory.student_attendance', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'studentList' => $studentList,
            'id' => $id,
        ]);
    }

    public function updateStudentAttendance(Request $request)
    {
        try {
            $lesson_history_id = $request->lesson_history_id;
            if ($lesson_history_id != null) {
                $result = LessonHistory::where('lesson_history_id', $lesson_history_id)->first();
                if($result->student_lesson_start == null) {
                    $result->student_lesson_start = Carbon::now();
                }else {
                    $result->student_lesson_start = null;
                }
                $result->save();
            }else {
                $lesson_history = new LessonHistory;
                $lesson_history->lesson_schedule_id = $request->lesson_schedule_id;
                $lesson_history->student_id = $request->student_id;
                $lesson_history->note_from_student_to_teacher = '';
                $lesson_history->student_lesson_reserve_type = 2;
                $lesson_history->reserve_date = Carbon::now();
                $lesson_history->student_lesson_start = Carbon::now();
                $lesson_history->save();  
            }

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
