@extends('layouts.default')
@section('title', 'グループコースユーザインポート結果')
@section('content')
<div class="c-body">
    <main class="c-main pt-0">
        <div class="container-fluid">
            <div class="page-heading">
                <div class="page-heading-left">
                    <h5>
                        ユーザ一括登録結果
                    </h5>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <p>{{ $result ? "ユーザ一括登録が成功しました" : "ユーザ一括登録が失敗しました" }}</p>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 50px;">
                    <div class="text-center">
                        <a class="btn btn-default read-csv-button width-200" href="{{ route('courseGroupUser.importPost') }}">
                            ユーザ一括登録画面へ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

