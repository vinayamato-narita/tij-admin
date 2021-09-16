@extends('layouts.default')
@section('title', 'お知らせ編集')
@section('content')
    <edit-news
    	:url-action="{{json_encode(route('news.update', $newsInfo->news_id))}}" :url-news-detail="{{json_encode(route('news.show', $newsInfo->news_id))}}" :news-subjects="{{json_encode($newsSubjects)}}" :news-info="{{ json_encode($newsInfo) }}"
    ></edit-news>
@endsection
