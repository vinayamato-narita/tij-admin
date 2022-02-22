@extends('layouts.default')
@section('title', '実力テスト回答詳細')
@section('content')
    <ability-test-result-answer-detail
            :test-result="{{json_encode($testResult)}}"
            :test-questions="{{json_encode($testQuestions)}}"
    >

    </ability-test-result-answer-detail>
@endsection
