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

        $queryBuilder = PointSubscriptionHistory::select('point_subscription_history.point_subscription_history_id as id',
        	'point_subscription_history.order_id as order_id',
        	'point_subscription_history.student_id as student_id',
        	'point_subscription_history.payment_date as payment_date',
        	'student_point_history.start_date as j_start_date',
        	'point_subscription_history.begin_date as begin_date',
        	'point_subscription_history.point_expire_date as point_expire_date',
        	'course.course_name as j_course_name',
        	'point_subscription_history.customer_code as customer_code',
        	'point_subscription_history.item_name as item_name',
        	'student.student_name as j_student_name',
        	'student.is_lms_user as j_is_lms_user',
        	'point_subscription_history.course_code as course_code',
        	DB::raw("(CASE WHEN student.is_lms_user = 0 THEN student.company_name ELSE '' END) AS j_company_name"),
        	'lms_company.company_name as company_name',
        	'lms_project.project_code as j_project_code',
        	'point_subscription_history.corporation_code as corporation_code',
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
        ->where('point_subscription_history.del_flag', 0);

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
                $queryBuilder = $queryBuilder->where('student_point_history.point_subscription_id.start_date', '>=', $request['start_date_start']);
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

        if (isset($request['sort'])) {
            if ($request['sort'] == "j_start_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student_point_history.start_date','ASC') : $queryBuilder->orderBy('student_point_history.start_date','DESC');
            }
            if ($request['sort'] == "j_course_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('course.course_name','ASC') : $queryBuilder->orderBy('course.course_name','DESC');
            }
            if ($request['sort'] == "j_student_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student.student_name','ASC') : $queryBuilder->orderBy('student.student_name','DESC');
            }
            if ($request['sort'] == "j_company_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('(CASE WHEN student.is_lms_user = 0 THEN student.company_name ELSE "" END) ASC') : $queryBuilder->orderByRaw('(CASE WHEN student.is_lms_user = 0 THEN student.company_name ELSE "" END) DESC');
            }
            if ($request['sort'] == "company_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lms_company.company_name','ASC') : $queryBuilder->orderBy('lms_company.company_name','DESC');
            }
            if ($request['sort'] == "j_project_code") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('lms_project.project_code','ASC') : $queryBuilder->orderBy('lms_project.project_code','DESC');
            }
            if ($request['sort'] == "j_campaign_code") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('order.campaign_code','ASC') : $queryBuilder->orderBy('order.campaign_code','DESC');
            }
            if ($request['sort'] == "j_paid_status") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('(CASE WHEN point_subscription_history.payment_way = 2 THEN point_subscription_history.payment_way + point_subscription_history.paid_status ELSE point_subscription_history.payment_way END) ASC') : $queryBuilder->orderByRaw('(CASE WHEN point_subscription_history.payment_way = 2 THEN point_subscription_history.payment_way + point_subscription_history.paid_status ELSE point_subscription_history.payment_way END) DESC');
            }
            if ($request['sort'] == "j_receive_payment_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('(CASE WHEN point_subscription_history.payment_way = 2 THEN DATE_FORMAT(point_subscription_history.receive_payment_date, "%Y-%m-%d") ELSE "" END) ASC') : $queryBuilder->orderByRaw('(CASE WHEN point_subscription_history.payment_way = 2 THEN DATE_FORMAT(point_subscription_history.receive_payment_date, "%Y-%m-%d") ELSE "" END) DESC');
            }
            if ($request['sort'] == "j_payment_term") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('(CASE WHEN point_subscription_history.payment_way = 2 THEN DATE_FORMAT(order.payment_term, "%Y-%m-%d") ELSE "" END) ASC') : $queryBuilder->orderByRaw('(CASE WHEN point_subscription_history.payment_way = 2 THEN DATE_FORMAT(order.payment_term, "%Y-%m-%d") ELSE "" END) DESC');
            }
        }

        Session::put('sessionPaymentHistory', collect($request));

        $total_tax = $queryBuilder->sum('point_subscription_history.tax');
        $total_amount = $queryBuilder->sum('point_subscription_history.amount');
        $paymentList = $queryBuilder->groupBy('point_subscription_history.point_subscription_history_id')->sortable(['update_date' => 'DESC'])->paginate($pageLimit);

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
        $fileName = "payment_".date("Y-m-d").".csv";
        return Excel::download(new PaymentHistoryExport($request), $fileName);
    }
   
    public function edit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'payment_history_list'],
            ['name' => 'edit_payment_history', $id],
        ]);

        $paymentInfo = PointSubscriptionHistory::select('point_subscription_history.point_subscription_history_id as id',
            'point_subscription_history.order_id as order_id',
            'point_subscription_history.student_id as student_id',
            'point_subscription_history.payment_date as payment_date',
            'student_point_history.start_date as j_start_date',
            'point_subscription_history.begin_date as begin_date',
            'point_subscription_history.point_expire_date as point_expire_date',
            'course.course_name as j_course_name',
            'point_subscription_history.customer_code as customer_code',
            'point_subscription_history.item_name as item_name',
            'student.student_name as j_student_name',
            'student.is_lms_user as is_lms_user',
            'point_subscription_history.course_code as course_code',
            DB::raw("(CASE WHEN student.is_lms_user = 0 THEN student.company_name ELSE '' END) AS j_company_name"),
            'lms_company.company_name as company_name',
            'lms_project.project_code as j_project_code',
            'point_subscription_history.corporation_code as corporation_code',
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
        ->where('point_subscription_history.point_subscription_history_id', $id)->firstOrFail();

        $paymentInfo->_token = csrf_token();
        $paymentInfo->payment_types = PaidStatus::asSelectArray();
        
        $adminCanEdit = $this->adminCanEdit(PAYMENTHISTORY);

        return view('payment-history.edit', [
            'breadcrumbs' => $breadcrumbs,
            'paymentInfo' => $paymentInfo,
            'adminCanEdit' => $adminCanEdit,
        ]);
    }

    public function update(Request $request)
    {
        if(!$request->isMethod('PUT')) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);  
        }
        $adminCanEdit = $this->adminCanEdit(PAYMENTHISTORY);

        if ($adminCanEdit == 0) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        $paymentInfo = PointSubscriptionHistory::where('point_subscription_history.del_flag', 0)
            ->where('point_subscription_history.point_subscription_history_id', $request->id)
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
        
        DB::table('student_point_history')
            ->where('point_subscription_id', $request->id)
            ->update(['paid_status' => $paid_status]);

        DB::table('order')
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
