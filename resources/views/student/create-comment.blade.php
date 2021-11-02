@extends('layouts.default')
@section('title', 'FAQ新規作成')
@section('content')
    <student-create-comment
    	:url-action="{{json_encode(route('student.storeComment'))}}" 
    	:url-student-comment-list="{{json_encode(route('student.commentList', $studentInfo->student_id))}}" 
    	:student-info="{{json_encode($studentInfo)}}" 
    ></student-create-comment>
@endsection
