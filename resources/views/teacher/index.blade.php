@php
    use App\Components\SearchQueryComponent;
    use App\Enums\TeacherShowFlag;
@endphp

@extends('layouts.default')
@section('title', '講師情報一覧')

@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            講師情報一覧
                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                        @if ($adminSystem)
                            <a href="{{ route('exportTeacher') }}" class="btn btn-primary pull-right"
                                ><i class="las la-plus"></i> CSV出力
                            </a>
                        @endif
                    </div>
                    <div class="pull-right mrb-5" style="margin-right: 10px">
                        <a href="{{ route('teacher.create') }}" class="btn btn-primary pull-right"
                        ><i class="las la-plus"></i>新規作成
                        </a>
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
                                            <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('teacher.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                        </div>
                                    </div>
                                    @if(!$teacherList->isEmpty())
                                        {{ $teacherList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-left width-130 white-space">@sortablelink('display_order', ' 表示順')</th>
                                                    <th class="text-left min-width-150 white-space">@sortablelink('teacher_name', ' 講師名')</th>
                                                    <th class="text-left min-width-120 white-space">@sortablelink('teacher_nickname', ' ニックネーム')</th>
                                                    <th class="text-left min-width-150 white-space">@sortablelink('teacher_email', ' メールアドレス')</th>
                                                    <th class="text-left min-width-150 white-space">@sortablelink('last_login_date', ' 最終ログイン日時')</th>
                                                    <th class="text-left min-width-150 white-space">@sortablelink('show_flag', ' ステータス')</th>
                                                    <th class="text-left min-width-150 white-space"></th>
                                                    <th class="text-left min-width-150 white-space"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($teacherList as $index => $teacher)
                                                    <tr>
                                                        <td class="text-left">{{ $teacher->display_order }}</td>
                                                        <td class="text-left">{{ $teacher->teacher_name }}</td>
                                                        <td class="text-left">{{ $teacher->teacher_nickname }}</td>
                                                        <td class="text-left">{{ $teacher->teacher_email }}</td>
                                                        <td class="text-left">{{ $teacher->last_login_date }}</td>
                                                        <td class="text-left">{{ TeacherShowFlag::getDescription($teacher->show_flag) }}</td>
                                                        <td class="text-left"></td>
                                                        <td class="text-right">
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="mailto:{{ $teacher->teacher_email }}"><i class="fa fa-envelope mr-2"></i>メール送信</a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('teacher.lessonHistory', $teacher->teacher_id) }}"><i class="fa fa-book mr-2"></i> レッスン履歴</a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('teacher.show', $teacher->teacher_id) }}"><i class="fa fa-info mr-2"></i>情報</a>
                                                                    </li>
                                                                    <li>
                                                                        <delete-item
                                                                                :delete-action="{{ json_encode(route('teacher.destroy',  $teacher->teacher_id)) }}"
                                                                                :message-confirm="{{ json_encode('この講師を削除しますか？') }}"
                                                                        >
                                                                        </delete-item>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $teacherList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
