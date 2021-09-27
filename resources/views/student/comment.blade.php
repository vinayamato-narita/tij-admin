@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'コメント履歴一覧')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        コメント履歴一覧
                    </h5>
                </div>
                <div class="pull-right mrb-5">
                    <a href="{{ route('student.createComment', $studentInfo->id) }}" class="btn btn-primary pull-right"
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
                                    <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}" :page-limit="{{ $pageLimit }}"></page-size>
                                    <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('student.commentList', $studentInfo->id)) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                </div>
                                @if(!$commentList->isEmpty())
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-center width-130">@sortablelink('teacher_nickname', '講師のニックネーム')</th>
                                                    <th class="text-center min-width-150">@sortablelink('created_at', '作成日')</th>
                                                    <th class="text-center min-width-120">@sortablelink('updated_at', '更新日')</th>
                                                    <th class="text-center min-width-120" style="width: 40%">@sortablelink('comment', 'コメント')</th>
                                                    <th class="w-100"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($commentList as $index => $comment)
                                                    <tr>
                                                        <td class="text-center">{{ $comment->teacher_nickname }}</td>
                                                        <td class="text-center">{{ $comment->created_at }}</td>
                                                        <td class="text-center">{{ $comment->updated_at }}</td>
                                                        <td class="text-center">{{ $comment->comment }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('student.editComment', $comment->id) }}"><i class="fa fa-book mr-2"></i>確認・編集</a>
                                                                    </li>
                                                                    <li>
                                                                        <delete-item
                                                                            :delete-action="{{ json_encode(route('student.destroyComment', $comment->id)) }}"
                                                                            :message-confirm="{{ json_encode('このコメントを削除しますか？') }}"
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

                                    {{ $commentList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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