<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Models\Teacher;
use App\Models\Lesson;
use App\Models\LessonText;
use App\Models\LessonSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Enums\LessonTiming;
use App\Enums\CourseTypeEnum;
use Log;

class GroupScheduleController extends BaseController
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
            ['name' => 'group_schedule_index']
        ]);

        return view('groupSchedule.index', [
            'breadcrumbs' => $breadcrumbs,
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
        //
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

    public function getData(Request $request)
    {
        try {
            $time = Carbon::parse($request['time']);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            echo json_encode(array(
                'status' => 400,
                'error_message' => __('エラーが発生しました。再度お願いします。')
            ));
            return;
        }
        // Log::info($request);
        // Log::info($time);

        // get course and lesson list of teacher
        $courseList = DB::table('course')
                        ->selectRaw('course.course_id,
                            coalesce(course_info.course_name, course.course_name) as course_name,
                            lesson.lesson_id,
                            coalesce(lesson_info.lesson_name, lesson.lesson_name) as lesson_name,
                            teacher.teacher_id,
                            teacher.teacher_name')
                        ->leftJoin('course_info', function ($join) {
                            $join->on('course_info.course_id', '=', 'course.course_id');
                        })
                        ->leftJoin('course_lesson', 'course_lesson.course_id', '=', 'course.course_id')
                        ->leftJoin('lesson', 'lesson.lesson_id', '=', 'course_lesson.lesson_id')
                        ->leftJoin('lesson_info', function ($join) {
                            $join->on('lesson_info.lesson_id', '=', 'lesson.lesson_id');
                        })
                        ->leftJoin('teacher_lesson', 'teacher_lesson.lesson_id', '=', 'lesson.lesson_id')
                        ->leftJoin('teacher', 'teacher.teacher_id', '=', 'teacher_lesson.teacher_id')
                        ->where('course.course_type', '=', CourseTypeEnum::GROUP_COURSE)
                        ->where(function ($query) use ($time) {
                            $query->whereNull('course.course_start_date')
                                 ->orWhere(function ($query) use ($time) {
                                    $query->where('course.course_start_date', '<=', $time)
                                    ->whereRaw('course.course_start_date + interval expire_day day >= ?', [$time]);
                                });
                        })
                        ->orderBy('course.display_order', 'ASC')
                        ->orderBy('course_name', 'ASC')
                        ->get()->toArray();

        $beautyList = [];
        foreach ($courseList as $course) {
            if (empty($beautyList[$course->course_id])) {
                $beautyList[$course->course_id] = [
                    'course_id' => $course->course_id,
                    'course_name' => $course->course_name,
                    'lesson' => []
                ];
                if ($course->lesson_id) {
                    $beautyList[$course->course_id]['lesson'][$course->lesson_id] = [
                        'lesson_id' => $course->lesson_id,
                        'lesson_name' => $course->lesson_name,
                    ];
                    $beautyList[$course->course_id]['lesson'][$course->lesson_id]['teacher'][$course->teacher_id] = [
                        'teacher_id' => $course->teacher_id,
                        'teacher_name' => $course->teacher_name,
                    ];
                }
            } else {
                if (empty($beautyList[$course->course_id]['lesson'][$course->lesson_id]) && $course->lesson_id) {
                    $beautyList[$course->course_id]['lesson'][$course->lesson_id] = [
                        'lesson_id' => $course->lesson_id,
                        'lesson_name' => $course->lesson_name,
                        'teacher' => []
                    ];
                    if ($course->teacher_id) {
                        $beautyList[$course->course_id]['lesson'][$course->lesson_id]['teacher'][$course->teacher_id] = [
                            'teacher_id' => $course->teacher_id,
                            'teacher_name' => $course->teacher_name,
                        ];
                    }
                } elseif (!empty($beautyList[$course->course_id]['lesson'][$course->lesson_id]) && $course->lesson_id && $course->teacher_id) {
                    $beautyList[$course->course_id]['lesson'][$course->lesson_id]['teacher'][$course->teacher_id] = [
                        'teacher_id' => $course->teacher_id,
                        'teacher_name' => $course->teacher_name,
                    ];
                }

            }
        }

        echo json_encode(array(
            'status' => 200,
            'error_message' => '',
            'data' => $beautyList
        ));
        return;
    }

    public function getSchedule(Request $request)
    {
        try {
            $startDate = Carbon::parse($request['startDate'])->setTimezone('Asia/Tokyo');
            $endDate = Carbon::parse($request['endDate'])->setTimezone('Asia/Tokyo');
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            echo json_encode(array(
                'status' => 400,
                'error_message' => __('エラーが発生しました。再度お願いします。')
            ));
            return;
        }
        // Log::info($request);
        // Log::info($startDate);
        // Log::info($endDate);

        $scheduleList = DB::table('lesson_schedule')
                        ->selectRaw('
                            lesson_schedule.lesson_schedule_id,
                            lesson_schedule.lesson_starttime as start,
                            lesson_schedule.lesson_endtime as end,
                            lesson_schedule.teacher_id,
                            course.course_id,
                            coalesce(course_info.course_name, course.course_name) as title,
                            lesson.lesson_id,
                            coalesce(lesson_info.lesson_name, lesson.lesson_name) as lesson_name,
                            CONCAT("color-", MOD(course.course_id, 11)) as class,
                            false as deletable,
                            false as resizable,
                            false as draggable
                            ')
                        ->leftJoin('lesson', 'lesson.lesson_id', '=', 'lesson_schedule.lesson_id')
                        ->leftJoin('lesson_info', 'lesson_info.lesson_id', '=', 'lesson.lesson_id')
                        ->leftJoin('course_lesson', 'course_lesson.lesson_id', '=', 'lesson_schedule.lesson_id')
                        ->leftJoin('course', 'course.course_id', '=', 'course_lesson.course_id')
                        ->leftJoin('course_info', 'course_info.course_id', '=', 'course.course_id')
                        ->where('course.course_type', '=', CourseTypeEnum::GROUP_COURSE)
                        ->where('lesson_schedule.lesson_starttime', '>=', $startDate)
                        ->where('lesson_schedule.lesson_starttime', '<=', $endDate)
                        ->get()->toArray();

        // Log::info($scheduleList);
        echo json_encode(array(
            'status' => 200,
            'error_message' => '',
            'data' => $scheduleList
        ));
        return;
    }

    public function registerSchedule(Request $request)
    {
        try {
            $startDateTime = Carbon::parse($request['startDateTime']);
            $endDateTime = Carbon::parse($request['endDateTime']);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            echo json_encode(array(
                'status' => 400,
                'error_message' => __('エラーが発生しました。再度お願いします。')
            ));
            return;
        }

        $courseCheck = DB::table('course')
                        ->where('course.course_id', '=', $request['selectedCourse'])
                        ->where('course.course_type', '=', CourseTypeEnum::GROUP_COURSE)
                        ->where(function ($query) use ($startDateTime) {
                            $query->whereNull('course.course_start_date')
                                  ->orWhere(function ($query) use ($startDateTime) {
                                    $query->where('course.course_start_date', '<=', $startDateTime)
                                    ->whereRaw('course.course_start_date + interval expire_day day >= ?', [$startDateTime]);
                                });
                        })
                        ->get()->toArray();
        if (empty($courseCheck)) {
            echo json_encode(array(
                'status' => 400,
                'error_message' => __('指定されたコースが存在しないか、有効期間外のため登録できません。')
            ));
            return;
        }

        $lessonCheck = DB::table('course_lesson')
                        ->leftJoin('lesson', 'lesson.lesson_id', '=', 'course_lesson.lesson_id')
                        ->leftJoin('course', 'course.course_id', '=', 'course_lesson.course_id')
                        ->where('course.course_id', '=', $request['selectedCourse'])
                        ->where('lesson.lesson_id', '=', $request['selectedLesson'])
                        ->get()->toArray();
        if (empty($lessonCheck)) {
            echo json_encode(array(
                'status' => 400,
                'error_message' => __('有効なレッスンが存在しない為、登録できません。')
            ));
            return;
        }

        $teacherCheck = DB::table('teacher_lesson')
                        ->leftJoin('lesson', 'lesson.lesson_id', '=', 'teacher_lesson.lesson_id')
                        ->leftJoin('teacher', 'teacher.teacher_id', '=', 'teacher_lesson.teacher_id')
                        ->where('teacher.teacher_id', '=', $request['selectedTeacher'])
                        ->where('lesson.lesson_id', '=', $request['selectedLesson'])
                        ->get()->toArray();
        if (empty($teacherCheck)) {
            echo json_encode(array(
                'status' => 400,
                'error_message' => __('有効な教師が存在しない為、登録できません。')
            ));
            return;
        }

        $editFlag = false;
        if (!empty($request['selectedEvent']) && !empty($request['selectedEvent']['lesson_schedule_id'])) {
            $editFlag = true;
        }

        $duplicateCheck = DB::table('lesson_schedule')
                        ->where('lesson_schedule.teacher_id', '=', $request['selectedTeacher'])
                        ->where('lesson_schedule.lesson_starttime', '=', $startDateTime)
                        ->where('lesson_schedule.lesson_endtime', '=', $endDateTime);
        if ($editFlag) {
            $duplicateCheck->where('lesson_schedule.lesson_schedule_id', '!=', $request['selectedEvent']['lesson_schedule_id']);
        }
        $duplicateCheck = $duplicateCheck->get()->toArray();
        if (!empty($duplicateCheck)) {
            echo json_encode(array(
                'status' => 400,
                'error_message' => __('既に他のスケジュールが登録されているため、登録できません。')
            ));
            return;
        }

        $scheduleCheck = DB::table('lesson_schedule')
                        ->where('lesson_schedule.course_id', '=', $request['selectedCourse'])
                        ->where('lesson_schedule.lesson_id', '=', $request['selectedLesson']);
        if ($editFlag) {
            $scheduleCheck->where('lesson_schedule.lesson_schedule_id', '!=', $request['selectedEvent']['lesson_schedule_id']);
        }
        $scheduleCheck = $scheduleCheck->get()->toArray();
        if (!empty($scheduleCheck)) {
            echo json_encode(array(
                'status' => 400,
                'error_message' => __('こちらレッスンのスケジュールが既に登録されているため、登録できません。')
            ));
            return;
        }

        // update schedule
        if ($editFlag) {
            // check input
            $schedule = DB::table('lesson_schedule')
                        ->where('lesson_schedule.lesson_schedule_id', '=', $request['selectedEvent']['lesson_schedule_id'])
                        ->where('lesson_schedule.course_id', '=', $request['selectedCourse'])
                        ->where('lesson_schedule.lesson_id', '=', $request['selectedLesson'])
                        ->where('lesson_schedule.teacher_id', '=', $request['selectedTeacher'])
                        ->get()->toArray();
            if (empty($schedule)) {
                echo json_encode(array(
                    'status' => 400,
                    'error_message' => __('エラーが発生しました。再度お願いします。')
                ));
                return;
            }

        DB::table('lesson_schedule')
            ->where('lesson_schedule_id', $request['selectedEvent']['lesson_schedule_id'])
            ->update([
                'course_id' => $request['selectedCourse'],
                'lesson_id' => $request['selectedLesson'],
                'teacher_id' => $request['selectedTeacher'],
                'lesson_starttime' => $startDateTime,
                'lesson_endtime' => $endDateTime,
                'course_type' => CourseTypeEnum::GROUP_COURSE,
                'last_update_date' => Carbon::now()
            ]);
        } else {
        // create schedule
            $result = DB::table('lesson_schedule')->insert([
                'course_id' => $request['selectedCourse'],
                'lesson_id' => $request['selectedLesson'],
                'teacher_id' => $request['selectedTeacher'],
                'lesson_date' => $startDateTime,
                'lesson_starttime' => $startDateTime,
                'lesson_endtime' => $endDateTime,
                'course_type' => CourseTypeEnum::GROUP_COURSE,
                'last_update_date' => Carbon::now()
            ]);
        }

        echo json_encode(array(
            'status' => 200,
            'error_message' => ''
        ));
        return;
    }
}
