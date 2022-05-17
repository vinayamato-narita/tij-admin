<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            リマインドメール情報表示
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">リマインドメール情報
                                    <div class="float-right">
                                        <a :href="this.editRemindMailUrl" class="btn btn-primary ">編集</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">種類:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ this.remindMail.send_remind_mail_timing.send_remind_mail_timing_type_name }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">送信タイミング:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ this.remindMail.timing_minutes }}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">メール件名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ this.remindMail.mail_subject }}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">メール内容:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            <pre>{{ this.remindMail.mail_body }}</pre>
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
                                    <a :href="editLangEnUrl" class="btn btn-primary">編集</a>     
                                </div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >メール件名:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ this.mailEnInfo != null ? this.mailEnInfo.mail_subject : "" }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >メール内容:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            <pre>{{ this.mailEnInfo != null ? this.mailEnInfo.mail_body : "" }}</pre>
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
                                    <a :href="editLangZhUrl" class="btn btn-primary">編集</a>     
                                </div>
                                
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >メール件名:</label
                                        >
                                        <div class="col-md-3 pd-7">
                                            {{ this.mailZhInfo != null ? this.mailZhInfo.mail_subject : "" }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >メール内容:</label
                                        >
                                        <div class="col-md-6 pd-7">
                                            <pre>{{ this.mailZhInfo != null ? this.mailZhInfo.mail_body : "" }}</pre>
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
</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";

    export default {
        created: function () {
            
        },
        components: {
            Loader,
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
            };
        },
        props: ['editRemindMailUrl', 'remindMail', 'editLangEnUrl', 'editLangZhUrl', 'mailEnInfo', 'mailZhInfo'],
        mounted() { },
        methods: {
            decodeHtml: function (html) {
                var txt = document.createElement("textarea");
                txt.innerHTML = html;
                return txt.value;
            }
        },
    }
</script>
