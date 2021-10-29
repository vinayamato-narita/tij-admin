@extends('layouts.default')
@section('title', '講師情報編集')
@section('content')
    <teacher-edit
            :time-zones = "{{json_encode($timeZones)}}"
            :update-url = "{{json_encode(route('teacher.update', $teacher->teacher_id))}}"
            :list-teacher-url = "{{json_encode(route('teacher.index'))}}"
            :detail-teacher-url = "{{json_encode(route('teacher.show', $teacher->teacher_id))}}"
            :teacher ="{{json_encode($teacher)}}"
            :delete-action="{{ json_encode(route('teacher.destroy',  $teacher->teacher_id)) }}"
    >

    </teacher-edit>
@endsection
