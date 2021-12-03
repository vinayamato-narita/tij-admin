<?php
use App\Enums\LangType;
?>
@extends('layouts.default')
@section('title', 'レッスン情報表示')
@section('content')
    <lesson-show
            :list-lesson-url = "{{json_encode(route('lesson.index'))}}"
            :edit-lesson-url = "{{json_encode(route('lesson.edit', $lesson->lesson_id))}}"
            :lesson ="{{json_encode($lesson)}}"
            :detail-lesson-url = "{{json_encode(route('lesson.show', $lesson->lesson_id))}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :register-url ="{{json_encode(route('lesson.registerTextLesson', $lesson->lesson_id))}}"
            :list-text-lesson-url ="{{json_encode(route('lesson.textLesson', $lesson->lesson_id))}}"
            :list-preparation-url ="{{json_encode(route('lesson.preparation'))}}"
            :register-preparation-url ="{{json_encode(route('lesson.registerPreparation'))}}"
            :list-review-url ="{{json_encode(route('lesson.review'))}}"
            :register-review-url ="{{json_encode(route('lesson.registerReview'))}}"
            :edit-lang-en-url ="{{json_encode(route('lesson.editLang', [$lesson->lesson_id, LangType::EN]))}}"
            :edit-lang-zh-url ="{{json_encode(route('lesson.editLang', [$lesson->lesson_id, LangType::ZH]))}}"
            :list-confirm-test-url ="{{json_encode(route('lesson.confirmTest', $lesson->lesson_id))}}"
            :register-confirm-test-url="{{json_encode(route('lesson.registerConfirmTest', $lesson->lesson_id))}}"


    >

    </lesson-show>
@endsection
