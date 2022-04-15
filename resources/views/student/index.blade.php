@php
    use App\Components\SearchQueryComponent;
    use App\Enums\StudentEntryType;
@endphp

@extends('layouts.default')
@section('title', '学習者情報一覧')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        学習者情報一覧
                    </h5>
                </div>
                <div class="pull-right mrb-5">
                    @if ($adminSystem)
                        <a href="{{ route('student.export') }}" class="btn btn-primary pull-right"
                            ><i class="las la-plus"></i> CSV出力
                        </a>
                    @endif
                </div>
            </div>
            <div class="clear"></div>
            <div class="fade-in" style="min-height: 1000px">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}" :page-limit="{{ $pageLimit }}"></page-size>
                                    </div>
                                    <div class="col-md-2 fwb pt-7">
                                        <label>有料会員数 : </label> 
                                        {{ $number_published }}人
                                    </div>
                                    <div class="col-md-2 fwb pt-7">
                                        <label> 無料会員数 : </label>
                                        {{ $number_unpublished }}人
                                    </div>
                                    <div class="col-md-5">
                                        <student-search 
                                            :page-limit="{{ $pageLimit }}"
                                            :url="{{ json_encode(route('student.index')) }}" 
                                            :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"
                                            ></student-search>
                                    </div>
                                </div>
                                @if(!$studentList->isEmpty())
                                    {{ $studentList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('student_id', '学習者番号')</th>
                                                    <th class="text-left min-width-150">@sortablelink('student_name', '学習者名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('student_nickname', 'ニックネーム')</th>
                                                    <th class="text-left min-width-120">@sortablelink('student_skypename', 'スカイプ名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('custom_company_name', '法人名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('all_project_code', '企業ID')</th>
                                                    <th class="text-left min-width-120">@sortablelink('all_project_company_name', '企業名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('company_code', '法人コード')</th>
                                                    <th class="text-left min-width-120">@sortablelink('create_date', '初回登録日時')</th>
                                                    <th class="text-left min-width-120">@sortablelink('last_login_date', '最終ログイン日時')</th>
                                                    <th class="text-left min-width-120">@sortablelink('last_reserve_date', '最新予約日時')</th>
                                                    <th class="text-left min-width-120">@sortablelink('first_lesson_date', '初回受講日時')</th>
                                                    <th class="text-left min-width-120">@sortablelink('lesson_count', '通算受講数')</th>
                                                    <th class="text-left min-width-120">@sortablelink('is_tmp_entry', '登録状態')</th>
                                                    <th class="text-left min-width-120">@sortablelink('course_name', '有料/無料')</th>
                                                    <th class="w-100"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($studentList as $index => $student)
                                                    <tr>
                                                        <td class="text-left">{{ $student->student_id }}</td>
                                                        <td class="text-left">{{ $student->student_name }}</td>
                                                        <td class="text-left">{{ $student->student_nickname }}</td>
                                                        <td class="text-left">{{ $student->student_skypename }}</td>
                                                        <td class="text-left">{{ $student->custom_company_name }}</td>
                                                        <td class="text-left">{{ trim($student->all_project_code, '/') }}</td>
                                                        <td class="text-left">{{ trim($student->all_project_company_name, '/') }}</td>
                                                        <td class="text-left">{{ trim($student->company_code, '/') }}</td>
                                                        <td class="text-left">{{ $student->create_date }}</td>
                                                        <td class="text-left">{{ $student->last_login_date }}</td>
                                                        <td class="text-left">{{ $student->last_reserve_date }}</td>
                                                        <td class="text-left">{{ $student->first_lesson_date }}</td>
                                                        <td class="text-left">{{ $student->lesson_count }}</td>
                                                        <td class="text-left">{{ StudentEntryType::getDescription($student->is_tmp_entry) }}</td>
                                                        <td class="text-left">{{ $student->course_name }}</td>
                                                        <td class="text-left">
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('student.edit', $student->student_id) }}"><i class="fa fa-edit mr-2"></i>編集</a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('student.lessonHistoryList', $student->student_id) }}"><i class="fa fa-book mr-2"></i>レッスン履歴</a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('student.paymentHistoryList', $student->student_id) }}"><i class="fa fa-comment mr-2"></i>支払い履歴</a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('student.pointHistoryList', $student->student_id) }}"><i class="fa fa-list-alt mr-2"></i>受講回数履歴</a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('student.commentList', $student->student_id) }}"><i class="fa fa-comment mr-2"></i>コメント履歴</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
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
