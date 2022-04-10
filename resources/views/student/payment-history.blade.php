@php
    use App\Components\SearchQueryComponent;
    use App\Components\DateTimeComponent;
    use App\Enums\PaidStatus;
@endphp

@extends('layouts.default')
@section('title', '支払い履歴一覧')
@section('content')
<div class="c-body"> 
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        支払い履歴一覧
                    </h5>
                </div>
                <div class="pull-right mrb-5">
                    <a href="{{ route('student.createPaymentHistory', $studentInfo->student_id) }}" class="btn btn-primary pull-right"
                        ><i class="las la-plus"></i>新規作成
                    </a>
                </div>
            </div>
            <div class="clear"></div>
            <div class="fade-in">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}" :page-limit="{{ $pageLimit }}"></page-size>
                                    </div>
                                    <div class="col-md-2 fwb pt-7">
                                        <label>学習者番号 : </label> 
                                        {{ $studentInfo->student_id }}
                                    </div>
                                    <div class="col-md-3 fwb pt-7">
                                        <label>学習者名 : </label>
                                        {{ $studentInfo->student_name }}
                                    </div>
                                    <div class="col-md-4">
                                        <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('student.paymentHistoryList', $studentInfo->student_id)) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                    </div>
                                </div>
                                @if(!$paymentHistoryList->isEmpty())
                                    {{ $paymentHistoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-center width-130">@sortablelink('point_subscription_history_id', '受注番号')</th>
                                                    <th class="text-center min-width-120">@sortablelink('course_name', 'コース名')</th>
                                                    <th class="text-center min-width-120">@sortablelink('j_paid_status', '支払い方法')</th>
                                                    <th class="text-center min-width-120">@sortablelink('amount', '支払い金額')</th>
                                                    <th class="text-center min-width-120">@sortablelink('payment_date', '受注日')</th>
                                                    <th class="text-center min-width-120">@sortablelink('begin_date', '受講開始日')</th>
                                                    <th class="text-center min-width-120">@sortablelink('point_expire_date', '有効期限日')</th>
                                                    <th class="text-center min-width-120">@sortablelink('receive_payment_date', '入金日')</th>
                                                    <th class="w-100"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($paymentHistoryList as $index => $payment)
                                                    <tr>
                                                        <td class="text-center">{{ $payment->point_subscription_history_id }}</td>
                                                        <td class="text-center">{{ $payment->course_name }}</td>
                                                        <td class="text-center">{{ PaidStatus::getDescription($payment->j_paid_status) }}</td>
                                                        <td class="text-center">{{ number_format($payment->amount) }}</td>
                                                        <td class="text-center">{{ DateTimeComponent::getDate($payment->payment_date) }}</td>
                                                        <td class="text-center">{{ DateTimeComponent::getDate($payment->begin_date) }}</td>
                                                        <td class="text-center">{{ DateTimeComponent::getDate($payment->point_expire_date) }}</td>
                                                        <td class="text-center">{{ DateTimeComponent::getDate($payment->receive_payment_date) }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('student.editPaymentHistory', $payment->point_subscription_history_id) }}"><i class="fa fa-book mr-2"></i>確認・編集</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{ $paymentHistoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                @else
                                    <data-empty></data-empty>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
