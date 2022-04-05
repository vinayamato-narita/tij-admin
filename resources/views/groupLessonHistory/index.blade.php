@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'グループレッスン履歴一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            グループレッスン履歴一覧
                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                        <a href="{{ route('exportGroupLesson') }}" class="btn btn-primary pull-right"
                        ><i class="las la-plus"></i> CSV出力
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
                                        <div class="col-md-2">
                                            <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}" :page-limit="{{ $pageLimit }}"></page-size>
                                        </div>
                                        <group-lesson-history-input-search-multi
                                                :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('group_lesson_history.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"
                                        >

                                        </group-lesson-history-input-search-multi>
                                    </div>
                                    @if(!$groupLessonHistoryList->isEmpty())
                                        {{ $groupLessonHistoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('course.course_name', ' コース名')</th>
                                                    <th class="text-left min-width-150">@sortablelink('lesson.lesson_name', ' レッスン名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('teacher.teacher_name', ' 教師名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('student_point_history_count', ' 受講者数')</th>
                                                    <th class="text-left min-width-120">@sortablelink('lesson_histories_count', ' 出席者数')</th>
                                                    <th class="text-left min-width-120">@sortablelink('lesson_starttime', ' レッスン日時')</th>
                                                    <th class="text-left min-width-120"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($groupLessonHistoryList as $index => $history)
                                                    <tr>
                                                        <td class="text-left">{{ $history->course->course_name }}</td>
                                                        <td class="text-left">{{ $history->lesson->lesson_name }}</td>
                                                        <td class="text-left">{{ $history->teacher->teacher_name }}</td>
                                                        <td class="text-left">{{ $history->student_point_history_count }}</td>
                                                        <td class="text-left">{{ $history->lesson_histories_count }}</td>
                                                        <td class="text-left ">{{\Carbon\Carbon::parse($history->lesson_starttime)->format('Y/m/d H:m')}}</td>
                                                        <td>
                                                            <div class="btn-group" style="float:right;">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('groupLessonHistory.studentAttendance', $history->lesson_schedule_id) }}"><i class="fa fa-info mr-2"></i>出欠管理</a>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $groupLessonHistoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
