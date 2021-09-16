@extends('layouts.default')
@section('content')
    <lesson-show
            :list-lesson-url = "{{json_encode(route('lesson.index'))}}"
            :edit-lesson-url = "{{json_encode(route('lesson.edit', $lesson->lesson_id))}}"
            :lesson ="{{json_encode($lesson)}}"
    >

    </lesson-show>
@endsection
