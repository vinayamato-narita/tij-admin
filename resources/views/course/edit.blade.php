@extends('layouts.default')
@section('title', 'コース編集')
@section('content')
    <course-edit
            :update-url = "{{json_encode(route('course.update', $course->course_id))}}"
            :list-course-url = "{{json_encode(route('course.index'))}}"
            :detail-course-url = "{{json_encode(route('course.show', $course->course_id))}}"
            :course ="{{json_encode($course)}}"
            :course-bought="{{ json_encode($courseBought) }}"
    >

    </course-edit>
@endsection
