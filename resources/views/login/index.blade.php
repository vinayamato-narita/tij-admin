@extends('layouts.guest')

@section('content')
<login :data="{{ json_encode([
    'request' => $request,
]) }}" :url-forgot-password="{{ json_encode(route('forgotPassword')) }}"></login>
@endsection
