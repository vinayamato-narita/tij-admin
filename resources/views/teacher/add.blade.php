@extends('layouts.default')
@section('title', '講師新規作成')

@section('content')
    <teacher-add
    :time-zones = "{{json_encode($timeZones)}}"
    :create-url = "{{json_encode(route('teacher.store'))}}"
    :list-teacher-url = "{{json_encode(route('teacher.index'))}}"
    >

    </teacher-add>
@endsection
