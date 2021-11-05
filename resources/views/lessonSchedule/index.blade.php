@extends('layouts.default')
@section('title', 'スケジュール管理')
@section('content')
    <lesson-schedule :url-get-data="{{ json_encode(route('getDataLessonSchedule')) }}" :url-register-multi-lesson="{{ json_encode(route('registerMultiLesson')) }}" :lesson-timing="{{ json_encode($lessonTiming) }}"></lesson-schedule>
@endsection

