@extends('layouts.default')
@section('title', 'メディア新規作成')
@section('content')
    <create-file
    	:url-action="{{json_encode(route('file.store'))}}" :url-file-list="{{json_encode(route('file.index'))}}"
    	:option-upload-file="{{json_encode($optionUploadFile)}}"
    	:file-base-media="{{json_encode($fileBaseMedia)}}"
    ></create-file>
@endsection
