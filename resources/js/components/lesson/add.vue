<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            レッスン新規作成
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">レッスン情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="lessonName">レッスン名:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="lessonName" type="text" name="lessonName" @input="changeInput()"  v-model="lessonName"  v-validate="'required|max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("lessonName") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="lessonName">レッスンコード:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="lessonCode" type="number" name="lessonCode" @input="changeInput()" style="max-width: 100px" v-model="lessonCode"  v-validate="'required|max:6'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("lessonCode") }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="isTestLesson"> テストあり/なし: </label>
                                            <div class="col-md-6 col-form-label">
                                                <input class=" checkbox" id="isTestLesson" name="isTestLesson"
                                                       type="checkbox"
                                                       @input="changeInput()" v-model="isTestLesson" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isTestLesson">
                                                    テストあり
                                                </label>
                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("isTestLesson") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="isShowToSearch"> サジェスト検索に含める:</label>
                                            <div class="col-md-6 col-form-label">
                                                <input class=" checkbox" id="isShowToSearch" name="isShowToSearch"
                                                       type="checkbox"
                                                       @input="changeInput()" v-model="isShowToSearch" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isShowToSearch">
                                                    サジェスト検索に含める
                                                </label>
                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("isShowToSearch") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="isShowToSearchDetail"> サジェスト検索に含める:</label>
                                            <div class="col-md-6 col-form-label">
                                                <input class=" checkbox" id="isShowToSearchDetail" name="isShowToSearchDetail"
                                                       type="checkbox"
                                                       @input="changeInput()" v-model="isShowToSearchDetail" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isShowToSearchDetail">
                                                    表示する
                                                </label>
                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("isShowToSearchDetail") }}
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="lessonDescription"> 説明: </label>

                                            <div class="col-md-6">
                                                <textarea class="form-control" id="lessonDescription"  name="lessonDescription"
                                                          @input="changeInput()"  v-model="lessonDescription">
                                                </textarea>
                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("lessonDescription") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions text-center">
                                            <div class="line"></div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                    <a :href="listLessonUrl" class="btn btn-default w-100">閉じる</a>
                                                </div>
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

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";

    export default {
        created: function () {
            let messError = {
                custom: {
                    lessonName: {
                        required: "レッスン名を入力してください",
                        max: "レッスン名は255文字以内で入力してください。",
                    },
                    lessonCode : {
                        required: "レッスンコードはを入力してください",
                        max: "レッスンコードは6文字以内で入力してください。",
                    },

                },
            };
            this.$validator.localize("en", messError);
        },
        components: {
            Loader,
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
                lessonName: '',
                lessonCode: '',
                // isTestLesson: false,
                // isShowToSearch : true,
                // isShowToSearchDetail : true,
                flagShowLoader: false,
                messageText: this.message,
                lessonDescription : '',
                errorsData: {},
            };
        },
        props: ["listLessonUrl", "createUrl"],
        mounted() {},
        methods: {
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("lessonName", this.lessonName);
                formData.append("lessonCode", this.lessonCode);
                // formData.append("isTestLesson", this.isTestLesson);
                // formData.append("isShowToSearch", this.isShowToSearch);
                // formData.append("isShowToSearchDetail", this.isShowToSearchDetail);
                formData.append("lessonDescription", this.lessonDescription);

                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.createUrl , formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                this.$swal({
                                    title: "レッスン新規作成が完了しました",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = that.listLessonUrl;
                                });
                                that.flagShowLoader = false;
                            })
                            .catch((err) => {
                                switch (err.response.status) {
                                    case 422:
                                    case 400:
                                        this.errorsData = err.response.data;
                                        that.flagShowLoader = false;
                                        break;
                                    case 500:
                                        this.$swal({
                                            title: "失敗したデータを追加しました",
                                            icon: "error",
                                            confirmButtonText: "OK",
                                        }).then(function (confirm) {});
                                        that.flagShowLoader = false;
                                        break;
                                    default:
                                        break;
                                }
                            });
                    }
                });

            },
            changeInput() {
                this.errorsData = [];
                this.messageText = "";
            },
        },
    }
</script>
