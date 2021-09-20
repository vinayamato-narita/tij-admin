@extends('layouts.default')
@section('title', 'お知らせ編集')
@section('content')
    <edit-news
    	:url-action="{{json_encode(route('news.update', $newsInfo->id))}}" :url-news-detail="{{json_encode(route('news.show', $newsInfo->id))}}" :news-subjects="{{json_encode($newsSubjects)}}" :news-info="{{ json_encode($newsInfo) }}" :delete-action="{{ json_encode(route('news.destroy', $newsInfo->id)) }}" :message-confirm="{{ json_encode('このお知らせを削除しますか？') }}" :url-redirect="{{ json_encode(route('news.index')) }}"
    ></edit-news>
@endsection
