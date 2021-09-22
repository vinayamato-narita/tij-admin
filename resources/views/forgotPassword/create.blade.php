@extends('layouts.guest')

@section('content')
<forgot-password :url-login="{{ json_encode(route('login.index')) }}" :url-action="{{json_encode(route('storeForgotPassword'))}}"></forgot-password>
@endsection
