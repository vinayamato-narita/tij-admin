@extends('layouts.default')
@section('title', 'コメント編集')
@section('content')
    <student-edit-comment
    	:url-action="{{json_encode(route('student.updateComment'))}}" 
    	:url-student-comment-list="{{json_encode(route('student.commentList', $commentInfo->student_id))}}" 
    	:comment-info="{{json_encode($commentInfo)}}" 
    ></student-edit-comment>
@endsection
