<?php
 use App\Enums\FileTypeEnum;

?>

@extends('layouts.default')
@section('title', '予習新規作成')
@section('content')
    <preparation-add
            :create-url = "{{json_encode(route('preparation.store'))}}"
            :list-preparation-url = "{{json_encode(route('preparation.index'))}}"
            :page-size-limit  ="{{json_encode(PAGE_SIZE_LIMIT)}}"
            :get-files-url ="{{json_encode(route('files.getFiles'))}}"
            :file-type ="{{json_encode(\App\Enums\FileTypeEnum::asArray())}}"

    >

    </preparation-add>
@endsection
