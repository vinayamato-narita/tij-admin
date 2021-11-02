@extends('layouts.default')
@section('title', 'テキスト編集')
@section('content')
    <text-edit
            :update-url = "{{json_encode(route('text.update', $lessonText->id))}}"
            :list-text-url = "{{json_encode(route('text.index'))}}"
            :detail-text-url = "{{json_encode(route('text.show', $lessonText->id))}}"
            :lesson-text ="{{json_encode($lessonText)}}"
            :delete-action="{{ json_encode(route('text.destroy',  $lessonText->id)) }}"
    >

    </text-edit>
@endsection
