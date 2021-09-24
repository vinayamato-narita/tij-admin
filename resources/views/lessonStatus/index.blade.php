@php
use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'レッスン状況')
@section('content')
<lesson-status-index :url-get-data="{{ json_encode(route('getDataLessonStatus')) }}" :num-row="{{ json_encode($numRow) }}" :data-time="{{ json_encode($dataTime) }}"></lesson-status-index>
@endsection