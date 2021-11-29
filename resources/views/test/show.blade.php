@extends('layouts.default')
@section('title', 'テスト情報表示')
@section('content')
    <test-show
            :edit-test-url = "{{json_encode(route('test.edit', $test->test_id))}}"
            :test ="{{json_encode($test)}}"
            :add-question-url="{{json_encode(route('test.addQuestion', $test->test_id))}}"
    >

    </test-show>
@endsection
