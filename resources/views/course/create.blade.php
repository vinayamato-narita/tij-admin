@extends('layouts.default')
@section('title', 'コース新規作成')
@section('content')
    <course-add
            :create-url="{{json_encode(route('course.store'))}}"
            :list-course-url="{{json_encode(route('course.index'))}}"
    >

    </course-add>
@endsection
