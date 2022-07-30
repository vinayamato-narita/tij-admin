<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\BreadcrumbComponent;
use App\Models\LessonSchedule;
use Carbon\Carbon;
use App\Enums\LessonTiming;
use App\Enums\StatusCode;
use App\Models\FreeTeacherLessonSetting;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use App\Enums\AdminRole;
use Auth;

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
        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;

        return view('lessonStatus.index', [
            'breadcrumbs' => $breadcrumbs,
            'lessonTiming' => $lessonTiming,
            'nextLessonTime' => $nextLessonTime,
            'numRow' => $numRow,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'adminSystem' => $adminSystem,
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
        // $data['start_date'] = "20160606";

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

        $lessonStatus = DB::table('lesson_schedule')
                            ->select(
                                'lesson_schedule.lesson_starttime',
                                DB::raw('DATE_FORMAT(lesson_schedule.lesson_starttime, "%Y-%m-%d %T") as lesson_starttime1'),
                                DB::raw("COUNT(IF(teacher.is_free_teacher = 0, lesson_history.student_id, NULL)) as reverse_count_normal"),
                                DB::raw("COUNT(IF(teacher.is_free_teacher = 0, lesson_schedule.lesson_schedule_id, NULL)) as lesson_count_normal"),
                                DB::raw("COUNT(IF(teacher.is_free_teacher = 1, lesson_history.student_id, NULL)) as reverse_count_free"),
                                DB::raw("COUNT(IF(teacher.is_free_teacher = 1, lesson_schedule.lesson_schedule_id, NULL)) as lesson_count_free"),
                                'teacher.is_free_teacher',
                                'lesson_history.student_id'
                            )   
                            ->leftJoin('teacher', function($join)
                                {
                                    $join->on('teacher.teacher_id', '=', 'lesson_schedule.teacher_id');
                                })
                            ->leftJoin('lesson_history', function($join)
                                {
                                    $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id')
                                        ->where('lesson_history.student_lesson_reserve_type','<>',2);
                                })
                            ->where('lesson_schedule.lesson_type_id','=',1)
                            ->where('lesson_schedule.lesson_date', '>=' , $startDate)
                            ->where('lesson_schedule.lesson_date', '<=', $endDate)
                            ->groupBy('lesson_schedule.lesson_starttime')
                        ->get();

        $lessonStatus = $lessonStatus->keyBy('lesson_starttime1');
        
		$endTimeFreeTeacher = date("Y-m-d 23:59:59", strtotime($startDate. " + 6 days"));
        $freeTeacherLessonSetting = DB::table('free_teacher_lesson_setting')
                            ->select(
                                'free_teacher_lesson_setting.lesson_starttime',
                                DB::raw("DATE_FORMAT(free_teacher_lesson_setting.lesson_starttime, '%Y-%m-%d %T') as lesson_starttime1"),
                                'free_teacher_lesson_setting.max_free_lesson',
                                'free_teacher_lesson_setting.setting_id'
                            )   
                            ->where('lesson_starttime', '>=' , $startDate)
                            ->where('lesson_starttime', '<=', $endTimeFreeTeacher)
                        ->get();
        $freeTeacherLessonSetting = $freeTeacherLessonSetting->keyBy('lesson_starttime1');

        $lessonTiming = LessonTiming::LESSON_TIMING;
        $nextLessonTime = LessonTiming::NEXT_LESSON_TIME;
        $numRow = 24 * 60/ $nextLessonTime;
        $dataTime = [];
        $dataMaxFreeLesson = [];

        for ($i = 0; $i < $numRow ; $i++) {
            $curRowTime = date("Y-m-d H:i:s", strtotime($startDate. " +" .$i * $nextLessonTime . " minutes"));
            $dataTime[$i] = [];
            $dataTime[$i]['time'] = date("H:i",strtotime($curRowTime)) . "~". date("H:i", strtotime($curRowTime . "+ $lessonTiming minutes"));

            for ($j = 0; $j < 7 ; $j++) {
                $dataTime[$i][$j] = [];
                $curCellTime = date("Y-m-d H:i:s", strtotime($curRowTime. " + $j days"));

                if (isset($lessonStatus[$curCellTime])) {
                    $dataTime[$i][$j][] = (array) $lessonStatus[$curCellTime];
                } else {
                    $dataTime[$i][$j][] = [
                        "lesson_starttime" => $curCellTime,
                        "reverse_count_normal" => 0,
                        "lesson_count_normal" => 0,
                        "reverse_count_free" => 0, 
                        "lesson_count_free" => 0,
                        "is_free_teacher" => 1,
                        "student_id" => null
                    ];
                }

                if (isset($freeTeacherLessonSetting[$curCellTime])) {
                    $dataTime[$i][$j]['free_schedule'] = ((array) $freeTeacherLessonSetting[$curCellTime])["max_free_lesson"] - (isset($lessonStatus[$curCellTime]) ?  ((array) $lessonStatus[$curCellTime])["lesson_count_free"] : 0 ) ;
                    $dataTime[$i][$j]['max_free_lesson'] = ((array) $freeTeacherLessonSetting[$curCellTime])["max_free_lesson"];
                } else {
                    $dataTime[$i][$j]['free_schedule'] = 0;
                    $dataTime[$i][$j]['max_free_lesson'] = 0;
                }

                $dataMaxFreeLesson[$i][$j] = [];
                $dataMaxFreeLesson[$i][$j]['max_free_lesson'] = $dataTime[$i][$j]['max_free_lesson'];
                $dataMaxFreeLesson[$i][$j]['setting_id'] = 0;
                if (isset($freeTeacherLessonSetting[$curCellTime])) {
                    $dataMaxFreeLesson[$i][$j]['setting_id'] = ((array) $freeTeacherLessonSetting[$curCellTime])["setting_id"];
                }
            }

            $dataTime[$i]['reverse_count_normal_1'] = $this->sum_of_lesson_by_time($curRowTime, 1, $lessonStatus);
            $dataTime[$i]['lesson_count_normal_2'] = $this->sum_of_lesson_by_time($curRowTime, 2, $lessonStatus);
            $dataTime[$i]['reverse_count_free_3'] = $this->sum_of_lesson_by_time($curRowTime, 3, $lessonStatus);
            $dataTime[$i]['lesson_count_free_4'] = $this->sum_of_lesson_by_time($curRowTime, 4, $lessonStatus);
        }

        for ($j = 0; $j < 7 ; $j++) {
            $curCellTime = date("Y-m-d", strtotime($startDate. " + $j days"));
            $dataTime[$j]['reverse_count_normal_date_1'] = $this->sum_of_lesson_by_date($curCellTime, 1, $lessonStatus);
            $dataTime[$j]['lesson_count_normal_date_2'] = $this->sum_of_lesson_by_date($curCellTime, 2, $lessonStatus);
            $dataTime[$j]['reverse_count_free_date_3'] = $this->sum_of_lesson_by_date($curCellTime, 3, $lessonStatus);
            $dataTime[$j]['lesson_count_free_date_4'] = $this->sum_of_lesson_by_date($curCellTime, 4, $lessonStatus);
        }

        $dataTime['reverse_count_normal'] = $lessonStatus->sum('reverse_count_normal');
        $dataTime['lesson_count_normal'] = $lessonStatus->sum('lesson_count_normal');
        $dataTime['reverse_count_free'] = $lessonStatus->sum('reverse_count_free');
        $dataTime['lesson_count_free'] = $lessonStatus->sum('lesson_count_free');
        
        return response()->json([
            'status' => 'OK',
            'lessonStatus' => $lessonStatus,
            'freeTeacherLessonSetting' => $freeTeacherLessonSetting,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'date' => $date,
            'dataTime' => $dataTime,
            'dataMaxFreeLesson' => $dataMaxFreeLesson
        ], StatusCode::OK);
    }


    public function lessoninfomationdetailexportcsv(Request $request) {
        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;
        if (!$adminSystem) {
            return;
        }
        $data = $request->all();

        if (empty($data)) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }

        $startDate = empty($data["lesson_date_from"]) ? "" : date ("Y-m-d", strtotime($data["lesson_date_from"]));
        $endDate = empty($data["lesson_date_to"]) ? "" : date ("Y-m-d", strtotime($data["lesson_date_to"]));
        
        $string = DB::select("CALL sp_get_lesson_schedule_info_for_export_csv('".$startDate."','".$endDate."')");
        $fileName = 'lesson_info_detail_'. $startDate. "~". $endDate . date('_YmdHis' ).'.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = [
            $this->convertShijis('レッスン日'),
            $this->convertShijis('レッスン時間'),
            $this->convertShijis('レッスン予約時間'),
            $this->convertShijis('レッスン名'),
            $this->convertShijis('テキスト名'),
            $this->convertShijis('講師名'),
            $this->convertShijis('学習者番号'),
            $this->convertShijis('学習者ニックネーム'),
            $this->convertShijis('学習者スカイプ名'),
            $this->convertShijis('評価（学習者→講師）'),
            $this->convertShijis('評価（講師→学習者）'),
            $this->convertShijis('コメント（学習者→講師）'),
            $this->convertShijis('コメント（講師→学習者）')
        ];

        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $columns);

        foreach ($string as $item) {
            $row['レッスン日'] = $this->convertShijis($item->lesson_date);
            $row['レッスン時間'] = $this->convertShijis($item->lesson_time);
            $row['レッスン予約時間'] = $this->convertShijis($item->student_book_time);
            $row['レッスン名'] = $this->convertShijis($item->lesson_name);
            $row['テキスト名'] = $this->convertShijis($item->lesson_text_name);
            $row['講師名'] = $this->convertShijis($item->teacher_name);
            $row['学習者番号'] = $this->convertShijis($item->student_id);
            $row['学習者ニックネーム'] = $this->convertShijis($item->student_nickname);
            $row['学習者スカイプ名'] = $this->convertShijis($item->student_skype_name);
            $row['評価（学習者→講師）'] = $this->convertShijis($item->teacher_rating);
            $row['評価（講師→学習者）'] = $this->convertShijis($item->student_rating);
            $row['コメント（学習者→講師）'] = $this->convertShijis($item->comment_from_student_to_teacher);
            $row['コメント（講師→学習者）'] = $this->convertShijis($item->comment_from_teacher_to_student);

            fputcsv($file, array($row['レッスン日'],$row['レッスン時間'],$row['レッスン予約時間'],$row['レッスン名'],$row['テキスト名'],$row['講師名'],$row['学習者番号'],$row['学習者ニックネーム'],$row['学習者スカイプ名'],$row['評価（学習者→講師）'],$row['評価（講師→学習者）'],$row['コメント（学習者→講師）'],$row['コメント（講師→学習者）']));
        }

        fclose($file);
        
        return response()->json([
            'path' => url('/csv_file/users/') .'/'. $fileName,
            'file_name' => $fileName,
            'status' => StatusCode::OK
        ]);
    }

    public function lessoninfomationstatusexportcsv(Request $request) {
        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;
        if (!$adminSystem) {
            return;
        }
        set_time_limit(1200);
        $data = $request->all();

        if (empty($data)) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }

        $startDate = empty($data["lesson_date_from"]) ? "" : date ("Y-m-d", strtotime($data["lesson_date_from"]));
        $endDate = empty($data["lesson_date_to"]) ? "" : date ("Y-m-d", strtotime($data["lesson_date_to"]));

        $lessonStatus = DB::table('lesson_schedule')
                            ->select(
                                'lesson_schedule.lesson_starttime',
                                DB::raw('DATE_FORMAT(lesson_schedule.lesson_starttime, "%Y-%m-%d %T") as lesson_starttime1'),
                                DB::raw("COUNT(IF(teacher.is_free_teacher = 0, lesson_history.student_id, NULL)) as reverse_count_normal"),
                                DB::raw("COUNT(IF(teacher.is_free_teacher = 0, lesson_schedule.lesson_schedule_id, NULL)) as lesson_count_normal"),
                                DB::raw("COUNT(IF(teacher.is_free_teacher = 1, lesson_history.student_id, NULL)) as reverse_count_free"),
                                DB::raw("COUNT(IF(teacher.is_free_teacher = 1, lesson_schedule.lesson_schedule_id, NULL)) as lesson_count_free"),
                                'teacher.is_free_teacher',
                                'lesson_history.student_id'
                            )   
                            ->leftJoin('teacher', function($join)
                                {
                                    $join->on('teacher.teacher_id', '=', 'lesson_schedule.teacher_id');
                                })
                            ->leftJoin('lesson_history', function($join)
                                {
                                    $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id')
                                        ->where('lesson_history.student_lesson_reserve_type','<>',2);
                                })
                            ->where('lesson_schedule.lesson_type_id','=',1)
                            ->where('lesson_schedule.lesson_date', '>=' , $startDate)
                            ->where('lesson_schedule.lesson_date', '<=', $endDate)
                            ->groupBy('lesson_schedule.lesson_starttime')
                        ->get();

        $lessonStatus = $lessonStatus->keyBy('lesson_starttime1');
        
		$endTimeFreeTeacher = date("Y-m-d 23:59:59", strtotime($startDate. " + 6 days"));
        $freeTeacherLessonSetting = DB::table('free_teacher_lesson_setting')
                            ->select(
                                'free_teacher_lesson_setting.lesson_starttime',
                                DB::raw("DATE_FORMAT(free_teacher_lesson_setting.lesson_starttime, '%Y-%m-%d %T') as lesson_starttime1"),
                                'free_teacher_lesson_setting.max_free_lesson',
                                'free_teacher_lesson_setting.setting_id'
                            )   
                            ->where('lesson_starttime', '>=' , $startDate)
                            ->where('lesson_starttime', '<=', $endTimeFreeTeacher)
                        ->get();
        $freeTeacherLessonSetting = $freeTeacherLessonSetting->keyBy('lesson_starttime1');

        $lessonTiming = LessonTiming::LESSON_TIMING;
        $nexLessonTine = LessonTiming::NEXT_LESSON_TIME;
        $numRow = 24 * 60/ $nexLessonTine;
        $dateDefine = array(
            0 => "日", 1 => "月", 2 => "火",3 => "水", 4 => "木",5 => "金", 6 => "土"
        );
        
        $fileName = 'lesson_info_'. $startDate. "~". $endDate . date('_YmdHis' ).'.csv';
        $header1 = $header2 = $header3 = array("");
        $curColDate = $startDate;
        
        while($curColDate <= $endDate) {
            $header1[] = $this->convertShijis(date("Y/m/d", strtotime($curColDate)) . "(" . $dateDefine[date("w", strtotime($curColDate))] .")");
            $header1[] = "";
            $header1[] = "";
            $header1[] = "";
            $header1[] = "";
            $header1[] = "";
            $header2[] = $this->convertShijis("固定枠");
            $header2[] = "";
            $header2[] = $this->convertShijis("自由枠");
            $header2[] = "";
            $header2[] = "";
            $header2[] = "";
            $header3[] = $this->convertShijis("予約数");
            $header3[] = $this->convertShijis("登録数");
            $header3[] = $this->convertShijis("予約数");
            $header3[] = $this->convertShijis("登録数");
            $header3[] = $this->convertShijis("残枠");
            $header3[] = $this->convertShijis("枠数");
            $curColDate = date("Y-m-d", strtotime($curColDate. " +1 days"));
        }
        $input_array[] = $header1;
        $input_array[] = $header2;
        $input_array[] = $header3;

        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $input_array[0]);
        fputcsv($file, $input_array[1]);
        fputcsv($file, $input_array[2]);

        for ($i= 0; $i < $numRow; $i ++) {
            $row = array();
            $curRowTime = date("Y-m-d H:i:s", strtotime($startDate. " +" .$i * $nexLessonTine . " minutes"));
            $row[] = $this->convertShijis(date("H:i",strtotime($curRowTime)) . "~". date("H:i", strtotime($curRowTime . "+ $lessonTiming minutes")));

            $curCellTime = $curRowTime;
            $endTime = date ("Y-m-d 23:30:00", strtotime($endDate));
            while($curCellTime <= $endTime) {
                $row[] = !isset($lessonStatus[$curCellTime]) ? 0: $this->convertShijis($lessonStatus[$curCellTime]->reverse_count_normal);
                $row[] = !isset($lessonStatus[$curCellTime]) ? 0: $this->convertShijis($lessonStatus[$curCellTime]->lesson_count_normal);
                $row[] = !isset($lessonStatus[$curCellTime]) ? 0: $this->convertShijis($lessonStatus[$curCellTime]->reverse_count_free);
                $row[] = $freeSchedule = !isset($lessonStatus[$curCellTime]) ? 0: $this->convertShijis($lessonStatus[$curCellTime]->lesson_count_free);
                $maxFreeSchedule = !isset($freeTeacherLessonSetting[$curCellTime]) ? 0: $this->convertShijis($freeTeacherLessonSetting[$curCellTime]->max_free_lesson);
                $row[] = $this->convertShijis($maxFreeSchedule - $freeSchedule);
                $row[] = $this->convertShijis($maxFreeSchedule);
                
                $curCellTime = date("Y-m-d H:i:s", strtotime($curCellTime. " +1 days"));
            }

            fputcsv($file, $row);
        }

        // 合計
        $row = array( $this->convertShijis("合計"));
        $curColDate = $startDate;
        while($curColDate <= $endDate) {
            $row[] = $this->convertShijis($this->sum_of_lesson_by_date($curColDate, 1, $lessonStatus));
            $row[] = $this->convertShijis($this->sum_of_lesson_by_date($curColDate, 2, $lessonStatus));
            $row[] = $this->convertShijis($this->sum_of_lesson_by_date($curColDate, 3, $lessonStatus));
            $row[] = $this->convertShijis($this->sum_of_lesson_by_date($curColDate, 4, $lessonStatus));
            $row[] = "-";
            $row[] = "-";
            $curColDate = date("Y-m-d", strtotime($curColDate. " +1 days"));
        }

        fputcsv($file, $row);

        fclose($file);
        
        return response()->json([
            'path' => url('/csv_file/users/') .'/'. $fileName,
            'file_name' => $fileName,
            'status' => StatusCode::OK
        ]);
    }

    public function lesson_status_detail(Request $request) {
        $data = $request->all();

        if (empty($data['lesson_time'])) {
            return response()->json([
                'status' => 'NG',
                'message' => 'エラーが発生しました。もう一度出力してください'
            ], StatusCode::BAD_REQUEST);
        }

        $lessonStatusDetail = DB::select('CALL sp_get_lesson_schedule_info_detail_for_cakephp(?)',
            array(
                date('Y-m-d H:i:s', strtotime($data['lesson_time']))
            ));

        return response()->json([
            'lessonStatusDetail' => $lessonStatusDetail,
            'status' => StatusCode::OK
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

    public function updateLessonStatus(Request $request){
        $data = $request->all();

        if (empty($data)) {
            return response()->json([
                'status' => 'BAD_REQUEST',
            ], StatusCode::BAD_REQUEST);
        }

        $dataMaxFreeLesson = $data['data_max_free_lesson'];

        $startDate = date('Y-m-d', strtotime( 'monday this week' , strtotime($data['start_date'])));
        $endDate = date("Y-m-d", strtotime($startDate. " + 6 days"));

        $lessonTiming = LessonTiming::LESSON_TIMING;
        $nextLessonTime = LessonTiming::NEXT_LESSON_TIME;
        $numRow = 24 * 60/ $nextLessonTime;

        for ($i = 0; $i < $numRow ; $i++) {
            $curRowTime = date("Y-m-d H:i:s", strtotime($startDate. " +" .$i * $nextLessonTime . " minutes"));
            for ($j = 0; $j < 7 ; $j++) {
                $curCellTime = date("Y-m-d H:i:s", strtotime($curRowTime. " + $j days"));
                $freeTeacherLessonSetting = new FreeTeacherLessonSetting();

                if ($dataMaxFreeLesson[$i][$j]['setting_id'] != 0) {
                    $freeTeacherLessonSetting = FreeTeacherLessonSetting::where('setting_id' , $dataMaxFreeLesson[$i][$j]['setting_id'])->firstOrFail();;
                }

                $freeTeacherLessonSetting->max_free_lesson = (int) $dataMaxFreeLesson[$i][$j]['max_free_lesson'];
                $freeTeacherLessonSetting->lesson_starttime = $curCellTime;
                
                if ($dataMaxFreeLesson[$i][$j]['setting_id'] != 0) {
                    $freeTeacherLessonSetting->update();
                } else {
                    $freeTeacherLessonSetting->save();
                }
            }
        }
        
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function copySettingLessonFree(Request $request) {
        $data = $request->all();

        if (empty($data) || empty($data["start_date"])) {
            return response()->json([
                'status' => 'エラーが発生しました。再度お願いします。',
            ], StatusCode::BAD_REQUEST);
        }


        $startDate = empty($data["start_date"]) ? "" : date ("Y-m-d", strtotime($data["start_date"]));
        
        $ret = DB::select("CALL sp_copy_setting_lesson_free('".$startDate."')");

        if ($ret !== false) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::OK);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }
    }

    public function sum_of_lesson_by_time($lessonTime, $type, $data) {
        $typeKeyArr = array(
            1 => "reverse_count_normal",
            2 => "lesson_count_normal",
            3 => "reverse_count_free",
            4 => "lesson_count_free"
        );
        $return = 0;
        foreach ($data as $item) {
            if (date("H:i:s", strtotime($item->lesson_starttime)) == date ("H:i:s", strtotime($lessonTime))) {
                $return += ((array) $item)[$typeKeyArr[$type]];
            }
        }
        return $return;
    }

    public function sum_of_lesson_by_date($lessonDate, $type, $data) {
        $typeKeyArr = array(
            1 => "reverse_count_normal",
            2 => "lesson_count_normal",
            3 => "reverse_count_free",
            4 => "lesson_count_free"
        );
        $return = 0;
        foreach ($data as $item) {
            if (date("Y-m-d", strtotime($item->lesson_starttime)) == $lessonDate) {
                $return += ((array) $item)[$typeKeyArr[$type]];
            }
        }
        return $return;
    }
}
