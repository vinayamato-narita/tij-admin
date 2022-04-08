@extends('layouts.default')
@section('title', 'テスト問題編集')
@section('content')
    <test-edit-question
            :test="{{json_encode($test)}}"
            :test-types="{{json_encode(\App\Enums\TestType::asSelectArray())}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :get-files-url ="{{json_encode(route('files.getFiles'))}}"
            :file-type ="{{json_encode(\App\Enums\FileTypeEnum::asArray())}}"
            :url-test-detail="{{json_encode(route('test.show', $test->test_id))}}"
            :update-question-url="{{json_encode(route('test.editQuestionUpdate', [ $test->test_id, $testQuestion->test_question_id ]))}}"
            :tags="{{json_encode($tags)}}"
            :create-tag-url="{{json_encode(route('test.createTag', $test->test_id))}}"
            :test-question="{{json_encode($testQuestion)}}"
            :test-categories="{{json_encode($testCategories)}}"
            :is-has-test-result="{{json_encode($isHasTestResult)}}"
            :check-navigation-url="{{json_encode(route('test.checkNavigation', $test->test_id))}}"
            :index="{{json_encode($index)}}"




    ></test-edit-question>
@endsection
