@extends('layouts.default')
@section('title', 'グループコース申込情報表示')
@section('content')
    <group-lesson-reserve-show
            :list-group-lesson-reserve-url = "{{json_encode(route('groupLessonReserves.index'))}}"
            :list-group-lesson-student-url = "{{json_encode(route('groupLesson.getStudent', ['id' => $course->course_id]))}}"
            :course ="{{json_encode($course)}}"
    >

    </group-lesson-reserve-show>
@endsection
