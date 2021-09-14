@extends('layouts.default')
@section('title', '管理ユーザ一覧')
@section('content')
    <create-admin
    	:url-action="{{json_encode(route('admin.store'))}}" :url-admin-list="{{json_encode(route('admin.index'))}}"
    ></create-admin>
@endsection
