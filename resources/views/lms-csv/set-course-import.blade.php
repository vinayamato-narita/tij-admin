@extends('layouts.default')
@section('title', '一括登録（法人・セットコース）')
@section('content')
    <lms-set-course-import
    	:url-action="{{json_encode(route('lmsCsv.uploadSetCourseCsv'))}}"
    	:url-import-csv="{{json_encode(route('lmsCsv.importSetCourseCsv'))}}"
    	:student-id="{{ STUDENT_ID }}"
    	:course-id="{{ COURSE_ID }}"
    	:course-begin-month="{{ COURSE_BEGIN_MONTH }}"
    	:order-date="{{ ORDER_DATE }}"
    	:start-date="{{ START_DATE }}"
    	:begin-date="{{ BEGIN_DATE }}"
    	:expire-date="{{ EXPIRE_DATE }}"
    	:management-number="{{ MANAGEMENT_NUMBER }}"
    ></lms-set-course-import>
@endsection
