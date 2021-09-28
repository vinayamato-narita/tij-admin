<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            問い合わせ詳細
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save">
                                    <input
                                        name="_token"
                                        type="hidden"
                                        v-model="inquiryInfo._token"
                                    />
                                    <div class="card-header">
                                        <h5 class="title-page">問い合わせ情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >問い合わせ番号</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ inquiryInfo.id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >日時</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ inquiryInfo.inquiry_date }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >生徒番号</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ inquiryInfo.student_id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >名前</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ inquiryInfo.student_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >メールアドレス</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ inquiryInfo.student_email }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >対応状況</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ inquiryInfo.inquiry_flag_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >問い合わせ件名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ inquiryInfo.inquiry_subject }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >問い合わせ内容</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ inquiryInfo.inquiry_body }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >備考</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows = "5"
                                                    name="inquiry_memo"
                                                    v-model="inquiryInfo.inquiry_memo"
                                                    v-validate="
                                                        'max:20000'
                                                    "
                                                ></textarea>
                                                
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('inquiry_memo')"
                                                >
                                                    {{ errors.first("inquiry_memo") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center display-flex">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">保存</button>
                                                <button v-if="inquiryInfo.inquiry_flag == notSupport" type="button" class="btn btn-success mr-2" v-on:click="changeInquiryFlag">対応済にする</button>
                                                <a :href="urlInquiryList" class="btn btn-default w-100">閉じる</a>
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
                inquiry_memo: {
                    max: "備考は20000文字以内で入力してください",
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
        };
    },
    props: ["urlUpdateInquiry", "urlInquiryList", "inquiryInfo", "urlChangeInquiryFlag", "notSupport"],
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
                .put(that.urlUpdateInquiry, that.inquiryInfo)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "問い合わせ編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = that.urlInquiryList;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
        changeInquiryFlag(e) {
            let that = this;
            that.flagShowLoader = true;
            axios
                .post(that.urlChangeInquiryFlag, that.inquiryInfo)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        location.reload();
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
    }
};
</script>
