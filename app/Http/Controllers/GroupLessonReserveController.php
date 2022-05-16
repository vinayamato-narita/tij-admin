<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\CourseTypeEnum;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupLessonReserveController extends BaseController
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
            ['name' => 'group_lesson_reserve']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = Course::where('course_type', CourseTypeEnum::GROUP_COURSE);


        if (!empty($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course_name', $request['search_input']));
            });
        }

        if (!empty($request['decide_date_start'])) {
            $queryBuilder = $queryBuilder->whereDate('decide_date', '>=', Carbon::createFromTimestamp($request['decide_date_start']));
        }

        if (!empty($request['decide_date_end'])) {
            $queryBuilder = $queryBuilder->whereDate('decide_date', '<', Carbon::createFromTimestamp($request['decide_date_end']));
        }

        if (!empty($request['reserve_end_date_start'])) {
            $queryBuilder = $queryBuilder->whereDate('reserve_end_date', '>=', Carbon::createFromTimestamp($request['reserve_end_date_start']));
        }

        if (!empty($request['reserve_end_date_end'])) {
            $queryBuilder = $queryBuilder->whereDate('reserve_end_date', '<', Carbon::createFromTimestamp($request['reserve_end_date_end']));
        }

        $courseList = $queryBuilder->with(['childCourse', 'lessonSchedules'])
            ->withCount(['pointSubscriptionHistories' => function ($query) {
                $query->select(DB::raw('count(distinct(student_id))'));
            }])
            ->sortable(['course_name' => 'desc'])
            ->paginate($pageLimit);

        return view('groupLessonReserve.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'courseList' => $courseList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'group_lesson_reserve'],
            ['name' => 'group_lesson_reserve_show', $id]
        ]);
        $course = Course::where('course_id', $id)
            ->with(['childCourse', 'lessonSchedules'])
            ->withCount(['pointSubscriptionHistories' => function ($query) {
                $query->select(DB::raw('count(distinct(student_id))'));
            }])
            ->first();
        $course['group_lesson'] = $course->group_lesson;

        return view('groupLessonReserve.show', [
            'breadcrumbs' => $breadcrumbs,
            'course' => $course,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getStudent($id, Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'group_lesson_reserve'],
            ['name' => 'group_lesson_reserve_show', $id],
            ['name' => 'group_lesson_student_list', $id]
        ]);

        $pageLimit = $this->newListLimit($request);
        $queryBuilder = Student::whereHas('pointSubscriptionHistories',  function ($query) use ($request, $id) {
            if (!empty($request['reserve_end_date_start'])) {
                $query->whereDate('payment_date', '>=', Carbon::createFromTimestamp($request['reserve_end_date_start']));
            }
            if (!empty($request['reserve_end_date_end'])) {
                $query->whereDate('receive_payment_date', '<=', Carbon::createFromTimestamp($request['reserve_end_date_end']));
            }
            $query->whereHas('course', function ($query) use ($id) {
                $query->where('course_id', $id);
                $query->where('course_type', CourseTypeEnum::GROUP_COURSE);
            });
        });

        if (!empty($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student_id', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student_name', $request['search_input']));
            });
        }

        $studentList = $queryBuilder->with('pointSubscriptionHistories', function ($query) use ($request, $id) {
            if (!empty($request['reserve_end_date_start'])) {
                $query->whereDate('payment_date', '>=', Carbon::createFromTimestamp($request['reserve_end_date_start']));
            }
            if (!empty($request['reserve_end_date_end'])) {
                $query->whereDate('receive_payment_date', '<=', Carbon::createFromTimestamp($request['reserve_end_date_end']));
            }
            $query->whereHas('course', function ($query) use ($id) {
                $query->where('course_id', $id);
                $query->where('course_type', CourseTypeEnum::GROUP_COURSE);
            });
        })->sortable(['student_id' => 'desc'])->paginate($pageLimit);

        return view('groupLessonReserve.studentList', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'studentList' => $studentList,
            'course_id' => $id,
        ]);
    }
}
