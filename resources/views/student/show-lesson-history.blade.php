@extends('layouts.default')
@section('title', 'コメント編集')
@section('content')
    <show-lesson-history
    	:url-action="{{json_encode(route('student.updateLessonHistory'))}}" 
    	:url-cancel-lesson-history="{{json_encode(route('student.cancelLessonHistory'))}}" 
    	:url-student-lesson-history-list="{{json_encode(route('student.lessonHistoryList', $lessonHistoryInfo->student_id))}}" 
    	:lesson-history-info="{{json_encode($lessonHistoryInfo)}}" 
    ></show-lesson-history>
@endsection
