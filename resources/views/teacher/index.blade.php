@extends('layouts.customer')

@section('content')
    <teacher-list
            :data="{{json_encode([
        'urlGetData' => route('teacher.getTeacherList'),
        'flag-show' => false
    ])}}"
            :register-url="{{json_encode(route('teacher.create'))}}"
    ></teacher-list>
@endsection
