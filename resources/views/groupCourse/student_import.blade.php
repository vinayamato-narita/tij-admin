@extends('layouts.default')
@section('title', 'グループコースユーザインポート')
@section('content')
    <student-import  :url-save="{{json_encode(route('courseGroupUser.importStudent'))}}" ></student-import>
@endsection

