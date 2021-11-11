<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            コメント編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">コメント情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >生徒番号</label
                                            >
                                            <div class="col-md-6 pt-7">
                                               {{ commentInfoEx.student_id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >生徒名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ commentInfoEx.student_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >講師のニックネーム</label
                                            >
                                            <div class="col-md-6 pt-7">
                                               {{ commentInfoEx.teacher_nickname }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >作成日</label
                                            >
                                            <div class="col-md-6 pt-7">
                                               {{ commentInfoEx.create_date | formatDateTime }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >更新日</label
                                            >
                                            <div class="col-md-6 pt-7">
                                               {{ commentInfoEx.update_date | formatDateTime}}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >コメント</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows = "5"
                                                    name="comment"
                                                    v-model="commentInfoEx.comment"
                                                    v-validate="
                                                        'max:20000'
                                                    "
                                                ></textarea>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('comment')"
                                                >
                                                    {{ errors.first("comment") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2" :disabled = "commentInfoEx.admin_can_edit == 0">登録</button>
                                                <a :href="urlStudentCommentList" class="btn btn-default w-100">閉じる</a>
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
import moment from 'moment'

export default {
    created: function() {
        let messError = {
            custom: {
                comment: {
                    max: "コメントは2000文字以内で入力してください",
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
            commentInfoEx: this.commentInfo
        };
    },
    props: ["urlAction", "urlStudentCommentList", "commentInfo"],
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
                .post(that.urlAction, that.commentInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "コメント編集成が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlStudentCommentList;
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
