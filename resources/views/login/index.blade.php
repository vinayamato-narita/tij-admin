@extends('layouts.guest')

@section('content')
<login :data="{{ json_encode([
    'request' => $request,
]) }}"></login>
@endsection
