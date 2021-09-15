

@extends('layouts.default')
@section('content')
    <text-add
            :create-url = "{{json_encode(route('text.store'))}}"
            :list-text-url = "{{json_encode(route('text.index'))}}"
    >

    </text-add>
@endsection
