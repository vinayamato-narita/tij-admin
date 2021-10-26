@extends('layouts.default')
@section('title', '一括登録（個人・セットコース）')
@section('content')
    <csv-import
            :post-url="{{json_encode(route('csv.doImport'))}}"
            :err-msg="{{json_encode($errMsg)}}"
            :err-flag="{{json_encode($errorFlag)}}"
            :csv="{{json_encode($csv)}}"
            :is-show-table="{{json_encode($isShowTable)}}"

    ></csv-import>
@endsection

