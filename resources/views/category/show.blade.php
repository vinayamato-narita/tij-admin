@extends('layouts.default')
@section('title', 'コースカテゴリ情報表示')
@section('content')
    <category-show
            :edit-category-url = "{{json_encode(route('category.edit', $category->category_id))}}"
            :category ="{{json_encode($category)}}"
            :detail-category-url = "{{json_encode(route('category.show', $category->category_id))}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :register-url ="{{json_encode(route('category.registerCourse', $category->category_id))}}"
            :list-course-url ="{{json_encode(route('category.course', $category->category_id))}}"

    >

    </category-show>
@endsection
