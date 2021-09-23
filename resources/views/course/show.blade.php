@extends('layouts.default')
@section('content')
    <course-show
            :list-course-url = "{{json_encode(route('course.index'))}}"
            :edit-course-url = "{{json_encode(route('course.edit', $course->course_id))}}"
            :course ="{{json_encode($course)}}"
            :detail-course-url = "{{json_encode(route('course.show',$course->course_id))}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :register-url ="{{json_encode(route('course.registerLesson', $course->course_id))}}"
            :list-lesson-url ="{{json_encode(route('course.lesson', $course->course_id))}}"
            :register-video-url ="{{json_encode(route('course.registerVideo', $course->course_id))}}"
            :course-video="{{json_encode($courseVideo)}}"
    >

    </course-show>
@endsection
