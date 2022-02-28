@extends('layouts.default')
@section('title', 'Zoomアカウント新規作成')

@section('content')
    <zoom-account-add
    :create-url = "{{json_encode(route('zoomAccount.store'))}}"
    :list-zoom-account-url = "{{json_encode(route('zoomAccount.index'))}}"
    >

    </zoom-account-add>
@endsection
