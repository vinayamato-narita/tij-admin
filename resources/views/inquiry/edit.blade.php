@extends('layouts.default')
<?php
use App\Enums\InquiryFlag;
?>
@section('title', '問い合わせ詳細')
@section('content')
    <edit-inquiry
    	:url-update-inquiry="{{json_encode(route('inquiry.update', $inquiryInfo->id))}}" :url-inquiry-list="{{json_encode(route('inquiry.index'))}}" :inquiry-info="{{ json_encode($inquiryInfo) }}" :url-change-inquiry-flag="{{ json_encode(route('changeInquiryFlag')) }}" :not-support="{{ InquiryFlag::NOTSUPPORTED }}"
    ></edit-inquiry>
@endsection
