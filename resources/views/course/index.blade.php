@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', 'コース一覧')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            コース一覧

                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                        <a href="{{ route('course.create') }}" class="btn btn-primary "
                        ><i class="las la-plus"></i> コース作成
                        </a>
                        &nbsp;
                        <a href="{{ route('course.setCreate') }}" class="btn btn-primary pull-right"
                        ><i class="las la-plus"></i>  セットコース作成
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
                                        <input-search-multi :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('course.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search-multi>
                                    </div>
                                    @if(!$courseList->isEmpty())
                                        <div class="tanemaki-table">
                                            <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                <tr>
                                                    <th class="text-center width-130">@sortablelink('is_show', ' 公開状況')</th>
                                                    <th class="text-center min-width-150">@sortablelink('course_id', ' コースID')</th>
                                                    <th class="text-center min-width-120">@sortablelink('course_id', ' セットコード')</th>
                                                    <th class="text-center min-width-120">@sortablelink('display_order', ' 表示順')</th>
                                                    <th class="text-center min-width-120">@sortablelink('course_name', ' コース名')</th>
                                                    <th class="text-center min-width-120">@sortablelink('course_name_short', ' 短縮名')</th>
                                                    <th class="text-center min-width-120">@sortablelink('course_description', ' コース概要')</th>
                                                    <th class="text-center min-width-120">@sortablelink('campaign_code', ' キャンペーンコード')</th>
                                                    <th class="text-center min-width-120">@sortablelink('point_count', ' 付与チケット数')</th>
                                                    <th class="text-center min-width-120">@sortablelink('point_expire_day', ' 有効日数')</th>
                                                    <th class="text-center min-width-120">@sortablelink('sum_amount', ' 価格（税抜）')</th>
                                                    <th class="text-center min-width-120"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($courseList as $index => $course)
                                                    <tr>
                                                        <td class="text-center">{{ $course->is_show ? '公開中' : '非公開'}}</td>
                                                        <td class="text-center">{{ $course->is_set_course ? '' : $course->course_id }}</td>
                                                        <td class="text-center">{{ $course->is_set_course ? $course->course_id : ''}}</td>
                                                        <td class="text-center">{{ $course->display_order }}</td>
                                                        <td class="text-center">{{ $course->course_name }}</td>
                                                        <td class="text-center">{{ $course->course_name_short }}</td>
                                                        <td class="text-center">{{ $course->course_description }}</td>
                                                        <td class="text-center">{{ $course->campaign_code }}</td>
                                                        <td class="text-center">{{ $course->point_count }}</td>
                                                        <td class="text-center">{{ $course->point_expire_day }}</td>
                                                        <td class="text-center">{{ $course->sumamount }}</td>

                                                        <td class="text-center "></td>
                                                        <td>
                                                            <div class="btn-group" style="float:right;">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        @if($course->is_set_course)
                                                                            <a class="dropdown-item"
                                                                               href="{{ route('course.setShow', $course->course_id) }}"><i
                                                                                        class="fa fa-info mr-2"></i>情報</a>
                                                                        @else
                                                                            <a class="dropdown-item"
                                                                               href="{{ route('course.show', $course->course_id) }}"><i
                                                                                        class="fa fa-info mr-2"></i>情報</a>
                                                                        @endif
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{ $courseList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
