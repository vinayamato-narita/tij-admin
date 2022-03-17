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
                                        <label>生徒番号 : </label> 
                                        {{ $studentInfo->student_id }}
                                    </div>
                                    <div class="col-md-3 fwb pt-7">
                                        <label>生徒名 : </label>
                                        {{ $studentInfo->student_name }}
                                    </div>
                                    <div class="col-md-4">
                                        <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('student.lessonHistoryList', $studentInfo->student_id)) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                    </div>
                                </div>
                                @if(!$lessonHistoryList->isEmpty())
                                    {{ $lessonHistoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-center width-130">@sortablelink('lesson_date', '日付')</th>
                                                    <th class="text-center min-width-150">@sortablelink('lesson_starttime', '時間')</th>
                                                    <th class="text-center min-width-120" style="width: 15%">@sortablelink('course_name', 'コース名')</th>
                                                    <th class="text-center min-width-120" style="width: 15%">@sortablelink('lesson_name', 'レッスン名')</th>
                                                    <th class="text-center min-width-120" style="width: 15%">@sortablelink('lesson_text_name', 'テキスト名')</th>
                                                    <th class="text-center min-width-120">@sortablelink('teacher_name', '講師名')</th>
                                                    <th class="text-center min-width-120">@sortablelink('skype_voice_rating_from_teacher', '出（0）欠（1）')</th>
                                                    <th class="text-center min-width-120">@sortablelink('set_course_id', 'セットコード')</th>
                                                    <th class="w-100"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($lessonHistoryList as $index => $lesson)
                                                    <tr>
                                                        <td class="text-center">{{ DateTimeComponent::getDate($lesson->lesson_date) }}</td>
                                                        <td class="text-center">{{ DateTimeComponent::getStartEndTime($lesson->lesson_starttime, $lesson->lesson_endtime) }}</td>
                                                        <td class="text-center">{{ $lesson->course_name }}</td>
                                                        <td class="text-center">{{ $lesson->lesson_name }}</td>
                                                        <td class="text-center">{{ $lesson->lesson_text_name }}</td>
                                                        <td class="text-center">{{ $lesson->teacher_name }}</td>
                                                        <td class="text-center">{{ $lesson->skype_voice_rating_from_teacher }}</td>
                                                        <td class="text-center">{{ $lesson->set_course_id }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('student.showLessonHistory', $lesson->lesson_history_id) }}"><i class="fa fa-book mr-2"></i>確認・編集</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{ $lessonHistoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
