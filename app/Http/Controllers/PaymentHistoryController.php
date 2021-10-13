<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\PaidStatus;
use App\Models\PointSubscriptionHistory;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Session;
use App\Exports\PaymentHistoryExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Enums\StatusCode;
use Log; 

class PaymentHistoryController extends BaseController
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
            ['name' => 'payment_history_list'],
        ]);
        $pageLimit = $this->newListLimit($request);

        $paymentType = PaidStatus::asSelectArray();

        $queryBuilder = PointSubscriptionHistory::select('point_subscription_histories.id as id',
        	'point_subscription_histories.order_id as order_id',
        	'point_subscription_histories.student_id as student_id',
        	'point_subscription_histories.payment_date as payment_date',
        	'student_point_histories.start_date as j_start_date',
        	'point_subscription_histories.begin_date as begin_date',
        	'point_subscription_histories.point_expire_date as point_expire_date',
        	'courses.course_name as j_course_name',
        	'point_subscription_histories.customer_code as customer_code',
        	'point_subscription_histories.item_name as item_name',
        	'students.student_name as j_student_name',
        	'students.is_lms_user as j_is_lms_user',
        	'point_subscription_histories.course_code as course_code',
        	DB::raw("(CASE WHEN students.is_lms_user = 0 THEN students.company_name ELSE '' END) AS j_company_name"),
        	'lms_companies.company_name as company_name',
        	'lms_projects.project_code as j_project_code',
        	'point_subscription_histories.corporation_code as corporation_code',
        	'point_subscription_histories.amount as amount',
        	'point_subscription_histories.tax as tax',
        	'orders.campaign_code as j_campaign_code',
        	'point_subscription_histories.point_count as point_count',
        	DB::raw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN point_subscription_histories.payment_way + point_subscription_histories.paid_status ELSE point_subscription_histories.payment_way END) AS j_paid_status'),
        	DB::raw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN DATE_FORMAT(point_subscription_histories.receive_payment_date, "%Y-%m-%d") ELSE "" END) AS j_receive_payment_date'),
        	DB::raw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN DATE_FORMAT(orders.payment_term, "%Y-%m-%d") ELSE "" END) AS j_payment_term')
        )
        ->leftJoin('orders', function($join) {
            $join->on('point_subscription_histories.order_id', '=', 'orders.order_id');
        })
        ->leftJoin('students', function($join) {
            $join->on('point_subscription_histories.student_id', '=', 'students.id');
        })
        ->leftJoin('student_point_histories', function($join) {
            $join->on('point_subscription_histories.id', '=', 'student_point_histories.point_subscription_id');
        })
        ->leftJoin('courses', function($join) {
            $join->on('point_subscription_histories.course_id', '=', 'courses.course_id');
        })
        ->leftJoin('lms_project_course_students', function($join) {
            $join->on('point_subscription_histories.id', '=', 'lms_project_course_students.point_subscription_id');
        })
        ->leftJoin('lms_projects', function($join) {
            $join->on('lms_project_course_students.project_id', '=', 'lms_projects.id');
        })
        ->leftJoin('lms_companies', function($join) {
            $join->on('lms_projects.company_id', '=', 'lms_companies.id');
        })
        ->where('point_subscription_histories.del_flag', 0);

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('point_subscription_histories.order_id', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('students.student_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lms_companies.company_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('students.company_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lms_projects.project_code', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('point_subscription_histories.corporation_code', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('orders.campaign_code', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('point_subscription_histories.item_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('courses.course_name', $request['search_input']));
            });
        }
   
        if(isset($request['payment_date_start'])) {
            if ($request['payment_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_histories.payment_date', '>=', $request['payment_date_start']);
            }
            if ($request['payment_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_histories.payment_date', '<=', date('Y/m/d 23:59:59', strtotime($request['payment_date_end'])));
            }
            if ($request['start_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('student_point_histories.start_date', '>=', $request['start_date_start']);
            }
            if ($request['start_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('student_point_histories.start_date', '<=', date('Y/m/d 23:59:59', strtotime($request['start_date_end'])));
            }
            if ($request['begin_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_histories.begin_date', '>=', $request['begin_date_start']);
            }
            if ($request['begin_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_histories.begin_date', '<=', date('Y/m/d 23:59:59', strtotime($request['begin_date_end'])));
            }
            if ($request['point_expire_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_histories.point_expire_date', '>=', $request['point_expire_date_start']);
            }
            if ($request['point_expire_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_histories.point_expire_date', '<=', date('Y/m/d 23:59:59', strtotime($request['point_expire_date_end'])));
            }
            if ($request['student_id'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_histories.student_id', '=', $request['student_id']);
            }
            if ($request['student_name'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('students.student_name', $request['student_name']));
            }
            if ($request['student_email'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('students.student_email', $request['student_email']));
            }
            if ($request['item_name'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('point_subscription_histories.item_name', $request['item_name']));
            }
            if ($request['project_code'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('lms_projects.project_code', $request['project_code']));
            }
            if ($request['company_name'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('lms_companies.company_name', $request['company_name']));
            }
            if ($request['corporation_code'] != "" && !isset($request['check_corporation_code'])) {
                $queryBuilder = $queryBuilder->where('point_subscription_histories.corporation_code', $request['corporation_code']);
            }
            if (isset($request['check_corporation_code'])) {
                $queryBuilder = $queryBuilder->where(function ($query) {
                    $query->where('point_subscription_histories.corporation_code', "")
                        ->orWhereNull('point_subscription_histories.corporation_code');
                });
            }
            if ($request['campaign_code'] != "") {
                $queryBuilder = $queryBuilder->where('orders.campaign_code', $request['campaign_code']);
            }
            if ($request['j_paid_status'] != "") {
                $queryBuilder = $queryBuilder->whereRaw('IF(point_subscription_histories.payment_way = 2, point_subscription_histories.payment_way + point_subscription_histories.paid_status, point_subscription_histories.payment_way) = '. $request['j_paid_status']);
            }
        }

        if (isset($request['sort'])) {
            if ($request['sort'] == "j_start_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_point_histories.start_date','ASC') : $queryBuilder->orderBy('student_point_histories.start_date','DESC');
            }
            if ($request['sort'] == "j_course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('courses.course_name','ASC') : $queryBuilder->orderBy('courses.course_name','DESC');
            }
            if ($request['sort'] == "j_student_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('students.student_name','ASC') : $queryBuilder->orderBy('students.student_name','DESC');
            }
            if ($request['sort'] == "j_company_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('(CASE WHEN students.is_lms_user = 0 THEN students.company_name ELSE "" END) ASC') : $queryBuilder->orderByRaw('(CASE WHEN students.is_lms_user = 0 THEN students.company_name ELSE "" END) DESC');
            }
            if ($request['sort'] == "company_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lms_companies.company_name','ASC') : $queryBuilder->orderBy('lms_companies.company_name','DESC');
            }
            if ($request['sort'] == "j_project_code") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lms_projects.project_code','ASC') : $queryBuilder->orderBy('lms_projects.project_code','DESC');
            }
            if ($request['sort'] == "j_campaign_code") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('orders.campaign_code','ASC') : $queryBuilder->orderBy('orders.campaign_code','DESC');
            }
            if ($request['sort'] == "j_paid_status") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN point_subscription_histories.payment_way + point_subscription_histories.paid_status ELSE point_subscription_histories.payment_way END) ASC') : $queryBuilder->orderByRaw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN point_subscription_histories.payment_way + point_subscription_histories.paid_status ELSE point_subscription_histories.payment_way END) DESC');
            }
            if ($request['sort'] == "j_receive_payment_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN DATE_FORMAT(point_subscription_histories.receive_payment_date, "%Y-%m-%d") ELSE "" END) ASC') : $queryBuilder->orderByRaw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN DATE_FORMAT(point_subscription_histories.receive_payment_date, "%Y-%m-%d") ELSE "" END) DESC');
            }
            if ($request['sort'] == "j_payment_term") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN DATE_FORMAT(orders.payment_term, "%Y-%m-%d") ELSE "" END) ASC') : $queryBuilder->orderByRaw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN DATE_FORMAT(orders.payment_term, "%Y-%m-%d") ELSE "" END) DESC');
            }
        }

        Session::put('sessionPaymentHistory', json_encode($request));

        $total_tax = $queryBuilder->sum('point_subscription_histories.tax');
        $total_amount = $queryBuilder->sum('point_subscription_histories.amount');
        $paymentList = $queryBuilder->groupBy('point_subscription_histories.id')->sortable(['update_date' => 'DESC'])->paginate($pageLimit);

        return view('payment-history.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'paymentList' => $paymentList,
            'paymentType' => $paymentType,
            'total_amount' => $total_amount,
            'total_tax' => $total_tax,
        ]);
    }

    public function export()
    {
        $request = Session::get('sessionStudent');
        $fileName = "payment_".date("Y_m_d").".csv";
        return Excel::download(new PaymentHistoryExport($request), $fileName);
    }
   
    public function edit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'payment_history_list'],
            ['name' => 'edit_payment_history', $id],
        ]);

        $paymentInfo = PointSubscriptionHistory::select('point_subscription_histories.id as id',
            'point_subscription_histories.order_id as order_id',
            'point_subscription_histories.student_id as student_id',
            'point_subscription_histories.payment_date as payment_date',
            'student_point_histories.start_date as j_start_date',
            'point_subscription_histories.begin_date as begin_date',
            'point_subscription_histories.point_expire_date as point_expire_date',
            'courses.course_name as j_course_name',
            'point_subscription_histories.customer_code as customer_code',
            'point_subscription_histories.item_name as item_name',
            'students.student_name as j_student_name',
            'students.is_lms_user as is_lms_user',
            'point_subscription_histories.course_code as course_code',
            DB::raw("(CASE WHEN students.is_lms_user = 0 THEN students.company_name ELSE '' END) AS j_company_name"),
            'lms_companies.company_name as company_name',
            'lms_projects.project_code as j_project_code',
            'point_subscription_histories.corporation_code as corporation_code',
            'point_subscription_histories.amount as amount',
            'point_subscription_histories.tax as tax',
            'orders.campaign_code as j_campaign_code',
            'point_subscription_histories.point_count as point_count',
            DB::raw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN point_subscription_histories.payment_way + point_subscription_histories.paid_status ELSE point_subscription_histories.payment_way END) AS j_paid_status'),
            DB::raw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN DATE_FORMAT(point_subscription_histories.receive_payment_date, "%Y-%m-%d") ELSE "" END) AS j_receive_payment_date'),
            DB::raw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN DATE_FORMAT(orders.payment_term, "%Y-%m-%d") ELSE "" END) AS j_payment_term')
        )
        ->leftJoin('orders', function($join) {
            $join->on('point_subscription_histories.order_id', '=', 'orders.order_id');
        })
        ->leftJoin('students', function($join) {
            $join->on('point_subscription_histories.student_id', '=', 'students.id');
        })
        ->leftJoin('student_point_histories', function($join) {
            $join->on('point_subscription_histories.id', '=', 'student_point_histories.point_subscription_id');
        })
        ->leftJoin('courses', function($join) {
            $join->on('point_subscription_histories.course_id', '=', 'courses.course_id');
        })
        ->leftJoin('lms_project_course_students', function($join) {
            $join->on('point_subscription_histories.id', '=', 'lms_project_course_students.point_subscription_id');
        })
        ->leftJoin('lms_projects', function($join) {
            $join->on('lms_project_course_students.project_id', '=', 'lms_projects.id');
        })
        ->leftJoin('lms_companies', function($join) {
            $join->on('lms_projects.company_id', '=', 'lms_companies.id');
        })
        ->where('point_subscription_histories.del_flag', 0)
        ->where('point_subscription_histories.id', $id)->firstOrFail();

        $paymentInfo->_token = csrf_token();
        $paymentInfo->payment_types = PaidStatus::asSelectArray();
       
        return view('payment-history.edit', [
            'breadcrumbs' => $breadcrumbs,
            'paymentInfo' => $paymentInfo,
        ]);
    }

    public function update(Request $request)
    {
        if(!$request->isMethod('PUT')) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);  
        }

        $paymentInfo = PointSubscriptionHistory::where('point_subscription_histories.del_flag', 0)
            ->where('point_subscription_histories.id', $request->id)
            ->first();

        if ($paymentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $payment_type = $request->j_paid_status;
        $paid_status = 1;
        if ($request->j_paid_status >= 2) {
            $payment_type = 2;
            $paid_status = $request->j_paid_status - 2;
        }
        $corporation_code = $this->hankakuConvert($request->corporation_code);
        $paymentInfo->corporation_code = $corporation_code;
        $paymentInfo->customer_code = $request->customer_code;
        $paymentInfo->payment_way = $payment_type;
        $paymentInfo->paid_status = $paid_status;

        $paymentInfo->save();
        
        DB::table('student_point_histories')
            ->where('point_subscription_id', $request->id)
            ->update(['paid_status' => $paid_status]);

        DB::table('orders')
            ->where('order_id', $request->order_id)
            ->update(['corporation_code' => $corporation_code]);

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public  function hankakuConvert($str) {
        $str = str_replace("-", "", $str);
        return mb_convert_kana($str, "n", mb_detect_encoding($str));
    }
}
