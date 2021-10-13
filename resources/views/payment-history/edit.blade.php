@extends('layouts.default')
@section('title', '支払い履歴編集')
@section('content')
    <edit-history-payment
    	:url-action="{{ json_encode(route('paymentHistory.update')) }}" 
    	:url-payment-history-list="{{json_encode(route('paymentHistory.index'))}}"
    	:payment-info="{{ json_encode($paymentInfo) }}"
    ></edit-history-payment>
@endsection
