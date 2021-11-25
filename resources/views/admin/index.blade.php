@php
    use App\Components\SearchQueryComponent;
@endphp

@extends('layouts.default')
@section('title', '管理ユーザ一覧')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="pull-left">
                    <h5>
                        管理ユーザ一覧
                    </h5>
                </div>
                <div class="pull-right mrb-5">
                    <a href="{{ route('admin.create') }}" class="btn btn-primary pull-right"
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
                                        <input-search :page-limit="{{ $pageLimit }}" :url="{{ json_encode(route('admin.index')) }}" :data-query="{{json_encode(!empty($request) ? $request->all() : new stdClass)}}"></input-search>
                                    </div>
                                    
                                </div>
                                @if(!$adminList->isEmpty())
                                    <div class="tanemaki-table">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                                <tr>
                                                    <th class="text-left width-130">@sortablelink('admin_user_name', ' ユーザ名')</th>
                                                    <th class="text-left min-width-150">@sortablelink('admin_user_email', ' メールアドレス')</th>
                                                    <th class="text-left min-width-120">説明</th>
                                                    <th class="text-left min-width-150">業務優先度設定</th>
                                                    <th class="w-100"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($adminList as $index => $user)
                                                    <tr>
                                                        <td class="text-left">{{ $user->admin_user_name }}</td>
                                                        <td class="text-left">{{ $user->admin_user_email }}</td>
                                                        <td class="text-left">{{ $user->admin_user_description }}</td>
                                                        <td class="text-left">
                                                            <change-status-admin :url-action="{{ json_encode(route('changeStatusAdmin', $user->admin_user_id)) }}" :status="{{ $user->is_online }}"></change-status-admin>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">操作選択</button>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('admin.edit', $user->admin_user_id) }}"><i class="fa fa-book mr-2"></i>確認・編集</a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('admin.editRole', $user->admin_user_id) }}"><i class="fa fa-cog mr-2"></i>権限編集</a>
                                                                    </li>
                                                                    <li>
                                                                        <delete-item
                                                                            :delete-action="{{ json_encode(route('admin.destroy', $user->admin_user_id)) }}"
                                                                            :message-confirm="{{ json_encode('この管理ユーザを削除しますか？') }}"
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

                                    {{ $adminList->appends(SearchQueryComponent::alterQuery($request))->links('pagination.paginate') }}
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
