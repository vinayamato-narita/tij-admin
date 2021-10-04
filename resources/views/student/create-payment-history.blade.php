@extends('layouts.default')
@section('title', '支払い履歴新規作成')
@section('content')
    <create-payment-history
    	:url-action="{{json_encode(route('student.storePaymentHistory'))}}" 
    	:url-payment-history-list="{{json_encode(route('student.paymentHistoryList', $studentInfo->id))}}" 
    	:student-info="{{json_encode($studentInfo)}}" 
    ></create-payment-history>
@endsection
