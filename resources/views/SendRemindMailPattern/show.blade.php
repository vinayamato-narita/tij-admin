<?php
use App\Enums\LangType;
?>
@extends('layouts.default')
@section('title', 'リマインドメール情報表示')
@section('content')
    <remind-mail-show
            :edit-remind-mail-url = "{{json_encode(route('remindmail.edit', $remindMail->send_remind_mail_pattern_id))}}"
            :remind-mail ="{{json_encode($remindMail)}}"
            :edit-lang-en-url ="{{json_encode(route('editLangRemindMail', [$remindMail->send_remind_mail_pattern_id, LangType::EN]))}}"
            :edit-lang-zh-url ="{{json_encode(route('editLangRemindMail', [$remindMail->send_remind_mail_pattern_id, LangType::ZH]))}}"
            :mail-En-info="{{json_encode($mailEnInfo)}}"
            :mail-Zh-info="{{json_encode($mailZhInfo)}}"
    >

    </remind-mail-show>
@endsection
