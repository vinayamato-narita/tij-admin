@extends('layouts.default')
@section('title', 'メディア編集')
@section('content')
    <edit-file
    	:url-action="{{json_encode(route('file.updateFile', $fileInfo->file_id))}}" 
    	:url-file-list="{{json_encode(route('file.index'))}}" 
    	:file-info="{{ json_encode($fileInfo) }}" 
    ></edit-file>
@endsection
