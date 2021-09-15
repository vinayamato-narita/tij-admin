@extends('layouts.default')
@section('content')
    <text-edit
            :update-url = "{{json_encode(route('text.update', $lessonText->lesson_text_id))}}"
            :list-text-url = "{{json_encode(route('text.index'))}}"
            :detail-text-url = "{{json_encode(route('teacher.show', $lessonText->lesson_text_id))}}"
            :lesson-text ="{{json_encode($lessonText)}}"
            :delete-action="{{ json_encode(route('text.destroy',  $lessonText->lesson_text_id)) }}"
    >

    </text-edit>
@endsection
