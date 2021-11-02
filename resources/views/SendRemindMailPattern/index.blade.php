@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'リマインドメール一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            リマインドメール一覧


                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                    </div>
                </div>
                <div class="clear"></div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-2">
                                            <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}" :page-limit="{{ $pageLimit }}"></page-size>

                                        </div>
                                            <div class="col-md-10">
                                                <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('remindmail.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>

                                            </div>

                                    </div>
                                    @if(!$remindMailList->isEmpty())
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-center width-130">@sortablelink('sendRemindMailTiming.send_remind_mail_timing_type_name', ' メール種類')</th>
                                                    <th class="text-center min-width-150">@sortablelink('timing_minutes', ' 送信タイミング')</th>
                                                    <th class="text-center min-width-120"></th>
                                                    <th class="text-center min-width-120"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($remindMailList as $index => $rm)
                                                    <tr>
                                                        <td class="text-center">{{ $rm->sendRemindMailTiming->send_remind_mail_timing_type_name }}</td>

                                                        <td class="text-center ">
                                                            {{ $rm->timing_minutes == 0 ? '-' : $rm->timing_minutes }}</td>
                                                        <td></td>
                                                        <td class="text-right">
                                                            <div class="btn-group ">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right ">

                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('remindmail.show', $rm->id) }}"><i class="fa fa-info mr-2"></i>情報</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $remindMailList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
