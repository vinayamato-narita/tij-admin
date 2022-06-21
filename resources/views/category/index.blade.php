@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'コースカテゴリ一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            コースカテゴリ一覧



                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                        <a href="{{ route('category.create') }}" class="btn btn-primary "
                        ><i class="las la-plus"></i>  新規作成
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
                                            <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('category.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>

                                        </div>
                                    </div>
                                    @if(!$categoryList->isEmpty())
                                        {{ $categoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-left width-130 white-space">@sortablelink('order_num', ' 表示順')</th>
                                                    <th class="text-left min-width-150 white-space">@sortablelink('category_name', ' カテゴリ名')</th>
                                                    <th class="text-left min-width-120 white-space"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($categoryList as $index => $c)
                                                    <tr>
                                                        <td class="text-left">{{ $c->order_num }}</td>

                                                        <td class="text-left ">
                                                            {{ $c->category_name }}</td>
                                                        <td class="text-right">
                                                            <div class="btn-group ">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right ">

                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('category.show', $c->category_id) }}"><i class="fa fa-info mr-2"></i>情報</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $categoryList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
