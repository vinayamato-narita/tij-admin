@extends('layouts.default')
@section('title', 'Zoomアカウント編集')
@section('content')
    <zoom-account-edit
            :update-url = "{{json_encode(route('zoomAccount.update', $zoomAccount->zoom_account_id))}}"
            :list-zoom-account-url = "{{json_encode(route('zoomAccount.index'))}}"
            :zoom-account ="{{json_encode($zoomAccount)}}"
    >

    </zoom-account-edit>
@endsection
