@extends('layouts.default')
@section('title', '問い合わせ件名多言語編集')
@section('content')
    <edit-lang-inquiry-subject
        :url-action="{{json_encode(route('updateLangInquirySubject'))}}" :url-inquiry-subject-detail="{{json_encode(route('inquirySubject.show', $inquirySubjectInfo->inquiry_subject_id))}}" :inquiry-subject-info="{{ json_encode($inquirySubjectInfo) }}"
    ></edit-lang-inquiry-subject>
@endsection
