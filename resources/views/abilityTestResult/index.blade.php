@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', ' 受験済実力テスト一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            受験済実力テスト一覧

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
                                        <ability-test-result-input-search-multi
                                                :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('abilityTestResult.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"
                                        >

                                        </ability-test-result-input-search-multi>

                                    </div>
                                    @if(!$testResultList->isEmpty())
                                        {{ $testResultList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-left min-width-150">@sortablelink('student.student_id', '学習者ID')</th>
                                                    <th class="text-left min-width-150">@sortablelink('student.student_name', '学習者名')</th>
                                                    <th class="text-left min-width-150">@sortablelink('test.test_name', '実力テスト名')</th>
                                                    <th class="text-left min-width-150">@sortablelink('test_start_time', '受講日時')</th>
                                                    <th class="text-left min-width-150">@sortablelink('test_comment.comment_start_time', '評価開始日時')</th>
                                                    <th class="text-left min-width-150">@sortablelink('custom_status', 'ステータス')</th>
                                                    <th class="text-left min-width-120"></th>
                                                    <th class="text-left min-width-120"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($testResultList as $index => $testResult)
                                                    <tr>
                                                        <td class="text-left">{{ $testResult->student->student_id }}</td>
                                                        <td class="text-left">{{ $testResult->student->student_name }}</td>
                                                        <td class="text-left">{{ $testResult->test->test_name }}</td>
                                                        <td class="text-left">{{ \Carbon\Carbon::parse($testResult->test_start_time)->format('Y/m/d H:i') }}</td>
                                                        @if($testResult->test_comment !== null)
                                                        <td class="text-left">{{ \Carbon\Carbon::parse($testResult->test_comment->comment_start_time)->format('Y/m/d H:i') }}</td>
                                                        @else
                                                            <td class="text-left"></td>
                                                        @endif

                                                        @if($testResult->test_comment === null || $testResult->test_comment->comment_start_time === null)
                                                            <td class="text-left"> 評価待ち</td>
                                                        @elseif($testResult->test_comment->comment_start_time !== null && $testResult->test_comment->comment_end_time === null)
                                                            <td class="text-left">評価中</td>
                                                        @else
                                                            <td class="text-left">済</td>

                                                        @endif


                                                        <td class="text-left">
                                                            <div class="btn-group" style="float:right;">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                            <a class="dropdown-item"
                                                                               href="{{route('abilityTestResult.show', $testResult->test_result_id)}}"><i
                                                                                        class="fa fa-info mr-2"></i>情報</a>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $testResultList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
