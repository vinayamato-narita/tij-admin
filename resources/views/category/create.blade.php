@extends('layouts.default')
@section('title', 'コースカテゴリ新規作成')
@section('content')
    <category-add
            :create-url="{{json_encode(route('category.store'))}}"
            :list-category-url="{{json_encode(route('category.index'))}}"
    >

    </category-add>
@endsection
