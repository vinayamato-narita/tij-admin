@extends('layouts.default')
@section('title', 'レッスン編集')
@section('content')
    <lesson-edit
            :update-url = "{{json_encode(route('lesson.update', $lesson->lesson_id))}}"
            :list-lesson-url = "{{json_encode(route('lesson.index'))}}"
            :detail-lesson-url = "{{json_encode(route('lesson.show', $lesson->lesson_id))}}"
            :lesson ="{{json_encode($lesson)}}"
            :delete-action="{{ json_encode(route('lesson.destroy',  $lesson->lesson_id)) }}"
    >

    </lesson-edit>
@endsection
