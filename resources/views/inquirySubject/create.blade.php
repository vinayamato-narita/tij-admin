@extends('layouts.default')
@section('title', '問い合わせ件名新規作成')
@section('content')
    <create-inquiry-subject
    	:url-action="{{json_encode(route('inquirySubject.store'))}}" :url-inquiry-subject-list="{{json_encode(route('inquirySubject.index'))}}" 
    ></create-inquiry-subject>
@endsection
