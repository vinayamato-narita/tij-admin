@extends('layouts.default')
@section('title', 'リマインドメール多言語編集')
@section('content')
    <edit-lang-remind-mail
        :url-action="{{json_encode(route('updateLangRemindMail'))}}" :url-remind-detail="{{json_encode(route('remindmail.show', $remindMail->send_remind_mail_pattern_id))}}" :remind-mail="{{ json_encode($remindMail) }}"
    ></edit-lang-remind-mail>
@endsection
