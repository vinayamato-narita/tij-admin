@extends('layouts.default')
@section('title', 'コースカテゴリ編集')
@section('content')
    <category-edit
            :update-url = "{{json_encode(route('category.update', $category->id))}}"
            :detail-category-url = "{{json_encode(route('category.show', $category->id))}}"
            :category ="{{json_encode($category)}}"
    >

    </category-edit>
@endsection
