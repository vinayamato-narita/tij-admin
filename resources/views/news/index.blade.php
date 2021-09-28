@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'お知らせ一覧')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        お知らせ一覧
                    </h5>
                </div>
                <div class="pull-right mrb-5">
                    <a href="{{ route('news.create') }}" class="btn btn-primary pull-right"
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
                                        <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('news.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                    </div>
                                    
                                </div>
                                @if(!$newsList->isEmpty())
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-center width-130">@sortablelink('public_flag', 'トップ表示')</th>
                                                    <th class="text-center min-width-150">@sortablelink('updated_at', '日時')</th>
                                                    <th class="text-center min-width-150">@sortablelink('news_subject_ja', '対象')</th>
                                                    <th class="text-center min-width-120">@sortablelink('news_title', 'タイトル')</th>
                                                    <th class="text-center min-width-120">@sortablelink('news_body', '内容')</th>
                                                    <th class="w-100"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($newsList as $index => $news)
                                                    <tr class="eachRow">
                                                        <td class="text-center status">{{ $news->public_flag == 0 ? "非表示" : "表示" }}</td>
                                                        <td class="text-center">{{ $news->updated_at }}</td>
                                                        <td class="text-center">{{ $news->news_subject_ja }}</td>
                                                        <td class="text-center">{{ $news->news_title }}</td>
                                                        <td class="text-center">{{ $news->news_body }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('news.show', $news->id) }}"><i class="fa fa-book mr-2"></i>確認・編集</a>
                                                                    </li>
                                                                    <li>
                                                                        <change-status-news :url-action="{{ json_encode(route('changeStatusNews', $news->id)) }}" :status="{{ $news->public_flag }}"></change-status-news>
                                                                    </li>
                                                                    <li>
                                                                        <delete-item
                                                                            :delete-action="{{ json_encode(route('news.destroy', $news->id)) }}"
                                                                            :message-confirm="{{ json_encode('このお知らせを削除しますか？') }}"
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

                                    {{ $newsList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
