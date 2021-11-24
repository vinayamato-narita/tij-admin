@extends('layouts.default')
@section('title', 'create test')
@section('content')
    <create-test
    	:url-action="{{json_encode(route('test.store'))}}" :url-test-list="{{json_encode(route('test.index'))}}" :test-types="{{json_encode($testTypes)}}" 
    ></create-test>
@endsection
