@extends('layouts.default')
@section('title', 'ZOOM連携設定')
@section('content')
    <zoom-setting-edit
        :update-url="{{json_encode(route('zoomSetting.update'))}}"
        :zoom-setting="{{json_encode($zoomSetting)}}"
    ></zoom-setting-edit>
@endsection