@extends('layouts.default')
@section('title', 'グループレッスンスケジュール管理')
@section('content')
    <group-schedule :url-get-data="{{ json_encode(route('getDataLessonSchedule')) }}"></group-schedule>
@endsection

