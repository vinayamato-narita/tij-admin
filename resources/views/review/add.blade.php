<?php
use App\Enums\FileTypeEnum;

?>

@extends('layouts.default')
@section('title', '復習新規作成')
@section('content')
    <review-add
            :create-url = "{{json_encode(route('review.store'))}}"
            :list-review-url = "{{json_encode(route('review.index'))}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :get-files-url ="{{json_encode(route('files.getFiles'))}}"
            :file-type ="{{json_encode(\App\Enums\FileTypeEnum::asArray())}}"

    >

    </review-add>
@endsection
