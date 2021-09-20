@extends('layouts.default')
@section('title', 'FAQ多言語編集')
@section('content')
    <edit-lang-faq
        :url-action="{{json_encode(route('updateLangFaq'))}}" :url-faq-detail="{{json_encode(route('faq.show', $faqInfo->id))}}" :faq-info="{{ json_encode($faqInfo) }}"
    ></edit-lang-faq>
@endsection
