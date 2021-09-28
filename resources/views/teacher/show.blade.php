@extends('layouts.default')
@section('title', '講師情報表示')
@section('content')
    <teacher-show
            :list-teacher-url = "{{json_encode(route('teacher.index'))}}"
            :edit-teacher-url = "{{json_encode(route('teacher.edit', $teacher->id))}}"
            :detail-teacher-url = "{{json_encode(route('teacher.show', $teacher->id))}}"
            :teacher ="{{json_encode($teacher)}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :lesson-list-url ="{{json_encode(route('teacher.lesson', $teacher->id))}}"
            :register-url ="{{json_encode(route('teacher.registerLesson',  $teacher->id))}}"
    >

    </teacher-show>
@endsection
