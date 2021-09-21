@extends('layouts.default')
@section('title', 'CSV出力')
@section('content')
    <csv-export
        :url-export-csv="{{ json_encode(route('csvExportPayment')) }}"
        :url-export-lesson-history="{{ json_encode(route('csvExportLessonHistory')) }}"
        :url-export-super-grace="{{ json_encode(route('exportSuperGrace')) }}"
        :url-export-lesson-summary-process="{{ json_encode(route('exportLessonSummaryProcess')) }}"
        :url-export-student-bought-course="{{ json_encode(route('exportStudentBoughtCourse')) }}"
        :url-export-super-grace-normal="{{ json_encode(route('exportSuperGraceNormal')) }}"
    ></csv-export>
@endsection
