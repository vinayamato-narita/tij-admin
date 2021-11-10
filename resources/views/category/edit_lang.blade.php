@extends('layouts.default')
@section('title', 'コースカテゴリ多言語編集')
@section('content')
    <edit-lang-category
            :url-action="{{json_encode(route('category.updateLang'))}}"
            :url-category-detail="{{json_encode(route('category.show', $category->category_id))}}"
            :category-info="{{ json_encode($categoryInfo) }}"
            :category="{{ json_encode($category) }}"
            :lang="{{json_encode($lang)}}"
    ></edit-lang-category>
@endsection
