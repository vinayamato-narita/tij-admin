@extends('layouts.default')
@section('title', 'お知らせ新規作成')
@section('content')
    <create-news
    	:url-action="{{json_encode(route('news.store'))}}" :url-news-list="{{json_encode(route('news.index'))}}" :news-subjects="{{json_encode($newsSubjects)}}" 
    ></create-news>
@endsection
