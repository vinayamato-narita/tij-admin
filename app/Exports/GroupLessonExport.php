<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Enums\CourseTypeEnum;
use App\Models\LessonSchedule;
use Carbon\Carbon;
use DB;

class GroupLessonExport implements FromCollection, WithHeadings
{
	protected $request;

    public function __construct($request)
    {
       $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request = $this->request;

    	$queryBuilder = LessonSchedule::select('lesson.lesson_id', 'lesson_schedule.lesson_date', 'lesson_schedule.lesson_starttime', 'lesson_schedule.lesson_endtime', 'lesson.lesson_name', 'lesson_text.lesson_text_name', 'teacher.teacher_id', 'teacher.teacher_name', 'teacher.teacher_email')
        ->leftJoin('teacher', function($join) {
            $join->on('lesson_schedule.teacher_id', '=', 'teacher.teacher_id');
        })
        ->leftJoin('course', function($join) {
            $join->on('lesson_schedule.course_id', '=', 'course.course_id');
        })
        ->leftJoin('lesson', function($join) {
            $join->on('lesson_schedule.lesson_id', '=', 'lesson.lesson_id');
        })
        ->leftJoin('lesson_text', function($join) {
            $join->on('lesson_schedule.lesson_text_id', '=', 'lesson_text.lesson_text_id');
        });

        if (!empty($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course.course_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher.teacher_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lesson.lesson_name', $request['search_input']));
            });
        }

        if (isset($request['time_from']) || isset($request['time_to'])) {
            $from = Carbon::createFromTimestamp($request['time_from']);
            $to = Carbon::createFromTimestamp($request['time_to']);
            if (!empty($request['time_from']) && !empty($request['time_to'])) {
                $queryBuilder = $queryBuilder->whereBetween('lesson_schedule.lesson_starttime', [$from, $to]);
            }
            else {
                if (!empty($request['time_from']))
                    $queryBuilder = $queryBuilder->whereDate('lesson_schedule.lesson_starttime', '>=', $from);
                if (!empty($request['time_to']))
                    $queryBuilder = $queryBuilder->whereDate('lesson_schedule.lesson_starttime', '<=', $to);
            }
        }

        $dataExport = $queryBuilder->whereHas('course', function ($q) {
            return $q->where('course_type', CourseTypeEnum::GROUP_COURSE);
        })->where('lesson_endtime' , '<', Carbon::now());

        $list = $dataExport->get()->map(function($item, $key) {
            $item['lesson_date'] = isset($item['lesson_date']) ? date('Y-m-d', strtotime($item['lesson_date'])) : "";
            $item['lesson_starttime'] = isset($item['lesson_starttime']) ? date('H:i:s', strtotime($item['lesson_starttime'])) : "";
            $item['lesson_endtime'] = isset($item['lesson_endtime']) ? date('H:i:s', strtotime($item['lesson_endtime'])) : "";

            return $item;
        });

        return collect($list);
    }

    public function headings(): array
    {
        $header = [
            "レッスンコード",
            "レッスン日",
            "レッスン時間",
            "レッスン予約時間",
            "レッスン名",
            "テキスト名",
            "講師コード",
            "講師名",
            "講師メールアドレス"
        ];

        return $header;
    }

    public function escapeLikeSentence($column, $str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', $this->mb_trim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_
        return [[$column, 'LIKE', (($before) ? '%' : '') . $result . (($after) ? '%' : '')]];
    }

    public function mb_trim($string)
    {
        $whitespace = '[\s\0\x0b\p{Zs}\p{Zl}\p{Zp}]';
        $ret = preg_replace(sprintf('/(^%s+|%s+$)/u', $whitespace, $whitespace), '', $string);
        return $ret;
    }
}
