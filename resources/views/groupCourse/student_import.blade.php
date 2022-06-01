@extends('layouts.default')
@section('title', '法人ユーザ登録')
@section('content')
    <student-import :error-message="{{json_encode($errorMessage)}}"  :data-import="{{json_encode(!empty($dataImport) ? $dataImport : new stdClass)}}"   :url-save="{{json_encode(route('courseGroupUser.importStudent'))}}" ></student-import>
@endsection

