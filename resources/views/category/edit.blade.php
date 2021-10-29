@extends('layouts.default')
@section('title', 'コースカテゴリ編集')
@section('content')
    <category-edit
            :update-url = "{{json_encode(route('category.update', $category->category_id))}}"
            :detail-category-url = "{{json_encode(route('category.show', $category->category_id))}}"
            :category ="{{json_encode($category)}}"
    >

    </category-edit>
@endsection
