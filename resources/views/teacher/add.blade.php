@extends('layouts.default')
@section('title', '講師新規作成')

@section('content')
    <teacher-add
    :time-zones = "{{json_encode($timeZones)}}"
    :create-url = "{{json_encode(route('teacher.store'))}}"
    :list-teacher-url = "{{json_encode(route('teacher.index'))}}"
    :url-check-unique = "{{ json_encode(route('teacher.unique_custom_teacher_code')) }}"
    >

    </teacher-add>
@endsection
