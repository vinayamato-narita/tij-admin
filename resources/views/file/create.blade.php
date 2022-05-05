@extends('layouts.default')
@section('title', 'メディア新規作成')
@section('content')
    <create-file
    	:url-action="{{json_encode(route('file.store'))}}" :url-file-list="{{json_encode(route('file.index'))}}"
    ></create-file>
@endsection
