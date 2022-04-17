@extends('layouts.default')
@section('title', '実力テスト評価')
@section('content')
    <ability-test-result-edit
            :test-result="{{json_encode($testResult)}}"
            :analytic-list="{{json_encode($analyticList)}}"
            :test-comment="{{json_encode($testComment)}}"
            :disable-comment="{{json_encode($disableComment)}}"
            :comments="{{json_encode($comments)}}"
            :update-url="{{json_encode(route('abilityTestResult.updateTestComment', $testComment->test_comment_id))}}"
            :detail-url="{{json_encode(route('abilityTestResult.show', $testResult->test_result_id))}}"
            :answer-detail-url="{{json_encode(route('abilityTestResult.answerDetail',  $testResult->test_result_id))}}"
            :group-lesson-guide-url="{{json_encode(url('group_lesson_guide.pdf'))}}"

    >

    </ability-test-result-edit>
@endsection
