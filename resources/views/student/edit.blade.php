@php
    use App\Enums\StudentEntryType;
    use App\Enums\LmsUserEnum;
@endphp
@extends('layouts.default')
@section('title', '学習者情報編集')
@section('content')
    <edit-student
    	:url-action="{{json_encode(route('student.update', $studentInfo->student_id))}}" 
    	:url-student-list="{{json_encode(route('student.index'))}}" 
    	:student-info="{{ json_encode($studentInfo) }}" 
    	:delete-action="{{ json_encode(route('student.destroy', $studentInfo->student_id)) }}" 
    	:message-confirm="{{ json_encode('この学習者を削除しますか？') }}"
    	:url-update-password="{{json_encode(route('student.updatePassword'))}}" 
		:is_tmp_entry="{{json_encode(StudentEntryType::getDescription($studentInfo->is_tmp_entry))}}"
        :is-lms-user="{{ json_encode(LmsUserEnum::CORPORATION) }}"
    ></edit-student>
@endsection
