@php
    use App\Components\SearchQueryComponent;
    use App\Enums\InquiryFlag;
@endphp

@extends('layouts.default')
@section('title', '問い合わせ履歴一覧')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        問い合わせ履歴一覧
                    </h5>
                </div>
                <div class="pull-right mrb-5">
                    @if ($adminSystem)
                        <a href="{{ route('exportInquiry', $request['search_input'] ?? "") }}" class="btn btn-primary pull-right"
                            ><i class="las la-plus"></i>CSV出力
                        </a>
                    @endif
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
                                        <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('inquiry.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                    </div>
                                </div>
                                @if(!$inquiryList->isEmpty())
                                    {{ $inquiryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('id', '問合せ番号')</th>
                                                    <th class="text-left min-width-150">@sortablelink('inquiry_date', '日時')</th>
                                                    <th class="text-left min-width-150">@sortablelink('inquiry_subject', '問い合わせ件名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('student_id', '学習者番号')</th>
                                                    <th class="text-left min-width-120">@sortablelink('student_name', '名前')</th>
                                                    <th class="text-left min-width-120">@sortablelink('j_student_email', 'メールアドレス')</th>
                                                    <th class="text-left min-width-120">@sortablelink('inquiry_flag', '対応状況')</th>
                                                    <th class="w-100"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($inquiryList as $index => $inquiry)
                                                    <tr class="eachRow">
                                                        <td class="text-left">{{ $inquiry->inquiry_id }}</td>
                                                        <td class="text-left">{{ $inquiry->inquiry_date }}</td>
                                                        <td class="text-left">{{ $inquiry->inquiry_subject }}</td>
                                                        <td class="text-left">{{ $inquiry->student_id }}</td>
                                                        <td class="text-left">{{ $inquiry->student_name ?? "" }}</td>
                                                        <td class="text-left">{{ $inquiry->j_student_email }}</td>
                                                        <td class="text-left">{{ InquiryFlag::getDescription($inquiry->inquiry_flag) }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('inquiry.edit', $inquiry->inquiry_id) }}"><i class="fa fa-edit mr-2"></i>編集</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{ $inquiryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
