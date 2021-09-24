@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'コメント一覧')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        コメント一覧
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
                                    <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}" :page-limit="{{ $pageLimit }}"></page-size>
                                    <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('comment.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                </div>
                                @if(!$commentList->isEmpty())
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">レッスン日時</th>
                                                    <th class="text-center">@sortablelink('student_id', '生徒番号')</th>
                                                    <th class="text-center">@sortablelink('student_nickname', '生徒のニックネーム')</th>
                                                    <th class="text-center">講師のニックネーム</th>
                                                    <th class="text-center">@sortablelink('course_name', 'コース')</th>
                                                    <th class="text-center">@sortablelink('teacher_rating', '教え方')</th>
                                                    <th class="text-center">@sortablelink('teacher_attitude', '態度')</th>
                                                    <th class="text-center">@sortablelink('teacher_punctual', 'わかりやすさ')</th>
                                                    <th class="text-center">@sortablelink('skype_voice_rating_from_student', 'Skypeの音声')</th>
                                                    <th class="text-center">@sortablelink('comment_from_student_to_office', 'レッスンに対する感想')</th>
                                                    <th class="text-center">@sortablelink('skype_voice_rating_from_teacher', '出（0）欠（1）')</th>
                                                    <th class="text-center">@sortablelink('comment_from_teacher_to_student', '生徒へのコメント')</th>
                                                    <th class="text-center">@sortablelink('comment_from_teacher_to_office', '事務局へのコメント')</th>
                                                    <th class="text-center">@sortablelink('note_from_student_to_teacher', '講義メモ')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($commentList as $index => $item)
                                                @php
                                                    $lessonTime = "";
                                                    if (!empty($item['lesson_starttime']) && !empty($item['lesson_endtime'])) {
                                                        $start = strtotime($item['lesson_starttime']);
                                                        $end = strtotime($item['lesson_endtime']);
                                                        $lessonTime = date('Y-m-d H:i', $start).'~'.date('H:i', $end);   
                                                    }
                                                @endphp
                                                    <tr class="row-comment">
                                                        <td class="text-center">{{ $lessonTime }}</td>
                                                        <td class="text-center">{{ $item['student_id'] }}</td>
                                                        <td class="text-center">{{ $item['student_nickname'] }}</td>
                                                        <td class="text-center">{{ $item['teacher_nickname'] }}</td>
                                                        <td class="text-center">{{ $item['course_name'] }}</td>
                                                        <td class="text-center">{{ $item['teacher_rating'] }}</td>
                                                        <td class="text-center">{{ $item['teacher_attitude'] }}</td>
                                                        <td class="text-center">{{ $item['teacher_punctual'] }}</td>
                                                        <td class="text-center">{{ Str::limit($item['skype_voice_rating_from_student'], 20) }}</td>
                                                        <td class="text-center">{{ Str::limit($item['comment_from_student_to_office'], 20) }}</td>
                                                        <td class="text-center">{{ Str::limit($item['skype_voice_rating_from_teacher'], 20) }}</td>
                                                        <td class="text-center">{{ Str::limit($item['comment_from_teacher_to_student'], 20) }}</td>
                                                        <td class="text-center">{{ Str::limit($item['comment_from_teacher_to_office'], 20) }}</td>
                                                        <td class="text-center">{{ Str::limit($item['note_from_student_to_teacher'], 20) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{ $commentList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
