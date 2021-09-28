@extends('layouts.default')
@section('title', 'セットコース情報表示')

@section('content')
    <course-set-show
            :list-course-url = "{{json_encode(route('course.index'))}}"
            :edit-course-url = "{{json_encode(route('course.setEdit', $course->course_id))}}"
            :course ="{{json_encode($course)}}"
    >

    </course-set-show>
@endsection
