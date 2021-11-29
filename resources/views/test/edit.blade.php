@extends('layouts.default')
@section('title', 'テスト編集')
@section('content')
    <edit-test
    	:url-action="{{json_encode(route('test.update', $testInfo->test_id))}}" 
    	:url-test-show="{{json_encode(route('test.show', $testInfo->test_id))}}" 
    	:test-types="{{json_encode($testTypes)}}" 
    	:test-info="{{ json_encode($testInfo) }}"
    ></edit-test>
@endsection
