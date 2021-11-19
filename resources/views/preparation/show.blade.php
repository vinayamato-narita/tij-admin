@extends('layouts.default')
@section('title', '予習情報表示')
@section('content')
    <preparation-show
            :edit-preparation-url = "{{json_encode(route('preparation.edit', $preparation->preparation_id))}}"
            :preparation ="{{json_encode($preparation)}}"
    >

    </preparation-show>
@endsection
