@extends('layouts.default')
@section('title', 'テスト問題追加')
@section('content')
    <test-add-question
            :test="{{json_encode($test)}}"
            :test-types="{{json_encode(\App\Enums\TestType::asSelectArray())}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :get-files-url ="{{json_encode(route('files.getFiles'))}}"
            :file-type ="{{json_encode(\App\Enums\FileTypeEnum::asArray())}}"
            :url-test-detail="{{json_encode(route('test.show', $test->test_id))}}"
            :create-question-url="{{json_encode(route('test.addQuestionPost', $test->test_id))}}"
            :tags="{{json_encode($tags)}}"
            :create-tag-url="{{json_encode(route('test.createTag', $test->test_id))}}"

    ></test-add-question>
@endsection
