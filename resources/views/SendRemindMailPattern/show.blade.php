@extends('layouts.default')
@section('title', 'リマインドメール情報表示')
@section('content')
    <remind-mail-show
            :edit-remind-mail-url = "{{json_encode(route('remindmail.edit', $remindMail->id))}}"
            :remind-mail ="{{json_encode($remindMail)}}"
    >

    </remind-mail-show>
@endsection