<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\LessonHistory;
use App\Components\CommonComponent;
use App\Components\DateTimeComponent;
use DB;
use Log;

class TeacherLessonHistoryExport implements FromCollection, WithHeadings
{
    protected $id;
	protected $request;

    public function __construct($id, $request)
    {
       $this->id  = $id ;
       $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request = $this->request;
        $id = $this->id;

    	$queryBuilder = LessonHistory::select('lesson_schedule.lesson_date', 
            'lesson_schedule.lesson_starttime',
            'lesson_schedule.lesson_endtime',
            'course.course_name',
            'lesson.lesson_name',
            'lesson_text.lesson_text_name',
            'lesson_history.student_id',
            'student.student_name',
            'lesson_history.lesson_history_id'
        )
        ->join('lesson_schedule', function($join) use ($id) {
            $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id')
            ->where('lesson_schedule.teacher_id', $id);
        })
        ->leftJoin('lesson', function($join) {
            $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
        })
        ->leftJoin('lesson_text', function($join) {
            $join->on('lesson_schedule.lesson_text_id', '=', 'lesson_text.lesson_text_id');
        })
        ->leftJoin('student', function($join) {
            $join->on('lesson_history.student_id', '=', 'student.student_id');
        })
        ->leftJoin('course', function($join) {
            $join->on('lesson_history.course_id', '=', 'course.course_id');
        })
        ->where('lesson_history.student_lesson_reserve_type', '!=', 2)
        ->orderByDesc('lesson_schedule.lesson_starttime');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where(CommonComponent::escapeLikeSentence('course.course_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('lesson.lesson_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('lesson_text.lesson_text_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_name', $request['search_input']));
            });
        }

        if (isset($request['lesson_date_start']) && $request['lesson_date_start'] != null) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('lesson_schedule.lesson_date', '>=', $request['lesson_date_start']);
            });
        }

        if (isset($request['lesson_date_end']) && $request['lesson_date_end'] != null) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('lesson_schedule.lesson_date', '<=', $request['lesson_date_end']);
            });
        }

        $dataExport = $queryBuilder->get()->map(function($item, $key) {
            $item['lesson_date'] = DateTimeComponent::getDate($item['lesson_date']);
            $item['lesson_starttime'] = DateTimeComponent::getStartEndTime($item['lesson_starttime'], $item['lesson_endtime']);

            return $item;
        });

        foreach ($dataExport as &$item) {
            $item = $this->convertShijis($item);
        }

        return collect($dataExport);
    }

    public function headings(): array
    {
        $header = [
            "レッスン日",
            "レッスン時間",
            "コース名",
            "レッスン名",
            "テキスト名",
            "生徒番号",
            "生徒名"
        ];

        foreach ($header as $item) {
            $item = $this->convertShijis($item);
        }

        return $header;
    }

    private function convertShijis($text) {
        return mb_convert_encoding($text, "SJIS", "UTF-8");
    }
}
