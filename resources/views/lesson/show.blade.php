@extends('layouts.default')
@section('content')
    <lesson-show
            :list-lesson-url = "{{json_encode(route('lesson.index'))}}"
            :edit-lesson-url = "{{json_encode(route('lesson.edit', $lesson->id))}}"
            :lesson ="{{json_encode($lesson)}}"
            :detail-lesson-url = "{{json_encode(route('lesson.show', $lesson->id))}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :register-url ="{{json_encode(route('lesson.registerTextLesson', $lesson->id))}}"
            :list-text-lesson-url ="{{json_encode(route('lesson.textLesson', $lesson->id))}}"




    >

    </lesson-show>
@endsection
