@extends('layouts.default')
@section('title', '管理ユーザ編集')
@section('content')
    <edit-admin
    	:url-action="{{ json_encode(route('admin.update', $adminInfo->id)) }}" :url-admin-detail="{{json_encode(route('admin.show', $adminInfo->id))}}"
    	:admin-info="{{ json_encode($adminInfo) }}" :delete-action="{{ json_encode(route('admin.destroy', $adminInfo->id)) }}"
        :message-confirm="{{ json_encode('この管理ユーザを削除しますか？') }}" :url-redirect="{{ json_encode(route('admin.index')) }}"
    ></edit-admin>
@endsection
