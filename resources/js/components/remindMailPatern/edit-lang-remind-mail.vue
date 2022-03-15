<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            リマインドメール多言語編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">リマインドメール情報</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >種類:
                                            </label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ remindMailEx.send_remind_mail_timing.send_remind_mail_timing_type_name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">{{ remindMailEx.title }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >メール件名<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="mail_lang_subject"
                                                    v-model="remindMailEx.mail_lang_subject"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('mail_lang_subject')"
                                                >
                                                    {{ errors.first("mail_lang_subject") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >メール内容</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows = "5"
                                                    name="mail_lang_body"
                                                    v-model="remindMailEx.mail_lang_body"
                                                    v-validate="
                                                        'max:20000'
                                                    "
                                                ></textarea>
                                                
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('mail_lang_body')"
                                                >
                                                    {{ errors.first("mail_lang_body") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlRemindDetail" class="btn btn-default w-100">閉じる</a>
                                              </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <loader :flag-show="flagShowLoader"></loader>
    </div>
</template>

<script type="text/javascript">
import axios from "axios";
import Loader from "./../common/loader.vue";

export default {
    created: function() {
        let messError = {
            custom: {
                mail_lang_subject: {
                    required: "メール件名を入力してください",
                    max: "メール件名は255文字以内で入力してください",
                },
                mail_lang_body: {
                    max: "メール内容は20000文字以内で入力してください",
                },
            }
        };
        this.$validator.localize("en", messError);
    },
    components: {
        Loader,
    },
    data() {
        return {
            flagShowLoader: false,
            remindMailEx: this.remindMail
        };
    },
    props: ["urlAction", "urlRemindDetail", "remindMail"],
    mounted() {},
    methods: {
        save() {
            let that = this;
            this.$validator
                .validateAll()
                .then(valid => {
                    if (valid) {
                        that.flagShowLoader = true;
                        that.submit();
                    }
                })
                .catch(function(e) {});
        },
        submit(e) {
            let that = this;
            axios
                .post(that.urlAction, that.remindMailEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "リマインドメール多言語編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlRemindDetail;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        }
    }
};
</script>
