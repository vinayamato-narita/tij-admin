@extends('layouts.default')
@section('title', '一括登録（法人・単体コース）')
@section('content')
    <lms-csv-import
    	:url-action="{{json_encode(route('lmsCsv.uploadCsv'))}}"
    	:url-import-csv="{{json_encode(route('lmsCsv.importCsv'))}}"
    	:student-id="{{ STUDENT_ID }}"
    	:url-import-send-mail="{{json_encode(route('lmsCsv.importSendMail'))}}"
    ></lms-csv-import>
@endsection
