@extends('layouts.default')
@section('content')
    <course-add
            :create-url="{{json_encode(route('course.store'))}}"
            :list-course-url="{{json_encode(route('course.index'))}}"
            :tag="{{json_encode($tag)}}"
    >

    </course-add>
@endsection
