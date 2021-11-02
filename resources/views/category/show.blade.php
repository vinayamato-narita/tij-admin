@extends('layouts.default')
@section('title', 'コースカテゴリ情報表示')
@section('content')
    <category-show
            :edit-category-url = "{{json_encode(route('category.edit', $category->id))}}"
            :category ="{{json_encode($category)}}"
            :detail-category-url = "{{json_encode(route('category.show', $category->id))}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :register-url ="{{json_encode(route('category.registerCourse', $category->id))}}"
            :list-course-url ="{{json_encode(route('category.course', $category->id))}}"

    >

    </category-show>
@endsection
