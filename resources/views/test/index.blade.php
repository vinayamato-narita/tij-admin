@php
    use App\Components\SearchQueryComponent;
    use App\Enums\TestType;
@endphp

@extends('layouts.default')
@section('title', 'テスト一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            テスト一覧

                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                        <a href="{{ route('test.create') }}" class="btn btn-primary pull-right"
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
                                            <page-size :page-size="{{ json_encode(PAGE_SIZE_LIMIT) }}"
                                                       :page-limit="{{ $pageLimit }}"></page-size>
                                        </div>
                                        <div class="col-md-10">
                                            <input-search :page-limit="{{ $pageLimit }}"
                                                          :url="{{ json_encode(route('test.index')) }}"
                                                          :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                        </div>
                                    </div>
                                    @if(!$testList->isEmpty())
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-left min-width-150 white-space">@sortablelink('test_id',
                                                        'テストID')
                                                    </th>
                                                    <th class="text-left min-width-120 white-space">@sortablelink('test_name', '
                                                        テスト名')
                                                    </th>
                                                    <th class="text-left min-width-120 white-space">
                                                        @sortablelink('test_description', ' 説明')
                                                    </th>
                                                    <th class="text-left min-width-120 white-space">@sortablelink('test_type', '
                                                        テスト種別')
                                                    </th>
                                                    <th class="text-left min-width-120"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($testList as $index => $test)
                                                    <tr>
                                                        <td class="text-left">{{ $test->test_id }}</td>
                                                        <td class="text-left">{{ $test->test_name }}</td>

                                                        <td class="text-left">
                                                            @if(!empty($test->test_description))
                                                            <nl2br tag="span"
                                                                   :text="{{json_encode($test->test_description )}}"
                                                            />
                                                            @endif
                                                        </td>

                                                        <td class="text-left">{{ TestType::getDescription($test->test_type) }}</td>

                                                        <td>
                                                            <div class="btn-group" style="float:right;">
                                                                <button class="btn btn-primary dropdown-toggle"
                                                                        type="button" data-toggle="dropdown"
                                                                        aria-haspopup="true" aria-expanded="false">操作選択
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                           href="{{ route('test.show', $test->test_id) }}"><i
                                                                                    class="fa fa-info mr-2"></i>情報</a>
                                                                    </li>
                                                                    <li>
                                                                        <delete-item
                                                                                :delete-action="{{ json_encode(route('test.destroy',  $test->test_id)) }}"
                                                                                :message-confirm="{{ json_encode('このテストを削除しますか？') }}"
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

                                        {{ $testList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
