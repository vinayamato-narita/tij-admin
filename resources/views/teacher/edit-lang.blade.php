@extends('layouts.default')
@section('title', '講師情報多言語編集')
@section('content')
    <edit-teacher-lang
        :url-action="{{json_encode(route('teacher.updateLang'))}}" :url-teacher-detail="{{json_encode(route('teacher.show', $teacherInfo->teacher_id))}}" :teacher-info="{{ json_encode($teacherInfo) }}"
    ></edit-teacher-lang>
@endsection
