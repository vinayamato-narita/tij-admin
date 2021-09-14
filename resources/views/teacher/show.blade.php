@extends('layouts.default')
@section('content')
    <teacher-show
            :list-teacher-url = "{{json_encode(route('teacher.index'))}}"
            :edit-teacher-url = "{{json_encode(route('teacher.edit', $teacher->id))}}"
            :teacher ="{{json_encode($teacher)}}"
    >

    </teacher-show>
@endsection
