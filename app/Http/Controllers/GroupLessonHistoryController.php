<?php


namespace App\Http\Controllers;


use App\Components\BreadcrumbComponent;
use App\Enums\CourseTypeEnum;
use App\Models\LessonSchedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\StudentPointHistory;
use App\Models\LessonHistory;
use App\Enums\StatusCode;
use Session;
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
        $queryBuilder = (new LessonSchedule())::with('teacher', 'course', 'lesson', 'studentPointHistory')->withCount('studentPointHistory', 'lessonHistories');

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

        $groupLessonHistoryList = $queryBuilder->whereHas('course', function ($q) {
            return $q->where('course_type', CourseTypeEnum::GROUP_COURSE);
        })->where('lesson_endtime' , '<', Carbon::now())->sortable(['last_update_date' => 'desc'])->paginate($pageLimit);

        return view('groupLessonHistory.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'groupLessonHistoryList' => $groupLessonHistoryList,
        ]);

    }

    public function exportGroupLesson()
    {
        $request = Session::get('exportGroupLesson');
        $fileName = "groupLesson".date("Y-m-d").".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $header = [
            $this->convertShijis("レッスンコード"),
            $this->convertShijis("レッスン日"),
            $this->convertShijis("レッスン時間"),
            $this->convertShijis("レッスン予約時間"),
            $this->convertShijis("レッスン名"),
            $this->convertShijis("テキスト名"),
            $this->convertShijis("講師コード"),
            $this->convertShijis("講師名"),
            $this->convertShijis("講師メールアドレス")
        ];

        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $header);

        $queryBuilder = LessonSchedule::select('lesson.lesson_id', 'lesson_schedule.lesson_date', 'lesson_schedule.lesson_starttime', 'lesson_schedule.lesson_endtime', 'lesson.lesson_name', 'lesson_text.lesson_text_name', 'teacher.teacher_id', 'teacher.teacher_name', 'teacher.teacher_email')
        ->leftJoin('teacher', function($join) {
            $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
        })
        ->leftJoin('course', function($join) {
            $join->on('lesson_schedule.course_id', '=', 'course.course_id');
        })
        ->leftJoin('lesson', function($join) {
            $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
        })
        ->leftJoin('lesson_text', function($join) {
            $join->on('lesson_schedule.lesson_text_id', '=', 'lesson_text.lesson_text_id');
        });

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
        $dataExport = $queryBuilder->whereHas('course', function ($q) {
            return $q->where('course_type', CourseTypeEnum::GROUP_COURSE);
        })->where('lesson_endtime' , '<', Carbon::now());

        $list = $dataExport->get()->map(function($item, $key) {
            $item['lesson_date'] = isset($item['lesson_date']) ? date('Y-m-d', strtotime($item['lesson_date'])) : "";
            $item['lesson_starttime'] = isset($item['lesson_starttime']) ? date('H:i:s', strtotime($item['lesson_starttime'])) : "";
            $item['lesson_endtime'] = isset($item['lesson_endtime']) ? date('H:i:s', strtotime($item['lesson_endtime'])) : "";

            return $item;
        });

        foreach($list as $item) {
            $input = [];
            $input["レッスンコード"] = $this->convertShijis($item['lesson_code']);
            $input["レッスン日"] = $this->convertShijis($item['lesson_date']);
            $input["レッスン時間"] = $this->convertShijis($item['lesson_starttime']);
            $input["レッスン予約時間"] = $this->convertShijis($item['lesson_endtime']);
            $input["レッスン名"] = $this->convertShijis($item['lesson_name']);
            $input["テキスト名"] = $this->convertShijis($item['lesson_text_name']);
            $input["講師コード"] = $this->convertShijis($item['teacher_id']);
            $input["講師名"] = $this->convertShijis($item['teacher_name']);
            $input["講師メールアドレス"] = $this->convertShijis($item['teacher_email']);
        }
        
        return Response::download(public_path().'/csv_file/users/'.$fileName, $fileName, $header);
    }

    public function studentAttendance(Request $request, $id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'group_lesson_history'],
            ['name' => 'student_attendance', $id]
        ]);

        $pageLimit = $this->newListLimit($request);

        $queryBuilder = Student::select('lesson_history.lesson_history_id', 'student.student_id', 'student.student_name', 'lesson_history.student_lesson_start')
            ->join('lesson_history', function($join) use ($id) {
                $join->on('student.student_id', '=', 'lesson_history.student_id')
                ->where('lesson_history.lesson_schedule_id', $id);
            })
           ->join('student_point_history', function($join) use ($id) {
                $join->on('student.student_id', '=', 'student_point_history.student_id')
                ->where('student_point_history.lesson_schedule_id', $id);
            });

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

    public function updateStudentAttendance(Request $request, $id)
    {
        try {
            $result = LessonHistory::where('lesson_history_id', $id)->firstOrFail();
            if($result->student_lesson_start == null) {
                $result->student_lesson_start = Carbon::now();
            }else {
                $result->student_lesson_start = null;
            }
            $result->save();

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