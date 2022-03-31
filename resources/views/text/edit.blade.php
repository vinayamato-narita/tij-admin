@extends('layouts.default')
@section('title', 'テキスト編集')
@section('content')
    <text-edit
            :update-url = "{{json_encode(route('text.update', $lessonText->lesson_text_id))}}"
            :list-text-url = "{{json_encode(route('text.index'))}}"
            :detail-text-url = "{{json_encode(route('text.show', $lessonText->lesson_text_id))}}"
            :lesson-text ="{{json_encode($lessonText)}}"
            :delete-action="{{ json_encode(route('text.destroy',  $lessonText->lesson_text_id)) }}"
            :get-files-url ="{{json_encode(route('files.getFiles'))}}"
            :file-type ="{{json_encode(\App\Enums\FileTypeEnum::asArray())}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
    >

    </text-edit>
@endsection
