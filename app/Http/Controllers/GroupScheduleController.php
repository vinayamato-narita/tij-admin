<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\BreadcrumbComponent;
use App\Enums\AutoRecording;
use App\Enums\Boolean;
use App\Enums\StatusCode;
use App\Enums\LangType;
use App\Models\Teacher;
use App\Models\Lesson;
use App\Models\LessonText;
use App\Models\LessonSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Enums\LessonTiming;
use App\Enums\CourseTypeEnum;
use App\Models\Course;
use App\Models\ZoomAccount;
use App\Models\ZoomSchedule;
use App\Models\ZoomSetting;
use App\Services\ZoomClientService;
use Illuminate\Support\Str;
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
                $join->on('course_info.course_id', '=', 'course.course_id')
                ->where('course_info.lang_type', LangType::EN);
            })
            ->leftJoin('course_lesson', 'course_lesson.course_id', '=', 'course.course_id')
            ->leftJoin('lesson', 'lesson.lesson_id', '=', 'course_lesson.lesson_id')
            ->leftJoin('lesson_info', function ($join) {
                $join->on('lesson_info.lesson_id', '=', 'lesson.lesson_id')
                ->where('lesson_info.lang_type', LangType::EN);
            })
            ->leftJoin('teacher_lesson', 'teacher_lesson.lesson_id', '=', 'lesson.lesson_id')
            ->leftJoin('teacher', 'teacher.teacher_id', '=', 'teacher_lesson.teacher_id')
            ->where('course.course_type', '=', CourseTypeEnum::GROUP_COURSE)
            ->where(function ($query) use ($time) {
                $query->whereNull('course.course_start_date')
                    ->orWhere(function ($query) use ($time) {
                        $query->where('course.course_start_date', '<=', $time)
                            ->where('course.publish_date_to', '>=', $time);
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
                            lesson_schedule.zoom_url,
                            lesson_schedule.link_zoom_schedule_flag,
                            lesson_schedule.zoom_schedule_id,
                            course.course_id,
                            coalesce(course_info.course_name, course.course_name) as title,
                            lesson.lesson_id,
                            coalesce(lesson_info.lesson_name, lesson.lesson_name) as lesson_name,
                            CONCAT("color-", MOD(course.course_id, 11)) as class,
                            false as deletable,
                            false as resizable,
                            false as draggable,
                            zoom_schedule.zoom_account_id as zoom_account_id,
                            zoom_schedule.join_before_host as join_before_host,
                            zoom_schedule.waiting_room as waiting_room,
                            zoom_schedule.auto_recording as auto_recording
                            ')
            ->leftJoin('lesson', 'lesson.lesson_id', '=', 'lesson_schedule.lesson_id')
            ->leftJoin('lesson_info', function ($join) {
                $join->on('lesson_info.lesson_id', '=', 'lesson.lesson_id')
                ->where('lesson_info.lang_type', LangType::EN);
            })
            ->leftJoin('course_lesson', 'course_lesson.lesson_id', '=', 'lesson_schedule.lesson_id')
            ->leftJoin('course', 'course.course_id', '=', 'course_lesson.course_id')
            ->leftJoin('course_info', function ($join) {
                $join->on('course_info.course_id', '=', 'course.course_id')
                ->where('course_info.lang_type', LangType::EN);
            })
            ->leftJoin('zoom_schedule', 'zoom_schedule.zoom_schedule_id', '=', 'lesson_schedule.zoom_schedule_id')
            ->where('course.course_type', '=', CourseTypeEnum::GROUP_COURSE)
            ->where('lesson_schedule.lesson_starttime', '>=', $startDate)
            ->where('lesson_schedule.lesson_starttime', '<=', $endDate)
            ->get()->toArray();

        // Log::info($scheduleList);
        echo json_encode(array(
            'status' => 200,
            'error_message' => '',
            'data' => $scheduleList,
        ));
        return;
    }

    public function registerSchedule(Request $request, ZoomClientService $zoomClientService)
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
                            ->where('course.publish_date_to', '>=', $startDateTime);
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
                'error_message' => __('有効な講師が存在しない為、登録できません。')
            ));
            return;
        }

        $editFlag = false;
        if (!empty($request['selectedEvent']) && !empty($request['selectedEvent']['lesson_schedule_id'])) {
            $editFlag = true;
        }

        $duplicateCheck = DB::table('lesson_schedule')
            ->where('lesson_schedule.teacher_id', '=', $request['selectedTeacher'])
            ->where(function($query) {
                $query->where(function($q) {
                    $q->where('lesson_schedule.lesson_starttime', '>', $startDateTime)
                      ->where('lesson_schedule.lesson_starttime', '<=', $endDateTime);
                })
                ->orWhere(function($q) {
                    $q->where('lesson_schedule.lesson_endtime', '>=', $startDateTime)
                      ->where('lesson_schedule.lesson_endtime', '<', $endDateTime);
                })
                ->orWhere(function($q) {
                    $q->where('lesson_schedule.lesson_starttime', '<=', $startDateTime)
                      ->where('lesson_schedule.lesson_endtime', '>=', $endDateTime);
                });
            });
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

        if (!empty($request['selectedEvent']) && !empty($request['selectedEvent']['zoom_schedule_id'])) {
            ZoomSchedule::where('zoom_schedule_id', $request['selectedEvent']['zoom_schedule_id'])->delete();
        }

        if ($request->linkZoomScheduleFlag) {
            $zoomAccounts = ZoomAccount::whereDoesntHave('zoomSchedules', function ($query) use ($startDateTime, $endDateTime) {
                $query->where(function ($query) use ($startDateTime) {
                    $query->where('start_time', '<=', $startDateTime)
                        ->whereRaw('start_time + interval duration minute >= ?', [$startDateTime]);
                })->orWhere(function ($query) use ($endDateTime) {
                    $query->where('start_time', '<=', $endDateTime)
                        ->whereRaw('start_time + interval duration minute >= ?', [$endDateTime]);
                })->orWhere(function ($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_time', '<', $startDateTime)
                        ->whereRaw('start_time + interval duration minute > ?', [$endDateTime]);
                });
            })->get();
            if (!empty($request->zoomAccountId)) {
                if (!in_array($request->zoomAccountId, $zoomAccounts->pluck('zoom_account_id')->toArray())) {
                    echo json_encode(array(
                        'status' => 400,
                        'error_message' => __('選択されたZoomアカウントにすでにスケジュールが登録されている。')
                    ));
                    return;
                }
                $zoomAccountId = $request->zoomAccountId;
            } else {
                if (empty($zoomAccounts)) {
                    echo json_encode(array(
                        'status' => 400,
                        'error_message' => __('全てZoomアカウントが利用される為、スケジュール登録できません。')
                    ));
                    return;
                }
                $zoomAccountId = $zoomAccounts[0]['zoom_account_id'];
            }

            $diff = abs(strtotime($endDateTime) - strtotime($startDateTime));
            $course = Course::find($request['selectedCourse']);
            $lesson = Lesson::find($request['selectedLesson']);

            $object = [
                'topic' => $course->course_name . $lesson->lesson_name,
                'type => 2',
                'start_time' => $startDateTime,
                'duration' => $diff / 60,
                'timezone' => 'Asia/Tokyo',
                'password' => Str::random(8),
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => $request->joinBeforeHost == Boolean::TRUE ? true : false,
                    'jbh_time' => 5,
                    'use_pmi' => false,
                    'auto_recording' => AutoRecording::getDescription($request->autoRecording),
                    'waiting_room' => $request->waitingRoom == Boolean::TRUE ? true : false
                ]
            ];

            $zoomAccount = ZoomAccount::where('zoom_account_id', $zoomAccountId)->first();
            $token = $zoomClientService->getZoomAccessToken($zoomAccount->api_key, $zoomAccount->api_secret);
            $dataZoomMeeting = $zoomClientService->createZoomMeeting($token, $zoomAccount->zoom_user_id, $object)->json();

            $zoomSchedule = new ZoomSchedule();
            $zoomSchedule->zoom_account_id = $zoomAccountId;
            $zoomSchedule->zoom_meeting_id = $dataZoomMeeting['id'];
            $zoomSchedule->meeting_type = $dataZoomMeeting['type'];
            $zoomSchedule->start_time = $dataZoomMeeting['start_time'];
            $zoomSchedule->duration = $dataZoomMeeting['duration'];
            $zoomSchedule->time_zone = $dataZoomMeeting['timezone'];
            $zoomSchedule->join_before_host = $request->joinBeforeHost;
            $zoomSchedule->auto_recording = $request->autoRecording;
            $zoomSchedule->waiting_room = $request->waitingRoom;
            $zoomSchedule->zoom_url = $dataZoomMeeting['start_url'];
            $zoomSchedule->password = $dataZoomMeeting['password'];
            $zoomSchedule->save();
            $zoomUrl = $dataZoomMeeting['start_url'];
        } else {
            $zoomUrl = $request->zoomUrl;
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
                    'last_update_date' => Carbon::now(),
                    'zoom_url' => $zoomUrl,
                    'link_zoom_schedule_flag' => $request->linkZoomScheduleFlag,
                    'zoom_schedule_id' => $request->linkZoomScheduleFlag ? $zoomSchedule->zoom_schedule_id : null
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
                'last_update_date' => Carbon::now(),
                'zoom_url' => $zoomUrl,
                'link_zoom_schedule_flag' => $request->linkZoomScheduleFlag,
                'zoom_schedule_id' => $request->linkZoomScheduleFlag ? $zoomSchedule->zoom_schedule_id : null
            ]);
        }

        echo json_encode(array(
            'status' => 200,
            'error_message' => ''
        ));
        return;
    }

    public function getZoom()
    {
        $zoomAccountList = ZoomAccount::get()->toArray();
        $zoomSetting = ZoomSetting::first();
        echo json_encode(array(
            'status' => 200,
            'error_message' => '',
            'zoomAccountList' => $zoomAccountList,
            'zoomSetting' => $zoomSetting,
        ));
        return;
    }
}
