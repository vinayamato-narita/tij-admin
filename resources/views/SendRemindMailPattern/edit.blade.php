@extends('layouts.default')
@section('title', 'リマインドメール編集')
@section('content')
    <remind-mail-edit
            :detail-remind-mail-url = "{{json_encode(route('remindmail.show', $remindMail->id))}}"
            :update-remind-mail-url = "{{json_encode(route('remindmail.update', $remindMail->id))}}"
            :remind-mail ="{{json_encode($remindMail)}}"
            :enum="{{json_encode($enum)}}"
    >

    </remind-mail-edit>
@endsection
