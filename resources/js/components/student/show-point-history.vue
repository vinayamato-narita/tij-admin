<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            ポイント履歴詳細
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save">
                                    <div class="card-header">
                                        <h5 class="title-page">ポイント履歴情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >生徒番号</label
                                            >
                                            <div class="col-md-6 pt-7">
                                               {{ pointHistoryInfoEx.student_id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >生徒名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ pointHistoryInfoEx.student_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >受注番号</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ pointHistoryInfoEx.point_subscription_id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >処理日</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ pointHistoryInfoEx.pay_date | formatDateTime }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >コース名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ pointHistoryInfoEx.course_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >有効期限</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ pointHistoryInfoEx.expire_date | formatDateTime }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >内容</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ pointHistoryInfoEx.pay_description }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >ポイント</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ parseInt(pointHistoryInfoEx.point_count) }}
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
                                                    name="admin_note"
                                                    v-model="pointHistoryInfoEx.admin_note"
                                                    v-validate="
                                                        'max:20000'
                                                    "
                                                ></textarea>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('admin_note')"
                                                >
                                                    {{ errors.first("admin_note") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2"
                                                    >登録</button>
                                                <button type="button" class="btn btn-danger w-100 mr-2" @click="cancelPointHistory"
                                                    >削除</button>
                                                <a :href="urlStudentPointHistoryList" class="btn btn-default w-100">閉じる</a>
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
import moment from 'moment-timezone'

export default {
    created: function() {
        let messError = {
            custom: {
                admin_note: {
                    max: "管理者のコメントは2000文字以内で入力してください",
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
            pointHistoryInfoEx: this.pointHistoryInfo
        };
    },
    props: ["urlAction", "urlCancelPointHistory", "urlStudentPointHistoryList", "pointHistoryInfo"],
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
                .post(that.urlAction, that.pointHistoryInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "レッスン履歴編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlStudentPointHistoryList;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
        cancelPointHistory() {
            let that = this;
            this.$swal({
                text: "このポイント履歴を削除しますか？",
                icon: "warning",
                confirmButtonText: "削除する",
                cancelButtonText: "閉じる",
                showCancelButton: true
            }).then(result => {
                if (result.isConfirmed) {
                    that.flagShowLoader = true;
                    that.submitCancelPointHistory();
                }
            });
        },
        submitCancelPointHistory() {
            let that = this;
            axios
                .post(that.urlCancelPointHistory, {
                    student_point_history_id: that.pointHistoryInfoEx.student_point_history_id,
                    _token: Laravel.csrfToken
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "ポイント削除が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlStudentPointHistoryList;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
    }
};
</script>
