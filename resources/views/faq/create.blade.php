@extends('layouts.default')
@section('title', 'FAQ新規作成')
@section('content')
    <create-faq
    	:url-action="{{json_encode(route('faq.store'))}}" :url-faq-list="{{json_encode(route('faq.index'))}}" :faq-categories="{{json_encode($faqCategories)}}" 
    ></create-faq>
@endsection
