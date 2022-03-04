<?php


namespace App\Http\Controllers;


use App\Components\BreadcrumbComponent;
use App\Enums\CourseTypeEnum;
use App\Models\LessonSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

}
