@php
use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'グループレッスンコース申込一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            グループレッスンコース申込一覧
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
                                            :url="{{ json_encode(route('groupLessonReserves.index')) }}"
                                            :data-query="{{ json_encode(!empty($request) ? $request->all() : new stdClass()) }}">
                                        </input-search-group-lesson>

                                    </div>
                                    @if (!$courseList->isEmpty())
                                        {{ $courseList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left min-width-150">@sortablelink('course_name',
                                                            ' コース名')</th>
                                                        <th class="text-left min-width-150">@sortablelink('is_for_lms',
                                                            ' 法人/個人')</th>
                                                        <th class="text-left min-width-120">
                                                            @sortablelink('min_reserve_count', ' 最小開催人数')</th>
                                                        <th class="text-left min-width-120">
                                                            @sortablelink('max_reserve_count', ' 最大申込人数')</th>
                                                        <th class="text-left min-width-120">
                                                            @sortablelink('point_subscription_histories_count', ' 申込人数')
                                                        </th>
                                                        <th class="text-left min-width-120">@sortablelink('decide_date',
                                                            ' 開催決定日時')</th>
                                                        <th class="text-left min-width-120">
                                                            @sortablelink('reserve_end_date', ' 申込期限')</th>
                                                        <th class="text-left min-width-120">
                                                            @sortablelink('group_lesson_status', ' ステータス')</th>
                                                        <th class="text-left min-width-120"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($courseList as $index => $course)
                                                        <tr>
                                                            <td class="text-left">{{ $course->course_name }}</td>
                                                            <td class="text-left">
                                                                {{ $course->is_for_lms == 0 ? '個人' : '法人' }}</td>
                                                            <td class="text-left">{{ $course->min_reserve_count }}
                                                            </td>
                                                            <td class="text-left">{{ $course->max_reserve_count }}
                                                            </td>
                                                            <td class="text-left">
                                                                {{ $course->point_subscription_histories_count }}</td>
                                                            <td class="text-left">
                                                                {{ Carbon\Carbon::parse($course->decide_date)->format('Y/m/d H:i') }}
                                                            </td>
                                                            <td class="text-left">
                                                                {{ Carbon\Carbon::parse($course->reserve_end_date)->format('Y/m/d H:i') }}
                                                            </td>
                                                            <td class="text-left">{{ $course->group_lesson }}</td>

                                                            <td class="text-left">
                                                                <div class="btn-group" style="float:right;">
                                                                    <a href="{{ route('groupLessonReserves.show', $course->course_id) }}"
                                                                        class="btn btn-primary text-white w-65" type="button"><i
                                                                            class="fa fa-info mr-1"></i>詳細</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $courseList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
