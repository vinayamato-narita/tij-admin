@extends('layouts.default')
@section('title', 'レッスン新規作成')
@section('content')
    <lesson-add
            :create-url="{{json_encode(route('lesson.store'))}}"
            :list-lesson-url="{{json_encode(route('lesson.index'))}}"
    >

    </lesson-add>
@endsection
