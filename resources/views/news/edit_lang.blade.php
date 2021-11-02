@extends('layouts.default')
@section('title', 'お知らせ多言語編集')
@section('content')
    <edit-lang-news
        :url-action="{{json_encode(route('updateLangNews'))}}" :url-news-detail="{{json_encode(route('news.show', $newsInfo->news_id))}}" :news-info="{{ json_encode($newsInfo) }}"
    ></edit-lang-news>
@endsection
