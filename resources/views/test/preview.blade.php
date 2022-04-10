@extends('layouts.preview')
@section('content')
    <preview
            :test="{{json_encode($test)}}"
            :test-questions="{{json_encode($testQuestions)}}">

    </preview>

@endsection
