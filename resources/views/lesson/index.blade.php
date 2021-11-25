@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'レッスン一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            レッスン一覧

                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                        <a href="{{ route('lesson.create') }}" class="btn btn-primary pull-right"
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
                                            <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('lesson.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                        </div>
                                    </div>
                                    @if(!$lessonList->isEmpty())
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('display_order', ' 表示順')</th>
                                                    <th class="text-left min-width-150">@sortablelink('lesson_id', ' レッスンID')</th>
                                                    <th class="text-left min-width-120">@sortablelink('is_test_lesson', ' テストあり/なし')</th>
                                                    <th class="text-left min-width-120">@sortablelink('lesson_name', ' レッスン名')</th>
                                                    <th class="text-left min-width-120">@sortablelink('lesson_description', ' 説明')</th>
                                                    <th class="text-left min-width-120"></th>
                                                    <th class="text-left min-width-120"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($lessonList as $index => $lesson)
                                                    <tr>
                                                        <td class="text-left">{{ $lesson->display_order }}</td>
                                                        <td class="text-left">{{ $lesson->lesson_id }}</td>
                                                        <td class="text-left">{{ $lesson->is_test_lesson ? "あり" : "なし" }}</td>
                                                        <td class="text-left">{{ $lesson->lesson_name }}</td>
                                                        <td class="text-left">{{ $lesson->lesson_description }}</td>
                                                        <td class="text-left "></td>
                                                        <td>
                                                            <div class="btn-group" style="float:right;">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('lesson.show', $lesson->lesson_id) }}"><i class="fa fa-info mr-2"></i>情報</a>
                                                                    </li>
                                                                    <li>
                                                                        <delete-item
                                                                                :delete-action="{{ json_encode(route('lesson.destroy',  $lesson->lesson_id)) }}"
                                                                                :message-confirm="{{ json_encode('このレッスンを削除しますか？') }}"
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

                                        {{ $lessonList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
