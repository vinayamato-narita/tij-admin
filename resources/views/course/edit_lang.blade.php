@extends('layouts.default')
@section('title', 'コース多言語編集')
@section('content')
    <edit-lang-course
            :url-action="{{json_encode(route('course.updateLang'))}}"
            :url-course-detail="{{json_encode(route('course.show', $course->course_id))}}"
            :course-info="{{ json_encode($courseInfo) }}"
            :course="{{ json_encode($course) }}"
            :lang="{{json_encode($lang)}}"
    ></edit-lang-course>
@endsection
