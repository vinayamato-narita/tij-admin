@extends('layouts.default')
@section('title', 'コメント編集')
@section('content')
    <show-lesson-history
    	:url-action="{{json_encode(route('student.updateLessonsHistory'))}}" 
    	:url-cancel-lesson-history="{{json_encode(route('student.cancelLessonsHistory'))}}" 
    	:url-student-lesson-history-list="{{json_encode(route('student.lessonHistoryList', $lessonHistoryInfo->student_id))}}" 
    	:lesson-history-info="{{json_encode($lessonHistoryInfo)}}" 
    ></show-lesson-history>
@endsection
