@extends('layouts.default')
@section('title', 'show admin')
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            show admin
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">show admin</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >ユーザ名:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $adminInfo->admin_name }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >メールアドレス:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $adminInfo->admin_email }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >説明:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $adminInfo->description }}
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group">
                                        <div class="text-center">
                                            <a href="{{ route('admin.edit', $adminInfo->id) }}" class="btn btn-primary w-100 mr-2">編集</a>
                                            <a href="{{ route('admin.index') }}" class="btn btn-default w-100">閉じる</a>
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
