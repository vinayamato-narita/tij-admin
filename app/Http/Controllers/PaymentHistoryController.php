<?php

namespace App\Http\Controllers;

use App\Models\Country;
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
use App\Components\DateTimeComponent;
use App\Components\CommonComponent;
use Log;
use Response;
use App\Enums\AdminRole;
use Auth;
use App\Enums\PaymentWay;
use App\Enums\PaymentStatus;
use App\Enums\PaymentWayEx;

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

        $queryBuilder = PointSubscriptionHistory::select('point_subscription_history.point_subscription_history_id as id',
        	'point_subscription_history.order_id as order_id',
        	'point_subscription_history.student_id as student_id',
        	'point_subscription_history.payment_date as payment_date',
        	'point_subscription_history.begin_date as begin_date',
        	'point_subscription_history.point_expire_date as point_expire_date',
        	'point_subscription_history.course_code as course_code',
            'point_subscription_history.item_name as item_name',
        	'student.student_name as j_student_name',
            'student.company_name as j_company_name',
        	'point_subscription_history.amount as amount',
        	'point_subscription_history.tax as tax',
        	'point_subscription_history.point_count as point_count',
        	'course.course_type',
        	'point_subscription_history.payment_way as payment_way',
            'point_subscription_history.payment_status as payment_status',
        	DB::raw('DATE_FORMAT(point_subscription_history.receive_payment_date, "%Y-%m-%d") AS j_receive_payment_date'),
        )
        ->leftJoin('order', function($join) {
            $join->on('point_subscription_history.order_id', '=', 'order.order_id');
        })
        ->leftJoin('course', function ($join) {
            $join->on('point_subscription_history.course_id', '=', 'course.course_id');
        })
        ->leftJoin('student', function($join) {
            $join->on('point_subscription_history.student_id', '=', 'student.student_id');
        })
        ->where('point_subscription_history.del_flag', 0);

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('point_subscription_history.order_id', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.company_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('point_subscription_history.item_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('point_subscription_history.course_code', $request['search_input']));
            });
        }
        if(isset($request['payment_date_start'])) {
            if ($request['payment_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.payment_date', '>=', $request['payment_date_start']);
            }
            if ($request['payment_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.payment_date', '<=', date('Y/m/d 23:59:59', strtotime($request['payment_date_end'])));
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
            if (isset($request['payment_way']) && $request['payment_way'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.payment_way', $request['payment_way']);
            }
            if ($request['course_code'] != "") {
                $queryBuilder = $queryBuilder->where($this->escapeLikeSentence('point_subscription_history.course_code', $request['course_code']));
            }
        }

        if (isset($request['sort'])) {
            if ($request['sort'] == "j_student_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student.student_name','ASC') : $queryBuilder->orderBy('student.student_name','DESC');
            }
            if ($request['sort'] == "j_company_name") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('student.company_name ASC') : $queryBuilder->orderByRaw('student.company_name DESC');
            }
            
            if ($request['sort'] == "j_receive_payment_date") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('DATE_FORMAT(point_subscription_history.receive_payment_date, "%Y-%m-%d") ASC') : $queryBuilder->orderByRaw('DATE_FORMAT(point_subscription_history.receive_payment_date, "%Y-%m-%d") DESC');
            }
            if ($request['sort'] == "amount") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('CAST(point_subscription_history.amount AS INT) ASC') : $queryBuilder->orderByRaw('CAST(point_subscription_history.amount AS INT) DESC');
            }
            if ($request['sort'] == "tax") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('CAST(point_subscription_history.tax AS INT) ASC') : $queryBuilder->orderByRaw('CAST(point_subscription_history.tax AS INT) DESC');
            }
            if ($request['sort'] == "point_count") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('CAST(point_subscription_history.point_count AS INT) ASC') : $queryBuilder->orderByRaw('CAST(point_subscription_history.point_count AS INT) DESC');
            }
        }

        Session::put('sessionPaymentHistory', collect($request));

        $paymentList = $queryBuilder->sortable(['update_date' => 'DESC'])->paginate($pageLimit);
        $total_tax = $queryBuilder->where('payment_status', PaymentStatus::SUCCESS)->sum('point_subscription_history.tax');
        $total_amount = $queryBuilder->where('payment_status', PaymentStatus::SUCCESS)->sum('point_subscription_history.amount');

        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;

        $paymentWays = PaymentWay::asSelectArray();

        return view('payment-history.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'paymentList' => $paymentList,
            'paymentWays' => $paymentWays,
            'total_amount' => $total_amount,
            'total_tax' => $total_tax,
            'adminSystem' => $adminSystem,
        ]);
    }

    public function export()
    {
        $adminSystem = Auth::user()->role == AdminRole::SYSTEM;
        if (!$adminSystem) {
            return;
        }
        $request = Session::get('sessionPaymentHistory');
        $fileName = "payment_".date("Y-m-d").".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $header = [
            $this->convertShijis("オーダーID"),
            $this->convertShijis("学習者番号"),
            $this->convertShijis("受注日"),
            $this->convertShijis("受講開始日"),
            $this->convertShijis("有効期限日"),
            $this->convertShijis("受注番号"),
            $this->convertShijis("コースID"),
            $this->convertShijis("コース名"),
            $this->convertShijis("学習者名"),
            $this->convertShijis("法人名"),
            $this->convertShijis("売上"),
            $this->convertShijis("消費税"),
            $this->convertShijis("支払方法"),
            $this->convertShijis("入金日"),
            $this->convertShijis("居住地")
        ];



        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $header);


        $queryBuilder = PointSubscriptionHistory::select('point_subscription_history.order_id as order_id',
            'point_subscription_history.student_id as student_id',
            'point_subscription_history.payment_date as payment_date',
            'point_subscription_history.begin_date as begin_date',
            'point_subscription_history.point_expire_date as point_expire_date',
            'point_subscription_history.point_subscription_history_id as id',
            'point_subscription_history.course_code as course_code',
            'point_subscription_history.item_name as item_name',
            'student.student_name as j_student_name',
            'student.company_name as j_company_name',
            'student.place_of_residence as j_place_of_residence',
            'point_subscription_history.amount as amount',
            'point_subscription_history.tax as tax',
            'point_subscription_history.payment_way as payment_way',
            DB::raw('DATE_FORMAT(point_subscription_history.receive_payment_date, "%Y-%m-%d") AS j_receive_payment_date'),
            'point_subscription_history.payment_status as payment_status'

        )
        ->leftJoin('order', function($join) {
            $join->on('point_subscription_history.order_id', '=', 'order.order_id');
        })
        ->leftJoin('student', function($join) {
            $join->on('point_subscription_history.student_id', '=', 'student.student_id');
        })
        ->where('point_subscription_history.del_flag', 0)
        ->orderByDesc('point_subscription_history.update_date');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where(CommonComponent::escapeLikeSentence('point_subscription_history.order_id', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.company_name', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('point_subscription_history.course_code', $request['search_input']))
                    ->orWhere(CommonComponent::escapeLikeSentence('point_subscription_history.item_name', $request['search_input']));
            });
        }

        if(isset($request['search_detail'])) {
            if ($request['payment_date_start'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.payment_date', '>=', $request['payment_date_start']);
            }
            if ($request['payment_date_end'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.payment_date', '<=', date('Y/m/d 23:59:59', strtotime($request['payment_date_end'])));
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
                $queryBuilder = $queryBuilder->where(CommonComponent::escapeLikeSentence('student.student_name', $request['student_name']));
            }
            if ($request['student_email'] != "") {
                $queryBuilder = $queryBuilder->where(CommonComponent::escapeLikeSentence('student.student_email', $request['student_email']));
            }
            if ($request['item_name'] != "") {
                $queryBuilder = $queryBuilder->where(CommonComponent::escapeLikeSentence('point_subscription_history.item_name', $request['item_name']));
            }
            if (isset($request['payment_way']) && $request['payment_way'] != "") {
                $queryBuilder = $queryBuilder->where('point_subscription_history.payment_way', $request['payment_way']);
            }
            if ($request['course_code'] != "") {
                $queryBuilder = $queryBuilder->where(CommonComponent::escapeLikeSentence('point_subscription_history.course_code', $request['course_code']));
            }
        }

        $paymentList = $queryBuilder->get()->map(function($item, $key) {
            $item['payment_date'] = DateTimeComponent::getDate($item['payment_date']);
            $item['begin_date'] = DateTimeComponent::getDate($item['begin_date']);
            $item['point_expire_date'] = DateTimeComponent::getDate($item['point_expire_date']);
            $item['amount'] = number_format($item['amount']);
            $item['tax'] = number_format($item['tax']);
            $item['payment_way'] = $item['payment_status'] == PaymentStatus::PENDING ? "-" : PaymentWay::getDescription($item['payment_way']);

        	return $item;
        });
        foreach ($paymentList as &$item) {
            $input = [];
            $input['オーダーID'] = $this->convertShijis($item['order_id']);
            $input['学習者番号'] = $this->convertShijis($item['student_id']);
            $input['受注日'] = $this->convertShijis($item['payment_date']);
            $input['受講開始日'] = $this->convertShijis($item['begin_date']);
            $input['有効期限日'] = $this->convertShijis($item['point_expire_date']);
            $input['受注番号'] = $this->convertShijis($item['id']);
            $input['コースID'] = $this->convertShijis($item['course_code']);
            $input['コース名'] = $this->convertShijis($item['item_name']);
            $input['学習者名'] = $this->convertShijis($item['j_student_name']);
            $input['法人名'] = $this->convertShijis($item['j_company_name']);
            $input['売上'] = $this->convertShijis($item['payment_status'] == PaymentStatus::PENDING ? 0 : $item['amount']);
            $input['消費税'] = $this->convertShijis($item['payment_status'] == PaymentStatus::PENDING ? 0 : $item['tax']);
            $input['支払方法'] = $this->convertShijis($item['payment_way']);
            $input['入金日'] = $this->convertShijis($item['j_receive_payment_date']);
            if (!empty( $item['j_place_of_residence']))
                $input['居住地'] = $this->convertShijis(Country::where('country_id', $item['j_place_of_residence'])->first()->country_name);
            else $input['居住地'] = $this->convertShijis('');
            fputcsv($file, $input);
        }

        return Response::download(public_path().'/csv_file/users/'.$fileName, $fileName, $header);
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
            'point_subscription_history.item_name as item_name',
            'student.student_name as j_student_name',
            'student.is_lms_user as is_lms_user',
            DB::raw("(CASE WHEN student.is_lms_user = 0 THEN student.company_name ELSE '' END) AS j_company_name"),
            'point_subscription_history.amount as amount',
            'point_subscription_history.tax as tax',
            'order.campaign_code as j_campaign_code',
            'point_subscription_history.point_count as point_count',
            'point_subscription_history.payment_way as payment_way',
            DB::raw('(CASE WHEN point_subscription_history.payment_way = 2 THEN DATE_FORMAT(point_subscription_history.receive_payment_date, "%Y-%m-%d") ELSE "" END) AS j_receive_payment_date'),
            DB::raw('(CASE WHEN point_subscription_history.payment_way = 2 THEN DATE_FORMAT(order.payment_term, "%Y-%m-%d") ELSE "" END) AS j_payment_term'),
            'point_subscription_history.payment_status as payment_status'
        )
        ->leftJoin('order', function($join) {
            $join->on('point_subscription_history.order_id', '=', 'order.order_id');
        })
        ->leftJoin('student', function($join) {
            $join->on('point_subscription_history.student_id', '=', 'student.student_id');
        })
        ->where('point_subscription_history.del_flag', 0)
        ->where('point_subscription_history.point_subscription_history_id', $id)->firstOrFail();

        $paymentInfo->_token = csrf_token();
        if($paymentInfo->payment_status == PaymentStatus::PENDING) {
            $paymentInfo->payment_way = PaymentWayEx::PENDING;
            $paymentInfo->amount = 0;
            $paymentInfo->tax = 0;
        }
        $paymentInfo->payment_ways = PaymentWayEx::asSelectArray();

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

        $paymentInfo = PointSubscriptionHistory::where('point_subscription_history.del_flag', 0)
            ->where('point_subscription_history.point_subscription_history_id', $request->id)
            ->first();

        if ($paymentInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $payment_status = 1;
        if ($request->payment_way >= PaymentWayEx::PENDING) {
            $payment_status = 0;
        }else {
            $paymentInfo->payment_way = $request->payment_way;
        }
        
        $paymentInfo->payment_status = $payment_status;

        $paymentInfo->save();

        DB::table('student_point_history')
            ->where('point_subscription_id', $request->id)
            ->update(['paid_status' => $payment_status]);

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public  function hankakuConvert($str) {
        $str = str_replace("-", "", $str);
        return mb_convert_kana($str, "n", mb_detect_encoding($str));
    }
}
