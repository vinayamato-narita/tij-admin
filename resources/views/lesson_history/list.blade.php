@php
    use App\Components\SearchQueryComponent;
    use App\Components\DateTimeComponent;
@endphp

@extends('layouts.default')
@section('title', 'レッスン履歴一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            レッスン履歴一覧
                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                        @if ($adminSystem)
                            <a href="{{ route('lesson-history.export')}}" class="btn btn-primary pull-right"><i class="las la-plus"></i> CSV出力
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

                                        </div>
                                        <div class="col-md-2 fwb pt-7">

                                        </div>
                                        <div class="col-md-5">
                                            <lesson-history-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('lesson-history.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}" :check-month="{{json_encode(true)}}"></lesson-history-search>
                                        </div>
                                    </div>
                                    @if(!$lessonHistories->isEmpty())
                                        {{ $lessonHistories->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-center width-130">@sortablelink('lesson_date', 'レッスン日')</th>
                                                    <th class="text-center min-width-150">@sortablelink('lesson_starttime', 'レッスン時間')</th>
                                                    <th class="text-center min-width-150">@sortablelink('course_name', 'コース名')</th>
                                                    <th class="text-center min-width-150">@sortablelink('lesson_name', 'レッスン名')</th>
                                                    <th class="text-center min-width-150">@sortablelink('lesson_text_name', 'テキスト名')</th>
                                                    <th class="text-center min-width-150">@sortablelink('student_id', '学習者番号')</th>
                                                    <th class="text-center min-width-150">@sortablelink('student_name', '学習者名')</th>
                                                    <th style="width: 90px"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($lessonHistories as $index => $lesson)
                                                    <tr>
                                                        <td class="text-center">{{ DateTimeComponent::getDate($lesson->lesson_date) }}</td>
                                                        <td class="text-center">{{ DateTimeComponent::getStartEndTime($lesson->lesson_starttime, $lesson->lesson_endtime) }}</td>
                                                        <td class="text-center">{{ $lesson->course_name }}</td>
                                                        <td class="text-center">{{ $lesson->lesson_name }}</td>
                                                        <td class="text-center">{{ $lesson->lesson_text_name }}</td>
                                                        <td class="text-center">{{ $lesson->student_id }}</td>
                                                        <td class="text-center">{{ $lesson->student_name }}</td>
                                                        <td>
                                                            <a href="{{ route('teacher.lessonHistoryDetail', $lesson->lesson_history_id) }}" class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i>詳細</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $lessonHistories->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
