@php
    use App\Components\DateTimeComponent;
@endphp
@extends('layouts.default')
@section('title', 'レッスン履歴詳細')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            レッスン履歴詳細
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">レッスン履歴情報</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >講師名</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $lesson->teacher_name }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >学習者名</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $lesson->student_name }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >学習者番号</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $lesson->student_id }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >レッスン日時</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ DateTimeComponent::getDate($lesson->lesson_date) . " " . DateTimeComponent::getStartEndTime($lesson->lesson_starttime, $lesson->lesson_endtime) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >コース名</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $lesson->course_name }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >レッスン名</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $lesson->lesson_name }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >テキスト名</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $lesson->lesson_text_name }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >評価（学習者→先生）</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            @php
                                                $rating = ($lesson->teacher_rating + $lesson->teacher_attitude + $lesson->teacher_punctual + $lesson->teacher_attitude)/4;
                                                $text = ($rating == 0) ? __('-') : round($rating, 2);
                                            @endphp
                                            {{ $text }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >レッスンに対する感想（学習者)</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $lesson->comment_from_student_to_office }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >コメント（先生→学習者）</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $lesson->comment_from_teacher_to_student }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >管理者のコメント</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $lesson->comment_from_admin_to_student }}
                                        </div>
                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group">
                                        <div class="text-center">
                                            <a href="{{ route('teacher.lessonHistory', $teacherId) }}" class="btn btn-default w-100">閉じる</a>
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
