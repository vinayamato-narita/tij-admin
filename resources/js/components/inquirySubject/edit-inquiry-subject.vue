<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            問い合わせ件名編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">問い合わせ件名編集</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >問い合わせ件名<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    name="inquiry_subject"
                                                    v-model="inquirySubjectInfoEx.inquiry_subject"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('inquiry_subject')"
                                                >
                                                    {{ errors.first("inquiry_subject") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center display-flex">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <btn-delete :delete-action="deleteAction"
                                                            :message-confirm="messageConfirm" 
                                                            :url-redirect="urlRedirect"></btn-delete>
                                                <a :href="urlInquirySubjectDetail" class="btn btn-default w-100">閉じる</a>
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
import Loader from "../common/loader.vue";
import BtnDelete from "../common/btn-delete.vue";

export default {
    created: function() {
        let messError = {
            custom: {
                inquiry_subject: {
                    required: "問い合わせ件名を入力してください",
                    max: "問い合わせ件名は255文字以内で入力してください",
                }
            }
        };
        this.$validator.localize("en", messError);
    },
    components: {
        Loader,
        BtnDelete
    },
    data() {
        return {
            flagShowLoader: false,
            inquirySubjectInfoEx: this.inquirySubjectInfo
        };
    },
    props: ["urlAction", "urlInquirySubjectDetail", "inquirySubjectInfo", 'deleteAction', 'messageConfirm', 'urlRedirect'],
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
                .put(that.urlAction, that.inquirySubjectInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "問い合わせ件名編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = this.urlInquirySubjectDetail;
                        });
                    } else {
                        this.$swal({
                            text: response.data.message,
                            icon: "warning",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = this.urlInquirySubjectDetail;
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
