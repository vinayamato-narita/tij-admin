@extends('layouts.default')
@section('title', 'セットコース新規作成')
@section('content')
    <course-set-add
            :create-url="{{json_encode(route('course.setStore'))}}"
            :list-course-url="{{json_encode(route('course.index'))}}"
            :tag="{{json_encode($tag)}}"
    >

    </course-set-add>
@endsection
