@extends('layouts.default')
@section('title', 'グループコースユーザインポート')
@section('content')
    <student-import :error-message="{{json_encode($errorMessage)}}" :show-list="{{json_encode($showList)}}" :data-import="{{json_encode(!empty($dataImport) ? $dataImport : new stdClass)}}"   :url-save="{{json_encode(route('courseGroupUser.importStudent'))}}" ></student-import>
@endsection

