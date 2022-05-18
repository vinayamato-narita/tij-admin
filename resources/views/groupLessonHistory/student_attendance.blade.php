@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', '出欠一覧')

@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            出欠一覧
                        </h5>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-2">
                                            <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}" :page-limit="{{ $pageLimit }}"></page-size>
                                        </div>
                                        <div class="col-md-10">
                                            <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('groupLessonHistory.studentAttendance', $id)) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                        </div>
                                    </div>
                                    @if(!$studentList->isEmpty())
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('student_id', '学習者ID')</th>
                                                    <th class="text-left min-width-150">@sortablelink('student_name', '学習者名')</th>
                                                    <th class="text-left min-width-150" style="width: 100px"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($studentList as $index => $student)
                                                    <tr>
                                                        <td class="text-left">{{ $student->student_id }}</td>
                                                        <td class="text-left">{{ $student->student_name }}</td>
                                                        <td class="text-left">
                                                            <update-student-attendance :url-action="{{ json_encode(route('groupLessonHistory.updateStudentAttendance')) }}" :status="{{ json_encode($student->student_lesson_start != null) }}" :student-info="{{ json_encode($student) }}"></update-student-attendance>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $studentList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                    @else
                                        <data-empty></data-empty>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
