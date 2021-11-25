@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', '予習一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            予習一覧

                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                        <a href="{{ route('preparation.create') }}" class="btn btn-primary pull-right"
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
                                            <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('preparation.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                        </div>
                                    </div>
                                    @if(!$preparationList->isEmpty())
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('display_order', ' 表示順')</th>
                                                    <th class="text-left min-width-150">@sortablelink('preparation_id', '予習ID')</th>
                                                    <th class="text-left min-width-120">@sortablelink('preparation_name', ' 予習名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('preparation_description', ' 説明')</th>
                                                    <th class="text-left min-width-120"></th>
                                                    <th class="text-left min-width-120"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($preparationList as $index => $preparation)
                                                    <tr>
                                                        <td class="text-left">{{ $preparation->display_order }}</td>
                                                        <td class="text-left">{{ $preparation->preparation_id }}</td>
                                                        <td class="text-left">{{ $preparation->preparation_name }}</td>
                                                        <td class="text-left">{{ $preparation->preparation_description }}</td>
                                                        <td class="text-left "></td>
                                                        <td>
                                                            <div class="btn-group" style="float:right;">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('preparation.show', $preparation->preparation_id) }}"><i class="fa fa-info mr-2"></i>情報</a>
                                                                    </li>
                                                                    <li>
                                                                        <delete-item
                                                                                :delete-action="{{ json_encode(route('preparation.destroy',  $preparation->preparation_id)) }}"
                                                                                :message-confirm="{{ json_encode('この予習を削除しますか？') }}"
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

                                        {{ $preparationList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
