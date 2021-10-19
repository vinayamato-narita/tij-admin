@extends('layouts.default')
@section('title', '生徒情報編集')
@section('content')
    <edit-student
    	:url-action="{{json_encode(route('student.update', $studentInfo->id))}}" 
    	:url-student-list="{{json_encode(route('student.index'))}}" 
    	:student-info="{{ json_encode($studentInfo) }}" 
    	:delete-action="{{ json_encode(route('student.destroy', $studentInfo->id)) }}" 
    	:message-confirm="{{ json_encode('この生徒を削除しますか？') }}"
    	:url-update-password="{{json_encode(route('student.updatePassword'))}}" 
    ></edit-student>
@endsection
