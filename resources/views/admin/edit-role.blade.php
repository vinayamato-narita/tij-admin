@extends('layouts.default')
@section('title', '権限編集')
@section('content')
    <edit-role
    	:url-action="{{json_encode(route('admin.updateRole'))}}" 
    	:url-admin-list="{{json_encode(route('admin.index'))}}" 
    	:admin-info="{{ json_encode($adminInfo) }}" 
    	:admin-role="{{ json_encode($adminRole) }}" 
    ></edit-role>
@endsection
