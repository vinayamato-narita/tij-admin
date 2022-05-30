@extends('layouts.default')
@section('title', '法人グループコース登録')
@section('content')
    <group-course-user-import :error-message="{{json_encode($errorMessage)}}" :show-list="{{json_encode($showList)}}" :data-import="{{json_encode(!empty($dataImport) ? $dataImport : new stdClass)}}"  :url-save="{{json_encode(route('courseGroupUser.saveImport'))}}"></group-course-user-import>
@endsection

