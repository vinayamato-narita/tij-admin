@extends('layouts.default')
@section('title', '予習編集')
@section('content')
    <preparation-edit
            :detail-preparation-url = "{{json_encode(route('preparation.show', $preparation->preparation_id))}}"
            :update-url = "{{json_encode(route('preparation.update', $preparation->preparation_id))}}"
            :preparation ="{{json_encode($preparation)}}"
            :delete-action="{{ json_encode(route('preparation.destroy',  $preparation->preparation_id)) }}"
            :list-preparation-url = "{{json_encode(route('preparation.index'))}}"

    >

    </preparation-edit>
@endsection
