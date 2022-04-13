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
                    'studentPointHistory', 
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

        return Excel::download(new GroupLessonExport($request), $fileName);
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
