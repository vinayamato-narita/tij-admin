@extends('layouts.default')
@section('content')
    <course-set-show
            :list-course-url = "{{json_encode(route('course.index'))}}"
            :edit-course-url = "{{json_encode(route('course.edit', $course->course_id))}}"
            :course ="{{json_encode($course)}}"
    >

    </course-set-show>
@endsection
