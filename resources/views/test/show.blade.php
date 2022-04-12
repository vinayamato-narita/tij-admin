@extends('layouts.default')
@section('title', 'テスト情報表示')
@section('content')
    <test-show
            :edit-test-url = "{{json_encode(route('test.edit', $test->test_id))}}"
            :test ="{{json_encode($test)}}"
            :add-question-url="{{json_encode(route('test.addQuestion', $test->test_id))}}"
            :detail-test-url="{{json_encode(route('test.show', $test->test_id))}}"
            :list-question-attach-url="{{json_encode(route('test.listQuestionAttach', $test->test_id))}}"
            :list-question-attach-update-url="{{json_encode(route('test.listQuestionAttachUpdate', $test->test_id))}}"
            :is-has-test-result="{{json_encode($isHasTestResult)}}"
            :preview-url="{{json_encode(route('test.preview', $test->test_id))}}"
    >

    </test-show>
@endsection
