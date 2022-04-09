@extends('layouts.default')
@section('title', '受講回数履歴詳細')
@section('content')
    <show-point-history
    	:url-action="{{json_encode(route('student.updatePointHistory'))}}" 
    	:url-cancel-point-history="{{json_encode(route('student.cancelPointHistory'))}}" 
    	:url-student-point-history-list="{{json_encode(route('student.pointHistoryList', $pointHistoryInfo->student_id))}}" 
    	:point-history-info="{{json_encode($pointHistoryInfo)}}" 
    ></show-point-history>
@endsection
