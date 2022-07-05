@extends('layouts.default')
@section('title', 'FAQ情報表示')
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
                            FAQ情報表示
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                	<h5 class="title-page">FAQ情報</h5>
                                	<a href="{{ route('faq.edit', $faqInfo->faq_id) }}" class="btn btn-primary">編集</a>     
                            	</div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >表示順:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ $faqInfo->no_faq }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >カテゴリ:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $faqInfo->faqCategory->faq_category_name }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >質問・Q:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            {{ $faqInfo->question }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >答え・A:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            <nl2br tag="p"  :text="{{json_encode($faqInfo->answer) ?? ""}}">

                                            </nl2br>
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
                                    <a href="{{ route('editLangFaq', [$faqInfo->faq_id, LangType::EN]) }}" class="btn btn-primary">編集</a>     
                                </div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >質問・Q:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ $faqEnInfo->question ?? "" }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >答え・A:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            <nl2br tag="p"  :text="{{json_encode($faqEnInfo->answer ?? "")}}">

                                            </nl2br>
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
                                    <h5 class="title-page">中国語版</h5>
                                    <a href="{{ route('editLangFaq', [$faqInfo->faq_id, LangType::ZH]) }}" class="btn btn-primary">編集</a>     
                                </div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >質問・Q:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ $faqZhInfo->question ?? "" }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >答え・A:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            <nl2br tag="p"  :text="{{json_encode($faqZhInfo->answer ?? "")}}">

                                            </nl2br>
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
