@php
use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'レッスン状況')
@section('content')
<lesson-status-index :url-get-data="{{ json_encode(route('getDataLessonStatus')) }}" :num-row="{{ json_encode($numRow) }}" :url-lesson-infomation-detail-export-csv="{{ json_encode(route('lessonInfomationDetailExportCsv')) }}" :url-lesson-infomation-status-export-csv="{{ json_encode(route('lessonInfomationStatusExportCsv')) }}" :url-action="{{ json_encode(route('updateLessonStatus')) }}" :url-copy-setting-lesson-free="{{ json_encode(route('copySettingLessonFree')) }}"></lesson-status-index>
@endsection