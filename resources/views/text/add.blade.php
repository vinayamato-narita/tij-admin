<?php
use App\Enums\FileTypeEnum;

?>

@extends('layouts.default')
@section('title', 'テキスト新規作成')
@section('content')
    <text-add
            :create-url = "{{json_encode(route('text.store'))}}"
            :list-text-url = "{{json_encode(route('text.index'))}}"
            :get-files-url ="{{json_encode(route('files.getFiles'))}}"
            :file-type ="{{json_encode(\App\Enums\FileTypeEnum::asArray())}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"

    >

    </text-add>
@endsection
