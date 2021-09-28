@extends('layouts.default')
@section('title', 'セットコース編集')
@section('content')
    <course-set-edit
            :update-url = "{{json_encode(route('course.setUpdate', $course->course_id))}}"
            :list-course-url = "{{json_encode(route('course.index'))}}"
            :detail-course-url = "{{json_encode(route('course.show', $course->course_id))}}"
            :tags ="{{json_encode($tags)}}"
            :course ="{{json_encode($course)}}"
            :detail-url="{{json_encode(route('course.setShow', $course->course_id))}}"

    >

    </course-set-edit>
@endsection
