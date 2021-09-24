@extends('layouts.default')
@section('title', '問い合わせ件名編集')
@section('content')
    <edit-inquiry-subject
    	:url-action="{{ json_encode(route('inquirySubject.update', $inquirySubjectInfo->id)) }}" :url-inquiry-subject-detail="{{json_encode(route('inquirySubject.show', $inquirySubjectInfo->id))}}"
    	:inquiry-subject-info="{{ json_encode($inquirySubjectInfo) }}" :delete-action="{{ json_encode(route('inquirySubject.destroy', $inquirySubjectInfo->id)) }}" :message-confirm="{{ json_encode('この問い合わせ件名を削除しますか？') }}" :url-redirect="{{ json_encode(route('inquirySubject.index')) }}"
    ></edit-inquiry-subject>
@endsection
