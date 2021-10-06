@extends('layouts.default')
@section('title', '一括登録（個人・セットコース）')
@section('content')
    <csv-import
            :post-url="{{json_encode(route('csv.doImport'))}}"
            :err-msg="{{json_encode($errMsg)}}"

    ></csv-import>
@endsection

