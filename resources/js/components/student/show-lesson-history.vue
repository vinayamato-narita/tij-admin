<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            レッスン履歴詳細
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save">
                                    <div class="card-header">
                                        <h5 class="title-page">レッスン履歴情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >生徒番号</label
                                            >
                                            <div class="col-md-6 pt-7">
                                               {{ lessonHistoryInfoEx.id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >生徒名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ lessonHistoryInfoEx.student_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >講師名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ lessonHistoryInfoEx.teacher_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >レッスン日時</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ getLessonStartEndTime(lessonHistoryInfoEx.lesson_starttime, lessonHistoryInfoEx.lesson_endtime) }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >コース名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ lessonHistoryInfoEx.course_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >レッスン名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ lessonHistoryInfoEx.lesson_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >テキスト名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ lessonHistoryInfoEx.lesson_text_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >出（0）欠（1）</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ lessonHistoryInfoEx.skype_voice_rating_from_teacher }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >評価（生徒→先生）</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ lessonHistoryInfoEx.average }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >レッスンに対する感想（生徒）</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ lessonHistoryInfoEx.comment_from_student_to_office }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >コメント（先生→生徒）</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ lessonHistoryInfoEx.comment_from_teacher_to_student }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >管理者のコメント</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows = "5"
                                                    name="comment"
                                                    v-model="lessonHistoryInfoEx.comment_from_admin_to_student"
                                                    v-validate="
                                                        'max:20000'
                                                    "
                                                ></textarea>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('comment_from_admin_to_student')"
                                                >
                                                    {{ errors.first("comment_from_admin_to_student") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <button type="button" class="btn btn-danger w-100 mr-2" @click="cancelLessonHistory">削除</button>
                                                <a :href="urlStudentLessonHistoryList" class="btn btn-default w-100">閉じる</a>
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
                comment_from_admin_to_student: {
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
            lessonHistoryInfoEx: this.lessonHistoryInfo
        };
    },
    props: ["urlAction", "urlCancelLessonHistory", "urlStudentLessonHistoryList", "lessonHistoryInfo"],
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
                .post(that.urlAction, that.lessonHistoryInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "レッスン履歴編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlStudentLessonHistoryList;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
        getLessonStartEndTime(start, end) {
            let result = "";
            if (start != "" && end != "") {
                result = moment(String(start)).tz("Asia/Tokyo").format("YYYY-MM-DD HH:mm") + " ~ " + moment(String(end)).tz("Asia/Tokyo").format("YYYY-MM-DD HH:mm");
            }
            return result
        },
        cancelLessonHistory() {
            let that = this;
            const inputOptions = {
                0 : '残さない',
                1: '残す',
            };
            this.$swal({
                text: "レッスンキャンセル履歴を残しますか？",
                input: 'radio',
                inputOptions: inputOptions,
                confirmButtonText: "削除する",
                showCancelButton: true,
                cancelButtonText: "閉じる",
                inputValue: 1,
            }).then(result => {
                if (result.isConfirmed) {
                    that.flagShowLoader = true;
                    that.submitCancelLessonHistory(result.value);
                }
            });
        },
        submitCancelLessonHistory(type) {
            let that = this;
            axios
                .post(that.urlCancelLessonHistory, {
                    cancel_type: type,
                    id: that.lessonHistoryInfoEx.id,
                    _token: Laravel.csrfToken
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "レッスン履歴削除が完了しました",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlStudentLessonHistoryList;
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
