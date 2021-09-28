@extends('layouts.default')
@section('title', 'テキスト情報表示')
@section('content')
    <text-show
            :list-text-url = "{{json_encode(route('text.index'))}}"
            :edit-text-url = "{{json_encode(route('text.edit', $lessonText->id))}}"
            :lesson-text ="{{json_encode($lessonText)}}"
    >

    </text-show>
@endsection
