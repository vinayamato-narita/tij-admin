@extends('layouts.default')
@section('title', '受験済実力テスト情報表示')
@section('content')
    <ability-test-result-show
            :test-result="{{json_encode($testResult)}}"
            :comments="{{json_encode($comments)}}"
    >

    </ability-test-result-show>
@endsection
