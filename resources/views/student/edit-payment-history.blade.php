@extends('layouts.default')
@section('title', '支払い履歴編集')
@section('content')
    <edit-payment-history
    	:url-action="{{json_encode(route('student.updatePaymentHistory'))}}" 
    	:url-destroy-payment-history="{{json_encode(route('student.destroyPaymentHistory'))}}" 
    	:url-payment-history-list="{{json_encode(route('student.paymentHistoryList', $paymentInfo->student_id))}}" 
    	:payment-info="{{json_encode($paymentInfo)}}" 
    ></edit-payment-history>
@endsection
