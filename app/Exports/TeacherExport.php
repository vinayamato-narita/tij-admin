<?php

namespace App\Exports;

use App\Models\Teacher;
use App\Models\Lesson;
use App\Models\TeacherLesson;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Log;

class TeacherExport implements FromCollection, WithHeadings
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
    	$queryBuilder = new Teacher();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('teacher_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('teacher_email', $request['search_input']));
            });
        }

        $teacherList = $queryBuilder->get()->toArray();

        $teacherIds = $queryBuilder->pluck("teacher_id");
        $teacherLessons = TeacherLesson::select("teacher_id", "lesson_id")->whereIn('teacher_id', $teacherIds)->get()->toArray();
        $teacherLessonList = [];
        foreach($teacherLessons as $item) {
            $teacherLessonList[$item['teacher_id']][$item['lesson_id']] = $item['lesson_id'];
        }

        $lessionIds = Lesson::pluck('lesson_id');
        $dataExport = [];

        foreach ($teacherList as $teacher) {
            $input = array();
            $input[] = $teacher['teacher_name'];
            $input[] = $teacher['teacher_nickname'];
            $input[] = isset($item['teacher_birthday']) ? date('Y-m-d', strtotime($item['teacher_birthday'])) : "";
            $input[] = $teacher['teacher_email'];
            $input[] = $teacher['is_free_teacher'] == 1 ? "自由" : "固定";
            $input[] = isset($item['last_login_date']) ? date('Y-m-d', strtotime($item['last_login_date'])) : "";
            $input[] = $teacher['teacher_department'];
            $input[] = $teacher['teacher_university'];
            $input[] = $teacher['teacher_hobby'];
            $input[] = $teacher['show_flag'] == 1 ? "する" : "しない";
            $input[] = $teacher['teacher_introduction'];
            $input[] = $teacher['photo_savepath'];
            $input[] = $teacher['movie_savepath'];
            foreach($lessionIds as $lessionId) {
                if (array_key_exists($teacher['teacher_id'], $teacherLessonList) && array_key_exists($lessionId, $teacherLessonList[$teacher['teacher_id']])) {
                    $input[] = "1";
                }else {
                    $input[] = "0";
                }
            }
            $dataExport[] = $input;
        }
        return collect($dataExport);
    }

    public function headings(): array
    {
        $header = [
            "講師名",
            "ニックネーム",
            "生年月日",
            "メアド",
            "固定／自由",
            "最終ログイン日時",
            "出身",
            "居住",
            "日本語対応",
            "一覧への表示",
            "自己紹介",
            "イメージURL",
            "動画URL"
        ];

        $lessonList = Lesson::select("lesson_id", "lesson_name")->get()->toArray();

        foreach ($lessonList as $lesson) {
            $header[] = $this->convert_text($lesson['lesson_id'].":".$lesson['lesson_name']);
        }
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

    public  function convert_text($comment)
    {
        if (!isset($comment)) {
            return $comment;
        }
        $comment = str_replace('"', '""', $comment);
        if (isset($comment)) {
            $comment = '"'.$comment.'"';
        }
        $comment = str_replace("\r", ' ', $comment);
        $comment = str_replace("\n", ' ', $comment);
        
        return $comment;
    }
}
