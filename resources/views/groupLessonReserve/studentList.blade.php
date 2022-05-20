@php
use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', '学習者一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            学習者一覧
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
                                            <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}"
                                                :page-limit="{{ $pageLimit }}"></page-size>
                                        </div>
                                        <input-search-group-lesson :page-limit="{{ $pageLimit }}"
                                            :url="{{ json_encode(route('groupLesson.getStudent', ['id' => $course_id])) }}"
                                            :data-query="{{ json_encode(!empty($request) ? $request->all() : new stdClass()) }}">
                                        </input-search-group-lesson>

                                    </div>
                                    @if (!$studentList->isEmpty())
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left min-width-150">@sortablelink('student_id',
                                                            ' 学習者ID')</th>
                                                        <th class="text-left min-width-150">@sortablelink('student_name',
                                                            ' 学習者名')</th>
                                                        <th class="text-left min-width-120">
                                                            @sortablelink('payment_date', ' 申込日時')</th>
                                                        <th class="text-left min-width-120">
                                                            @sortablelink('receive_payment_date', ' 支払日時')</th>
                                                        <th class="text-left min-width-120">
                                                            @sortablelink('status', ' ステータス')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($studentList as $index => $student)
                                                        <tr>
                                                            <td class="text-left">{{ $student->student_id }}</td>
                                                            <td class="text-left">
                                                                {{ $student->student_name }}</td>
                                                            <td class="text-left">{{ !empty($student->pointSubscriptionHistories[0]->payment_date) ? Carbon\Carbon::parse($student->pointSubscriptionHistories[0]->payment_date)->format('Y/m/d H:i') : '---' }}
                                                            </td>
                                                            <td class="text-left">{{ !empty($student->pointSubscriptionHistories[0]->receive_payment_date) ? Carbon\Carbon::parse($student->pointSubscriptionHistories[0]->receive_payment_date)->format('Y/m/d H:i') : '-' }}
                                                            </td>
                                                            <td class="text-left">{{ !empty($student->pointSubscriptionHistories[0]->receive_payment_date) ? '申込済' : '仮申込'}}</td>
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
