@extends('layouts.default')
@section('title', '管理ユーザ編集')
@section('content')
    <edit-admin
    	:url-action="{{ json_encode(route('admin.update', $adminInfo->id)) }}" :url-admin-detail="{{json_encode(route('admin.show', $adminInfo->id))}}"
    	:admin-info="{{ json_encode($adminInfo) }}"
    ></edit-admin>
@endsection
