<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Enums\StudentEntryType;
use DB;
use DateTime;
use App\Components\CommonComponent;
use App\Components\DateTimeComponent;
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
    	$queryBuilder = Student::select('student.student_id as student_id',
            'student.student_name as student_name',
            'student.student_email as student_email',
            DB::raw("(CASE WHEN student.is_lms_user = 0 THEN student.company_name ELSE '' END) AS custom_company_name"),
            'student.create_date as create_date',
            DB::raw("COALESCE(MIN(IF(point_subscription_history.course_id = 1 AND student.is_lms_user = 0, NULL, point_subscription_history.payment_date)),'---') AS first_payment_date"), 
            'student.last_login_date as last_login_date',  
            DB::raw("MIN(CASE WHEN lesson_history.student_lesson_reserve_type = 3 THEN lesson_schedule.lesson_starttime END) AS first_lesson_date"),  
            DB::raw("COUNT(DISTINCT lesson_history.lesson_history_id) AS lesson_count"),  
            'student.is_tmp_entry as is_tmp_entry',  
            DB::raw("IF(student.course_id > 1,'有料','無料') AS course_name"),  
            'student.direct_mail_flag as direct_mail_flag',  
            'student.student_comment_text as student_comment_text',  
        )
        ->leftJoin('point_subscription_history', function($join) {
            $join->on('student.student_id', '=', 'point_subscription_history.student_id')
                ->where('point_subscription_history.del_flag', '=', 0);
        })
        ->leftJoin('lesson_history', function($join) {
            $join->on('student.student_id', '=', 'lesson_history.student_id')
                ->where('lesson_history.student_lesson_reserve_type', '<>', 2);
        })
        ->leftJoin('lesson_schedule', function($join) {
            $join->on('lesson_history.lesson_schedule_id', '=', 'lesson_schedule.lesson_schedule_id')
                ->where('lesson_history.student_lesson_reserve_type', '<>', 2);
        })
        ->leftJoin('lms_project_student', function($join) {
            $join->on('student.student_id', '=', 'lms_project_student.student_id');
        })
        ->leftJoin('lms_project', function($join) {
            $join->on('lms_project_student.project_id', '=', 'lms_project.project_id');
        })
        ->leftJoin('lms_company', function($join) {
            $join->on('lms_project.company_id', '=', 'lms_company.company_id');
        })
        ->groupBy('student.student_id');
        	
        $request = $this->request;
       
        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('student.student_id', '=',$request['search_input'])
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_nickname', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_email', $request['search_input']));
            });
        }
        if (isset($request['search_detail'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where(CommonComponent::escapeLikeSentence('student.student_name', $request['student_name']))
                    ->where(CommonComponent::escapeLikeSentence('student.student_nickname', $request['student_nickname']))
                    ->where(CommonComponent::escapeLikeSentence('student.student_skypename', $request['student_skypename']))
                    ->where(CommonComponent::escapeLikeSentence('student.student_email', $request['student_email']))
                    ->where(CommonComponent::escapeLikeSentence('student.company_name', $request['custom_company_name']));

                    if ($request['all_project_code'] != "") {
                        $query->where(CommonComponent::escapeLikeSentence('lms_project.project_code', $request['all_project_code']));
                    }
                    if ($request['all_project_company_name'] != "") {
                        $query->where(CommonComponent::escapeLikeSentence('lms_company.company_name', $request['all_project_company_name']));
                    }
                    if(!isset($request['check_company_code'])) {
                        $query->where(function($query) use ($request) {
                            $query->orWhere(CommonComponent::escapeLikeSentence('lms_company.legal_code', $request['company_code']))
                                ->orWhere(CommonComponent::escapeLikeSentence('point_subscription_history.corporation_code', $request['company_code']));
                        });
                    }
                    if(isset($request['check_company_code'])) {
                        $query->where(function($query) {
                            $query->orWhere('point_subscription_history.corporation_code', '=', '')
                                ->orWhereNull('point_subscription_history.corporation_code');
                        });
                        $query->where(function($query) {
                            $query->orWhere('lms_company.legal_code', '=', '')
                                ->orWhereNull('lms_company.legal_code');
                        });
                    }
                    if ($request['student_id'] != "") {
                        $query->where('student.student_id', '=', $request['student_id']);
                    }
                    if ($request['create_date'] != "") {
                        $query->where('student.create_date', '>=', $request['create_date']);
                    }
            });
        }

        $studentList = $queryBuilder->get()->map(function($item, $key) {
            $item['create_date'] = DateTimeComponent::getDate($item['create_date']);
            $item['first_payment_date'] = DateTimeComponent::getDate($item['first_payment_date']);
            $item['first_lesson_date'] = DateTimeComponent::getDate($item['first_lesson_date']);
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
        return [
            "学習者番号", 
            "学習者名", 
            "学習者メール", 
            "法人名", 
            "初回登録日", 
            "初回支払日", 
            "最終ログイン日", 
            "初回受講日", 
            "通算受講回数", 
            "登録状態", 
            "有料/無料", 
            "DMステータス", 
            "連絡事項"
        ];
    }
}
