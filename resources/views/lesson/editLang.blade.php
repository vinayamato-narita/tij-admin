@extends('layouts.default')
@section('title', 'レッスン多言語編集')
@section('content')
    <edit-lang-lesson
            :url-action="{{json_encode(route('lesson.updateLang'))}}"
            :url-lesson-detail="{{json_encode(route('lesson.show', $lesson->lesson_id))}}"
            :lesson-info="{{ json_encode($lessonInfo) }}"
            :lesson="{{ json_encode($lesson) }}"
            :lang="{{json_encode($lang)}}"
    ></edit-lang-lesson>
@endsection
