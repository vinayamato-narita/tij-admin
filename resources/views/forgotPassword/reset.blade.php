@extends('layouts.guest')

@section('content')
<reset-password
  :data="{{ json_encode([
    'token' => $token
  ]) }}"
></reset-password>
@endsection
