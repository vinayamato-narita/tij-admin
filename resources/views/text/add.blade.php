

@extends('layouts.default')
@section('title', 'テキスト新規作成')
@section('content')
    <text-add
            :create-url = "{{json_encode(route('text.store'))}}"
            :list-text-url = "{{json_encode(route('text.index'))}}"
    >

    </text-add>
@endsection
