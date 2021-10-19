<?php

namespace App\Exports;

use App\Models\StudentList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Enums\StudentEntryType;
use DB;
use DateTime;
use Log;

class StudentExport implements FromCollection, WithHeadings
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
    	$queryBuilder = StudentList::select('student_list.student_id as student_id',
            'student_list.student_name as student_name',
            'student_list.student_email as student_email',
            DB::raw("(CASE WHEN student_list.is_lms_user = 0 THEN student_list.company_name ELSE '' END) AS custom_company_name"),
            DB::raw("CONCAT('/',GROUP_CONCAT(DISTINCT lms_projects.project_code SEPARATOR '/'),'/') as all_project_code"),
            DB::raw("CONCAT('/',GROUP_CONCAT(DISTINCT lms_companies.company_name SEPARATOR '/'),'/') as all_project_company_name"),
            DB::raw("CONCAT('/',GROUP_CONCAT(DISTINCT NULLIF(IF(is_lms_user = 1, lms_companies.legal_code, point_subscription_histories.corporation_code),'') SEPARATOR '/'),'/') as company_code"),
            'student_list.create_date as create_date',
            DB::raw("COALESCE(MIN(IF(point_subscription_histories.course_id = 1 AND student_list.is_lms_user = 0, NULL, point_subscription_histories.payment_date)),'---') AS first_payment_date"), 
            'student_list.last_login_at as last_login_at',  
            DB::raw("MIN(CASE WHEN lesson_histories.student_lesson_reserve_type = 3 THEN lesson_schedules.lesson_starttime END) AS first_lesson_date"),  
            DB::raw("COUNT(DISTINCT lesson_histories.id) AS lesson_count"),  
            'student_list.is_tmp_entry as is_tmp_entry',  
            DB::raw("IF(student_list.course_id > 1,'有料','無料') AS course_name"),  
            'student_list.direct_mail_flag as direct_mail_flag',  
            'student_list.student_comment_text as student_comment_text',  
        )
        ->leftJoin('point_subscription_histories', function($join) {
            $join->on('student_list.student_id', '=', 'point_subscription_histories.student_id')
                ->where('point_subscription_histories.del_flag', '=', 0);
        })
        ->leftJoin('lesson_histories', function($join) {
            $join->on('student_list.student_id', '=', 'lesson_histories.student_id')
                ->where('lesson_histories.student_lesson_reserve_type', '<>', 2);
        })
        ->leftJoin('lesson_schedules', function($join) {
            $join->on('lesson_histories.lesson_schedule_id', '=', 'lesson_schedules.id')
                ->where('lesson_histories.student_lesson_reserve_type', '<>', 2);
        })
        ->leftJoin('lms_project_students', function($join) {
            $join->on('student_list.student_id', '=', 'lms_project_students.student_id');
        })
        ->leftJoin('lms_projects', function($join) {
            $join->on('lms_project_students.project_id', '=', 'lms_projects.id');
        })
        ->leftJoin('lms_companies', function($join) {
            $join->on('lms_projects.company_id', '=', 'lms_companies.id');
        })
        ->groupBy('student_list.student_id')
        ->orderByDesc('student_list.student_id');
        	
        $request = $this->request;
       
        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('student_list.student_id', '=',$request['search_input'])
                    ->orWhere($this->escapeLikeSentence('student_list.student_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student_list.student_nickname', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student_list.student_email', $request['search_input']));
            });
        }
        if (isset($request['student_id'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('student_list.student_name', $request['student_name']))
                    ->where($this->escapeLikeSentence('student_list.student_nickname', $request['student_nickname']))
                    ->where($this->escapeLikeSentence('student_list.student_skypename', $request['student_skypename']))
                    ->where($this->escapeLikeSentence('student_list.student_email', $request['student_email']))
                    ->where($this->escapeLikeSentence('lms_companies.company_name', $request['all_project_company_name']))
                    ->where($this->escapeLikeSentence('student_list.company_name', $request['custom_company_name']))
                    ->where($this->escapeLikeSentence('lms_projects.project_code', $request['all_project_code']));

                    if(!isset($request['check_company_code'])) {
                        $query->where(function($query) use ($request) {
                            $query->orWhere($this->escapeLikeSentence('lms_companies.legal_code', $request['company_code']))
                                ->orWhere($this->escapeLikeSentence('point_subscription_histories.corporation_code', $request['company_code']));
                        });
                    }
                    if(isset($request['check_company_code'])) {
                        $query->where(function($query) {
                            $query->orWhere('lms_companies.legal_code', '=', '')
                                ->orWhereNull('lms_companies.legal_code')
                                ->orWhere('point_subscription_histories.corporation_code', '=', '')
                                ->orWhereNull('point_subscription_histories.corporation_code');
                        });
                    }
                    if ($request['student_id'] != "") {
                        $query->where('student_list.student_id', '=', $request['student_id']);
                    }
                    if ($request['first_lesson_date'] != "") {
                        $query->where('student_list.create_date', '>=', $request['first_lesson_date']);
                    }
            });
        }

        $studentList = $queryBuilder->get()->map(function($item, $key) {
            $item['all_project_code'] = trim($item['all_project_code'], '/');
            $item['all_project_company_name'] = trim($item['all_project_company_name'], '/');
            $item['company_code'] = trim($item['company_code'], '/');
            $item['create_date'] = isset($item['create_date']) ? date('Y-m-d', strtotime($item['create_date'])) : "";
            $item['first_payment_date'] = DateTime::createFromFormat('Y-m-d', $item['first_payment_date']) ? date('Y-m-d', strtotime($item['first_payment_date'])) : "";
            $item['last_login_at'] = isset($item['last_login_at']) ? date('Y-m-d', strtotime($item['last_login_at'])) : "";
            $item['first_lesson_date'] = isset($item['first_lesson_date']) ? date('Y-m-d', strtotime($item['first_lesson_date'])) : "";
            $item['is_tmp_entry'] = StudentEntryType::getDescription($item['is_tmp_entry']);

            if (is_null($item['direct_mail_flag']) === true) {
                $item['direct_mail_flag'] = "";
            } else if(!$item['direct_mail_flag']) {
                $item['direct_mail_flag'] = "0";
            } else {
                $item['direct_mail_flag'] = $item['direct_mail_flag'];
            }
        	return $item;
        });
        return $studentList;
    }

    public function headings(): array
    {
        return ["生徒番号", "生徒名", "生徒メール", "法人名", "企業ID", "企業名", "法人コード", "初回登録日", "初回支払日", "最終ログイン日", "初回受講日", "通算受講回数", "登録状態", "有料/無料", "DMステータス", "連絡事項"];
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
