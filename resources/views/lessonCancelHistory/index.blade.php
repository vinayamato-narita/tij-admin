@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'レッスンキャンセル履歴')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            レッスンキャンセル履歴


                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
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
                                        <cancel-history-search-multi :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('lessonCancelHistory.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></cancel-history-search-multi>
                                    </div>
                                    @if(!$historyList->isEmpty())
                                        {{ $historyList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('cancel_date', ' キャンセル日時')</th>
                                                    <th class="text-left min-width-150">@sortablelink('lesson_date', ' レッスン日')</th>
                                                    <th class="text-left min-width-120">レッスン開始時間    </th>
                                                    <th class="text-left min-width-120">@sortablelink('teacher.teacher_name', ' 講師名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('student_id', ' 学習者番号')</th>
                                                    <th class="text-left min-width-120">@sortablelink('student.student_name', ' 学習者名')</th>
                                                    <th class="text-left min-width-120"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($historyList as $index => $history)
                                                    <tr>
                                                        <td class="text-left">{{ $history->cancel_date }}</td>
                                                        <td class="text-left">{{ $history->lesson_date }}</td>
                                                        <td class="text-left">{{ $history->lesson_starttime }}</td>
                                                        <td class="text-left">{{ $history->teacher->teacher_name }}</td>
                                                        <td class="text-left">{{ $history->student_id }}</td>
                                                        <td class="text-left">{{ $history->student->student_name }}</td>

                                                        <td class="text-left "></td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $historyList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
