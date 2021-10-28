<?php

namespace App\Exports;

use App\Models\PointSubscriptionHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Enums\PaidStatus;
use DB;
use DateTime;
use Log;

class PaymentHistoryExport implements FromCollection, WithHeadings
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
    	$queryBuilder = PointSubscriptionHistory::select('point_subscription_history.order_id as order_id',
            'point_subscription_history.student_id as student_id',
            'point_subscription_history.payment_date as payment_date',
            'student_point_history.start_date as j_start_date',
            'point_subscription_history.begin_date as begin_date',
            'point_subscription_history.point_expire_date as point_expire_date',
            'point_subscription_history.point_subscription_history_id as id',
            'point_subscription_history.course_code as course_code',
            'course.course_name as j_course_name',
            'point_subscription_history.item_name as item_name',
            'student.student_name as j_student_name',
            DB::raw("(CASE WHEN student.is_lms_user = 0 THEN student.company_name ELSE '' END) AS j_company_name"),
            'lms_company.company_name as company_name',  
            'lms_project.project_code as j_project_code',
            'point_subscription_history.corporation_code as corporation_code',
            'point_subscription_history.customer_code as customer_code',
            'point_subscription_history.amount as amount',
            'point_subscription_history.tax as tax',
            'order.campaign_code as j_campaign_code',
            'point_subscription_history.point_count as point_count',
            DB::raw('(CASE WHEN point_subscription_history.payment_way = 2 THEN point_subscription_history.payment_way + point_subscription_history.paid_status ELSE point_subscription_history.payment_way END) AS j_paid_status'),
            DB::raw('(CASE WHEN point_subscription_history.payment_way = 2 THEN DATE_FORMAT(point_subscription_history.receive_payment_date, "%Y-%m-%d") ELSE "" END) AS j_receive_payment_date'),
            DB::raw('(CASE WHEN point_subscription_history.payment_way = 2 THEN DATE_FORMAT(order.payment_term, "%Y-%m-%d") ELSE "" END) AS j_payment_term')
        )
        ->leftJoin('order', function($join) {
            $join->on('point_subscription_history.order_id', '=', 'order.order_id');
        })
        ->leftJoin('student', function($join) {
            $join->on('point_subscription_history.student_id', '=', 'student.student_id');
        })
        ->leftJoin('student_point_history', function($join) {
            $join->on('point_subscription_history.point_subscription_history_id', '=', 'student_point_history.point_subscription_id');
        })
        ->leftJoin('course', function($join) {
            $join->on('point_subscription_history.course_id', '=', 'course.course_id');
        })
        ->leftJoin('lms_project_course_student', function($join) {
            $join->on('point_subscription_history.point_subscription_history_id', '=', 'lms_project_course_student.point_subscription_id');
        })
        ->leftJoin('lms_project', function($join) {
            $join->on('lms_project_course_student.project_id', '=', 'lms_project.project_id');
        })
        ->leftJoin('lms_company', function($join) {
            $join->on('lms_project.company_id', '=', 'lms_company.company_id');
        })
        ->where('point_subscription_history.del_flag', 0)
        ->groupBy('point_subscription_history.point_subscription_history_id')
        ->orderByDesc('point_subscription_history.update_date');
        	
        $request = $this->request;
       
        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('point_subscription_history.order_id', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lms_company.company_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.company_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lms_project.project_code', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('point_subscription_history.corporation_code', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('order.campaign_code', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('point_subscription_history.item_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('course.course_name', $request['search_input']));
            });
        }
   
        if(isset($request['payment_date_start'])) {
            if ($request['payment_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.payment_date', '>=', $request['payment_date_start']);
            }
            if ($request['payment_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.payment_date', '<=', date('Y/m/d 23:59:59', strtotime($request['payment_date_end'])));
            }
            if ($request['start_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('student_point_history.start_date', '>=', $request['start_date_start']);
            }
            if ($request['start_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('student_point_history.start_date', '<=', date('Y/m/d 23:59:59', strtotime($request['start_date_end'])));
            }
            if ($request['begin_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.begin_date', '>=', $request['begin_date_start']);
            }
            if ($request['begin_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.begin_date', '<=', date('Y/m/d 23:59:59', strtotime($request['begin_date_end'])));
            }
            if ($request['point_expire_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.point_expire_date', '>=', $request['point_expire_date_start']);
            }
            if ($request['point_expire_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.point_expire_date', '<=', date('Y/m/d 23:59:59', strtotime($request['point_expire_date_end'])));
            }
            if ($request['student_id'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.student_id', '=', $request['student_id']);
            }
            if ($request['student_name'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('student.student_name', $request['student_name']));
            }
            if ($request['student_email'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('student.student_email', $request['student_email']));
            }
            if ($request['item_name'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('point_subscription_history.item_name', $request['item_name']));
            }
            if ($request['project_code'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('lms_project.project_code', $request['project_code']));
            }
            if ($request['company_name'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('lms_company.company_name', $request['company_name']));
            }
            if ($request['corporation_code'] != "" && !isset($request['check_corporation_code'])) {
                $queryBuilder = $queryBuilder->where('point_subscription_history.corporation_code', $request['corporation_code']);
            }
            if (isset($request['check_corporation_code'])) {
                $queryBuilder = $queryBuilder->where(function ($query) {
                    $query->where('point_subscription_history.corporation_code', "")
                        ->orWhereNull('point_subscription_history.corporation_code');
                });
            }
            if ($request['campaign_code'] != "") {
                $queryBuilder = $queryBuilder->where('order.campaign_code', $request['campaign_code']);
            }
            if ($request['j_paid_status'] != "") {
                $queryBuilder = $queryBuilder->whereRaw('IF(point_subscription_history.payment_way = 2, point_subscription_history.payment_way + point_subscription_history.paid_status, point_subscription_history.payment_way) = '. $request['j_paid_status']);
            }
        }

        $paymentList = $queryBuilder->get()->map(function($item, $key) {
            $item['payment_date'] = isset($item['payment_date']) ? date('Y-m-d', strtotime($item['payment_date'])) : "";
            $item['j_start_date'] = isset($item['j_start_date']) ? date('Y-m-d', strtotime($item['j_start_date'])) : "";
            $item['begin_date'] = isset($item['begin_date']) ? date('Y-m-d', strtotime($item['begin_date'])) : "";
            $item['point_expire_date'] = isset($item['point_expire_date']) ? date('Y-m-d', strtotime($item['point_expire_date'])) : "";
            $item['amount'] = number_format($item['amount']);
            $item['tax'] = number_format($item['tax']);
            $item['j_paid_status'] = PaidStatus::getDescription($item['j_paid_status']);

        	return $item;
        });
        return $paymentList;
    }

    public function headings(): array
    {
        return [
            "オーダーID",
            "生徒番号",
            "受注日",
            "基準日",
            "受講開始日",
            "有効期限日",
            "受注番号",
            "商品コード",
            "セットコース名",
            "商品名",
            "生徒名",
            "法人名",
            "企業名",
            "企業ID",
            "法人コード",
            "得意先コード",
            "売上",
            "消費税",
            "キャンペーンコード",
            "ポイント数",
            "支払い状況",
            "入金日",
            "支払期限"
        ];
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
