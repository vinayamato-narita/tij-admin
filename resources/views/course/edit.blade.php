@extends('layouts.default')
@section('content')
    <course-edit
            :update-url = "{{json_encode(route('course.update', $course->course_id))}}"
            :list-course-url = "{{json_encode(route('course.index'))}}"
            :detail-course-url = "{{json_encode(route('course.show', $course->course_id))}}"
            :tags ="{{json_encode($tag)}}"
            :course ="{{json_encode($course)}}"
            :detail-url="{{json_encode(route('course.show', $course->course_id))}}"
    >

    </course-edit>
@endsection
