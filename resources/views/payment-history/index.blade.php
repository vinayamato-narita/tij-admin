@php
    use App\Components\SearchQueryComponent;
    use App\Enums\PaymentWay;
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
                    @if ($adminSystem)
                        <a href="{{ route('paymentHistory.export') }}" class="btn btn-primary pull-right"
                            ><i class="las la-plus"></i> CSV出力
                        </a>
                    @endif
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
                                        <label>売上 : </label> 
                                        {{ number_format($total_amount) }}円
                                    </div>
                                    <div class="col-md-2 fwb pt-7">
                                        <label> 消費税 : </label>
                                        {{ number_format($total_tax) }}円
                                    </div>
                                    <div class="col-md-5">
                                        <payment-history-search 
                                            :page-limit="{{ $pageLimit }}"
                                            :url="{{ json_encode(route('paymentHistory.index')) }}" 
                                            :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"
                                            :payment-ways="{{ json_encode($paymentWays) }}"
                                            ></payment-history-search>
                                    </div>
                                </div>
                                @if(!$paymentList->isEmpty())
                                    {{ $paymentList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('order_id', 'オーダーID')</th>
                                                    <th class="text-left min-width-150">@sortablelink('student_id', '学習者番号')</th>
                                                    <th class="text-left min-width-150">@sortablelink('payment_date', '受注日時')</th>
                                                    <th class="text-left min-width-150">@sortablelink('begin_date', '受講開始日')</th>
                                                    <th class="text-left min-width-150">@sortablelink('point_expire_date', '有効期限日')</th>
                                                    <th class="text-left min-width-150">@sortablelink('item_name', '商品名')</th>
                                                    <th class="text-left min-width-150">@sortablelink('j_student_name', '学習者名')</th>
                                                    <th class="text-left min-width-150">@sortablelink('j_company_name', '法人名')</th>
                                                    <th class="text-left min-width-150">@sortablelink('amount', '売上')</th>
                                                    <th class="text-left min-width-150">@sortablelink('tax', '消費税')</th>
                                                    <th class="text-left min-width-150">@sortablelink('point_count', '付与回数')</th>
                                                    <th class="text-left min-width-150">@sortablelink('payment_way', '支払方法')</th>
                                                    <th class="text-left min-width-150">@sortablelink('j_receive_payment_date', '入金日')</th>
                                                    <th class="text-left min-width-150">@sortablelink('j_payment_term', '支払期限')</th>
                                                    
                                                    <th class="w-100"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($paymentList as $index => $payment)
                                                    <tr>
                                                        <td class="text-left">{{ $payment->order_id }}</td>
                                                        <td class="text-left">{{ $payment->student_id }}</td>
                                                        <td class="text-left">{{ $payment->payment_date }}</td>
                                                        <td class="text-left">{{ $payment->begin_date }}</td>
                                                        <td class="text-left">
                                                            @if($payment->course_type !== \App\Enums\CourseTypeEnum::GROUP_COURSE)
                                                                {{ $payment->point_expire_date }}
                                                            @endif
                                                        </td>
                                                        <td class="text-left">{{ $payment->item_name }}</td>
                                                        <td class="text-left">{{ $payment->j_student_name }}</td>
                                                        <td class="text-left">{{ $payment->j_company_name }}</td>
                                                        <td class="text-left">{{ number_format($payment->amount) }}</td>
                                                        <td class="text-left">{{ number_format($payment->tax) }}</td>
                                                        <td class="text-left">{{ $payment->point_count }}</td>
                                                        <td class="text-left">{{ PaymentWay::getDescription($payment->payment_way) }}</td>
                                                        <td class="text-left">{{ $payment->j_receive_payment_date }}</td>
                                                        <td class="text-left">{{ $payment->j_payment_term }}</td>

                                                        <td class="text-left">
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('paymentHistory.edit', $payment->id) }}"><i class="fa fa-edit mr-2"></i>編集</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{ $paymentList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
