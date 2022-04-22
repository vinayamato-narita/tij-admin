<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\BreadcrumbComponent;
use App\Enums\CourseTypeEnum;
use App\Enums\StatusCode;
use App\Models\Teacher;
use App\Models\Lesson;
use App\Models\LessonText;
use Illuminate\Support\Facades\DB;
use App\Enums\LessonTiming;
use App\Models\LessonSchedule;

class LessonScheduleController extends BaseController
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
            ['name' => 'lesson_schedule_index']
        ]);

        $lessonStart = date('Ymd');

        $header = array(
            date('M d (D)', strtotime($lessonStart)),
            date('M d (D)', strtotime($lessonStart. ' +1 day')),
            date('M d (D)', strtotime($lessonStart. ' +2 day')),
            date('M d (D)', strtotime($lessonStart. ' +3 day')),
            date('M d (D)', strtotime($lessonStart. ' +4 day')),
            date('M d (D)', strtotime($lessonStart. ' +5 day')),
            date('M d (D)', strtotime($lessonStart. ' +6 day')),
        );
        
        $lessonTiming = LessonTiming::LESSON_TIMING;
        $nextLessonTime = LessonTiming::NEXT_LESSON_TIME;
        $numRow = 24 * 60/ $nextLessonTime;
        
        $lessonList = Lesson::select('lesson_id', 'lesson_name')->get();
        $lessonTextList = LessonText::select('lesson_text_id', 'lesson_text_name')->get();

        return view('lessonSchedule.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'header' => $header,
            'lessonList' => $lessonList,
            'lessonTextList' => $lessonTextList,
            'lessonTiming' => $lessonTiming,
            'nextLessonTime' => $nextLessonTime,
            'numRow' => $numRow
        ]);
    }

    public function getData(Request $request) {
        $data = $request->all();

        if (empty($data['week'])) {
            return;
        }

        $data = $request->all();

        $teacherQueryBuilder = Teacher::select('teacher_id', 'teacher_name', 'teacher_nickname');

        if (isset($data['search_input']) && !empty($data['search_input'])) {
            $teacherQueryBuilder = $teacherQueryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('teacher_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher_nickname', $request['search_input']));
            });
        }

        $teacherList = $teacherQueryBuilder->get();
        
        $lessonStart = !empty($data['week']) ? $data['week']: date('Ymd');
        $w = date('w', strtotime($lessonStart));
        if ($w == 0) $w = 7;
        $lessonStart = date('Ymd', strtotime($lessonStart. ' -'.($w-1) . 'day'));
        $preWeek = date('Ymd', strtotime($lessonStart. ' -7 day'));
        $nextWeek = date('Ymd', strtotime($lessonStart. ' +7 day'));

        $header = array(
            date('M d (D)', strtotime($lessonStart)),
            date('M d (D)', strtotime($lessonStart. ' +1 day')),
            date('M d (D)', strtotime($lessonStart. ' +2 day')),
            date('M d (D)', strtotime($lessonStart. ' +3 day')),
            date('M d (D)', strtotime($lessonStart. ' +4 day')),
            date('M d (D)', strtotime($lessonStart. ' +5 day')),
            date('M d (D)', strtotime($lessonStart. ' +6 day')),
        );

        $lessonSchedule = [];
        $teacherZoomId = null;

        if (!empty($data['teacher_id']) && is_numeric($data['teacher_id'])) {
            $lessonSchedule = DB::select("CALL sp_admin_get_lesson_schedule_list('".(int) $data['teacher_id']."','".$lessonStart."')");
            $teacher = DB::table('teacher')
                ->select('teacher.zoom_personal_meeting_id')
                ->where('teacher.teacher_id', $data['teacher_id'])
                ->get();
            if ($teacher) {
                $teacherZoomId = $teacher[0]->zoom_personal_meeting_id;
            }
        }
        $lessonTiming = LessonTiming::LESSON_TIMING;
        $nextLessonTime = LessonTiming::NEXT_LESSON_TIME;
        $numRow = 24 * 60/ $nextLessonTime;
        $dataLessonSchedule = [];
        $dataSelected = [];
        $dataRegisted = [];

        if (!empty($lessonSchedule)) {
            $lessonSchedule = collect($lessonSchedule)->keyBy('lesson_starttime');
        }

        foreach ($lessonSchedule as &$item) {
            if ($item->course_type == CourseTypeEnum::GROUP_COURSE) {
                $startTime = date("Y-m-d H:i:s", strtotime($item->lesson_starttime. " +" . $nextLessonTime . " minutes"));
                $lessonSchedule[$startTime] = $item;
            }
        }

        $currentIndex = -1;
        $currentRowIndex = -1;
        
        for ($j = 0; $j < 7; $j++) {
            if (date("Y-m-d", strtotime($lessonStart. " + $j days")) == date("Y-m-d")) {
                $currentIndex = $j;
                break;
            }
        }

        for($i = 0; $i < $numRow; $i++) {
            $curRowTime = date("Y-m-d H:i:s", strtotime($lessonStart. " +" .$i * $nextLessonTime . " minutes"));
            
            $dataLessonSchedule[$i]['time'] = date("H:i",strtotime($curRowTime)) . "~". date("H:i", strtotime($curRowTime . "+ $lessonTiming minutes"));
            
            for($j = 0; $j < 7; $j++) {
                $dataLessonSchedule[$i][$j] = [];
                $dataSelected[$i][$j] = false;
                $dataRegisted[$i][$j] = false;
                $curCellTime = date("Y-m-d H:i:s", strtotime($curRowTime. " + $j days"));
                $timeFormat = date('M d (D) ', strtotime($curRowTime. " + $j days")).$dataLessonSchedule[$i]['time'];
                
                if ($currentIndex != -1 && $currentRowIndex == -1 && date('H:i', strtotime($curCellTime)) > date("H:i")) {
                    $currentRowIndex = $i;
                }

                if (isset($lessonSchedule[$curCellTime])) {
                    $lessonSchedule[$curCellTime]->start_time = $curCellTime; 
                    $lessonSchedule[$curCellTime]->time_format = $timeFormat;
                    $dataLessonSchedule[$i][$j][] = (array) $lessonSchedule[$curCellTime];
                    if ($lessonSchedule[$curCellTime]->lesson_type_id == 1) {
                        $dataRegisted[$i][$j] = true;
                    }
                } else {
                    $dataLessonSchedule[$i][$j][] = [
                        'lesson_schedule_id' => 0,
                        'lesson_type_id' => 0,
                        'lesson_id' => 0,
                        'lesson_text_id' => 0,
                        'lesson_name' => '-(-)',
                        'start_time' => $curCellTime,
                        'time_format' => $timeFormat,
                        'lesson_name_selected' => '',
                        'student_id' => '',
                        'student_name' => '',
                        'student_nickname' => ''
                    ];
                }
            }
        }

        return response()->json([
            'status' => 'OK',
            'preWeek' => $preWeek,
            'nextWeek' => $nextWeek,
            'header' => $header,
            'teacherList' => $teacherList,
            'dataLessonSchedule' => $dataLessonSchedule,
            'numRow' => $numRow,
            'dataSelected' => $dataSelected,
            'dataRegisted' => $dataRegisted,
            'currentIndex' => $currentIndex,
            'lessonTiming' => $lessonTiming,
            'teacherZoomId' => $teacherZoomId,
            'currentRowIndex' => $currentRowIndex
        ], StatusCode::OK);
    }

    public function registerMultiLesson(Request $request) {
        $data = $request->all();

        if (empty($data) || !isset($data['teacher_id']) || !is_numeric($data['teacher_id'])) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }
        foreach ($data['data_bulk_resistration'] as $value) {
            $lessonScheduleId =  !empty($value['lesson_schedule_id']) ? $value['lesson_schedule_id'] : -1;
            $groupCourseFlg = false;

            if ($lessonScheduleId != -1) {
                $lessonSchedule = LessonSchedule::where('lesson_schedule_id', $lessonScheduleId)->get();
                if ($lessonSchedule && $lessonSchedule[0]['course_type'] == CourseTypeEnum::GROUP_COURSE) {
                    $groupCourseFlg = true;
                }
            }

            if (!$groupCourseFlg) {
                $value['end_time'] = date("Y-m-d H:i:s" , strtotime($value["start_time"]. "+". $data['lesson_timing'] ."minutes"));
                $data['teacher_zoom_url'] = $data['teacher_zoom_id'];
                $string = DB::select("CALL sp_admin_register_lesson_for_teacher('".$lessonScheduleId."','".$data['teacher_id']."','".$value['start_time']."','".$value['end_time']."','".$data['teacher_zoom_url']."')");
            }
        }

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function removeMultiLesson(Request $request) {
        $data = $request->all();

        if (empty($data) || !isset($data['lesson_schedule_ids'])) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }

        foreach($data['lesson_schedule_ids'] as $lessonScheduleId) {
            $string = DB::select("CALL sp_admin_remove_lesson_for_teacher('".$lessonScheduleId."')");
        }

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function removeLesson(Request $request) {
        $data = $request->all();

        if (empty($data) || !isset($data['lesson_schedule_id'])) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }

        $string = DB::select("CALL sp_admin_remove_lesson_for_teacher('".$data['lesson_schedule_id']."')");

        if ($string[0]->result != 1) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function registerLesson(Request $request) {
        $data = $request->all();
        $lessonScheduleId =  !empty($data['lesson_schedule_id']) ? $data['lesson_schedule_id'] : -1;
        $data['end_time'] = date("Y-m-d H:i:s" , strtotime($data["start_time"]. "+". $data['lesson_timing'] ."minutes"));

        if(!is_numeric($data['teacher_id'])) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }

        $data['teacher_zoom_url'] = $data['teacher_zoom_id'];
        $string = DB::select("CALL sp_admin_register_lesson_for_teacher('".$lessonScheduleId."','".$data['teacher_id']."','".$data['start_time']."','".$data['end_time']."','".$data['teacher_zoom_url']."')");
        
        if ($string[0]->result != 1) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }

        return response()->json([
            'status' => 'OK',
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
