@extends('layouts.default')
@section('title', 'FAQ編集')
@section('content')
    <edit-faq
    	:url-action="{{ json_encode(route('faq.update', $faqInfo->faq_id)) }}" :url-faq-detail="{{json_encode(route('faq.show', $faqInfo->faq_id))}}"
    	:faq-info="{{ json_encode($faqInfo) }}" :faq-categories="{{json_encode($faqCategories)}}" :delete-action="{{ json_encode(route('faq.destroy', $faqInfo->faq_id)) }}" :message-confirm="{{ json_encode('このFAQを削除しますか？') }}" :url-redirect="{{ json_encode(route('faq.index')) }}"
    ></edit-faq>
@endsection
