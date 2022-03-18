@php
    use App\Components\SearchQueryComponent;
    use App\Components\DateTimeComponent;
@endphp

@extends('layouts.default')
@section('title', 'ポイント履歴一覧')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        ポイント履歴一覧
                    </h5>
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
                                        <label>生徒 ID : </label> 
                                        {{ $studentInfo->student_id }}
                                    </div>
                                    <div class="col-md-3 fwb pt-7">
                                        <label>生徒名 : </label>
                                        {{ $studentInfo->student_name }}
                                    </div>
                                    <div class="col-md-4">
                                        <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('student.pointHistoryList', $studentInfo->student_id)) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                    </div>
                                </div>
                                @if(!$pointHistoryList->isEmpty())
                                    {{ $pointHistoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-center width-130">@sortablelink('point_subscription_id', '受注番号')</th>
                                                    <th class="text-center min-width-150">@sortablelink('pay_date', '処理日')</th>
                                                    <th class="text-center min-width-120" style="width: 15%">@sortablelink('course_name', 'コース名')</th>
                                                    <th class="text-center min-width-120" style="width: 15%">@sortablelink('pay_description', '内容')</th>
                                                    <th class="text-center min-width-120" style="width: 15%">@sortablelink('point_count', '付与/消費')</th>
                                                    <th class="text-center min-width-120" style="width: 15%">@sortablelink('expire_date', '有効期限')</th>
                                                    <th class="text-center min-width-120" style="width: 15%">@sortablelink('set_course_id', 'セットコード')</th>
                                                    <th class="w-100"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pointHistoryList as $index => $point)
                                                    <tr>
                                                        <td class="text-center">{{ $point->point_subscription_id }}</td>
                                                        <td class="text-center">{{ DateTimeComponent::getDate($point->pay_date) }}</td>
                                                        <td class="text-center">{{ $point->course_name }}</td>
                                                        <td class="text-center">{{ $point->pay_description }}</td>
                                                        <td class="text-center">{{ (int)$point->point_count }}</td>
                                                        <td class="text-center">{{ DateTimeComponent::getDate($point->expire_date) }}</td>
                                                        <td class="text-center">{{ $point->set_course_id }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('student.showPointHistory', $point->student_point_history_id) }}"><i class="fa fa-book mr-2"></i>確認・編集</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{ $pointHistoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
