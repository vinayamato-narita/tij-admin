@extends('layouts.guest')

@section('content')
<reset-password
  :data-token="{{ json_encode($dataToken) }}"
  :url-login="{{ json_encode(route('login.index')) }}"
  :url-action="{{json_encode(route('changePassword'))}}"
></reset-password>
@endsection
