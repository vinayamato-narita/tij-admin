@extends('layouts.default')
@section('title', '問い合わせ件名情報表示')
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
                        問い合わせ件名情報表示
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                	<h5 class="title-page">問い合わせ件名情報</h5>
                                	<a href="{{ route('inquirySubject.edit', $inquirySubjectInfo->inquiry_subject_id) }}" class="btn btn-primary">編集</a>     
                            	</div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >問い合わせ件名:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ $inquirySubjectInfo->inquiry_subject }}
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
                                    <a href="{{ route('editLangInquirySubject', [$inquirySubjectInfo->inquiry_subject_id, LangType::EN]) }}" class="btn btn-primary">編集</a>     
                                </div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >問い合わせ件名:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ $inquirySubjectEnInfo->inquiry_subject ?? "" }}
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
                                    <a href="{{ route('editLangInquirySubject', [$inquirySubjectInfo->inquiry_subject_id, LangType::ZH]) }}" class="btn btn-primary">編集</a>     
                                </div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >問い合わせ件名:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ $inquirySubjectZhInfo->inquiry_subject ?? "" }}
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
