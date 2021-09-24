<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\BreadcrumbComponent;
use App\Models\LessonSchedule;
use Carbon\Carbon;
use App\Enums\LessonTiming;
use App\Enums\StatusCode;
use Illuminate\Support\Facades\DB;

class LessonStatusController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'lesson_status_index']
        ]);

        $startDate = time();
        //if is sunday, get monday prev week
        if (date('w', $startDate) == 0) {
            $startDate = strtotime('-1 week', $startDate);
        }
        //get monday of this week
        $startDate = date('Y-m-d', strtotime( 'monday this week' , $startDate));
        $endDate = date("Y-m-d", strtotime($startDate. " + 6 days"));

        $lessonTiming = LessonTiming::LESSON_TIMING;
        $nextLessonTime = LessonTiming::NEXT_LESSON_TIME;
        $numRow = 24 * 60/ $nextLessonTime;
        $dataTime = [];

        for ($i = 0; $i < $numRow ; $i++) {
            $curRowTime = date("Y-m-d H:i:s", strtotime($startDate. " +" .$i * $nextLessonTime . " minutes"));
            $dataTime[] = date("H:i",strtotime($curRowTime)) . "~". date("H:i", strtotime($curRowTime . "+ $lessonTiming minutes"));
        }

        return view('lessonStatus.index', [
            'breadcrumbs' => $breadcrumbs,
            'lessonTiming' => $lessonTiming,
            'nextLessonTime' => $nextLessonTime,
            'numRow' => $numRow,
            'dataTime' => $dataTime
        ]);
    }


    public function getData(Request $request) {
        $data = $request->all();
        if (empty($data['start_date'])) {
            return response()->json([
                'status' => 'NG',
                'message' => 'エラーが発生しました。もう一度出力してください'
            ], StatusCode::BAD_REQUEST);
        }

        $startDate = date('Y-m-d', strtotime( 'monday this week' , strtotime($data['start_date'])));
        $endDate = date("Y-m-d", strtotime($startDate. " + 6 days"));

        $date = [];

        $dateDefine = array(
            0 => "日", 1 => "月", 2 => "火",3 => "水", 4 => "木",5 => "金", 6 => "土"
        );

        for ($i = 0; $i < 7; $i++) {
            $colDate = date("Y/m/d", strtotime($startDate. " +$i days"));
            $date[] = $colDate ."(" . $dateDefine[date("w", strtotime($colDate))] .")";
        }

        $lessonStatus = DB::table('lesson_schedules')
                            ->select(
                                'lesson_schedules.lesson_starttime',
                                DB::raw('DATE_FORMAT(lesson_starttime, "%Y-%m-%d %T") as lesson_starttime1'),
                                DB::raw("COUNT(IF(teachers.is_free_teacher = 0, lesson_histories.student_id, NULL)) as reverse_count_normal"),
                                DB::raw("COUNT(IF(teachers.is_free_teacher = 0, lesson_schedules.lesson_schedule_id, NULL)) as lesson_count_normal"),
                                DB::raw("COUNT(IF(teachers.is_free_teacher = 1, lesson_histories.student_id, NULL)) as reverse_count_free"),
                                DB::raw("COUNT(IF(teachers.is_free_teacher = 1, lesson_schedules.lesson_schedule_id, NULL)) as lesson_count_free"),
                                'teachers.is_free_teacher',
                                'lesson_histories.student_id'
                            )   
                            ->leftJoin('teachers', function($join)
                                {
                                    $join->on('teachers.id', '=', 'lesson_schedules.teacher_id');
                                })
                            ->leftJoin('lesson_histories', function($join)
                                {
                                    $join->on('lesson_histories.lesson_schedule_id', '=', 'lesson_schedules.lesson_schedule_id')
                                        ->where('lesson_histories.student_lesson_reserve_type','<>',2);
                                })
                            ->where('lesson_type_id','=',1)
                            ->where('lesson_date', '>=' , $startDate)
                            ->where('lesson_date', '<=', $endDate)
                            ->groupBy('lesson_schedules.lesson_starttime','teachers.is_free_teacher','lesson_histories.student_id')
                        ->get();
        $lessonStatus = $lessonStatus->keyBy('lesson_starttime1');
        
		$endTimeFreeTeacher = date("Y-m-d 23:59:59", strtotime($startDate. " + 6 days"));
        $freeTeacherLessonSetting = DB::table('free_teacher_lesson_settings')
                            ->select(
                                'free_teacher_lesson_settings.lesson_starttime',
                                DB::raw("DATE_FORMAT(free_teacher_lesson_settings.lesson_starttime, '%Y-%m-%d %T') as lesson_starttime1"),
                                'free_teacher_lesson_settings.max_free_lesson',
                                'free_teacher_lesson_settings.setting_id'
                            )   
                            ->where('lesson_starttime', '>=' , $startDate)
                            ->where('lesson_starttime', '<=', $endTimeFreeTeacher)
                        ->get();
        $freeTeacherLessonSetting = $freeTeacherLessonSetting->keyBy('lesson_starttime1');

        

        return response()->json([
            'status' => 'OK',
            'lessonStatus' => $lessonStatus,
            'freeTeacherLessonSetting' => $freeTeacherLessonSetting,
            'startDate' => $startDate,
            'date' => $date
        ], StatusCode::OK);
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
}
