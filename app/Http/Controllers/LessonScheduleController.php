<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Models\Teacher;
use App\Models\Lesson;
use App\Models\LessonText;
use Illuminate\Support\Facades\DB;

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

        $data = $request->all();

        $teacherQueryBuilder = Teacher::select('teacher_name', 'teacher_nickname');

        if (isset($data['search_input'])) {
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
        $data['teacher_id'] = 16;
        $lessonSchedule = [];

        if (!empty($data['teacher_id']) && is_numeric($data['teacher_id'])) {
            $lessonSchedule = DB::select("CALL sp_admin_get_lesson_schedule_list('".(int) $data['teacher_id']."','".$lessonStart."')");
        }

        if (!empty($lessonSchedule)) {
            $lessonSchedule = $lessonSchedule->keyBy('lesson_starttime');
        }

        // $lessonTiming = LESSON_TIMING;
        // $nexLessonTime = NEXT_LESSON_TIME != 0 ? NEXT_LESSON_TIME : 60;
        // $numRow = 24 * 60/ $nexLessonTime;

        $lessonList = Lesson::select('lesson_id', 'lesson_name')->get();
        $lessonTextList = LessonText::select('lesson_text_id', 'lesson_text_name')->get();
        
dd($lessonTextList);
        return view('lessonSchedule.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'teacherList' => $teacherList,
            'lessonList' => $lessonList,
            'lessonTextList' => $lessonTextList
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
}
