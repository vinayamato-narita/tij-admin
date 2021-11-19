

@extends('layouts.default')
@section('title', '予習新規作成')
@section('content')
    <preparation-add
            :create-url = "{{json_encode(route('preparation.store'))}}"
            :list-preparation-url = "{{json_encode(route('preparation.index'))}}"
    >

    </preparation-add>
@endsection
