@extends('layouts.default')
@section('title', 'IMPORT FORM')
@section('content')
    <import-send-mail
    	:url-action="{{json_encode(route('lmsCsv.sendMail'))}}"
    	:send-mail="{{ $sentMail }}"
    	:error-send-mail-data="{{json_encode($errorSendMailData)}}"
    	:error-send-mail-data-flag="{{ $errorSendMailDataFlag }}"
    	:student-first-name="{{ STUDENT_FIRST_NAME }}"
    	:student-last-name="{{ STUDENT_LAST_NAME }}"
    	:student-email="{{ STUDENT_EMAIL }}"
    	:project-code="{{ PROJECT_CODE }}"
    	:student-id="{{ STUDENT_ID }}"
    ></import-send-mail>
@endsection
