@extends('layouts.default')
@section('title', 'お知らせ情報表示')
<?php 
use App\Enums\LangType;
?>
@section('content')
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            お知らせ情報表示
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                	<h5 class="title-page">お知らせ情報</h5>
                                	<a href="{{ route('news.edit', $newsInfo->news_id) }}" class="btn btn-primary">編集</a>     
                            	</div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >対象:
                                            </label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ $newsInfo->newsSubject->news_subject_ja }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >タイトル:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $newsInfo->news_title }}

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >内容:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $newsInfo->news_body }}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">英語版</h5>
                                    <a href="{{ route('editLangNews', [$newsInfo->news_id, LangType::EN]) }}" class="btn btn-primary">編集</a>     
                                </div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >タイトル:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ $newsEnInfo->news_title ?? "" }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >内容:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $newsEnInfo->news_body ?? "" }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">ベトナム語版</h5>
                                    <a href="{{ route('editLangNews', [$newsInfo->news_id, LangType::ZH]) }}" class="btn btn-primary">編集</a>     
                                </div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >タイトル:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ $newsZhInfo->news_title ?? "" }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >内容:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $newsZhInfo->news_body ?? "" }}
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
