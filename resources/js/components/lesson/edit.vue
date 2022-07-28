<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            レッスン編集
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
                                                <input class="form-control" id="lessonCode" type="text" name="lessonCode" @input="changeInput()" @keydown="keyInput" v-model="lessonCode"  v-validate="{required: true, min: 6, max:16, regex: /^[A-Za-z0-9_]+$/}" />

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
                                            <label class="col-md-3 col-form-label text-md-right" for="isShowToSearchDetail"> 講師プロフィールに表示:
                                                </label>

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
                                                    <button type="button" class="btn btn-danger w-100 mr-2" v-on:click="showAlert" >削除
                                                    </button>
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
                    displayOrder : {
                        require: "表示順を入力してください",
                        min_value: "表示順は1～1000000000 を入力してください",
                        max_value: "表示順は1～1000000000 を入力してください"
                    },
                    lessonName: {
                        required: "レッスン名を入力してください",
                        max: "レッスン名は255文字以内で入力してください。",
                    },
                    lessonCode : {
                        required: "レッスンコードはを入力してください",
                        max: "レッスンコードは16桁の英数字で入力してください",
                        min: "レッスンコードは6桁の英数字で入力してください",
                        regex: "レッスンコードは半角英数字および ハイフンで入力してください。"
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
                id : this.lesson.lesson_id,
                csrfToken: Laravel.csrfToken,
                displayOrder: this.lesson.display_order,
                lessonName: this.lesson.lesson_name,
                lessonCode: this.lesson.lesson_code,
                // isTestLesson: this.lesson.is_test_lesson ? true : false,
                // isShowToSearch : this.lesson.is_show_to_search ? true : false,
                // isShowToSearchDetail : this.lesson.is_show_to_teacher_detail ? true : false,
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},
                lessonDescription : this.lesson.lesson_description,

            };
        },
        props: ["listLessonUrl", "updateUrl", 'lesson', 'deleteAction', 'detailLessonUrl'],
        mounted() {},
        methods: {
            keyInput(event) {
/*                switch (event.keyCode) {
                    case 8:  // Backspace
                    case 9:  // Tab
                    case 13: // Enter
                    case 37: // Left
                    case 38: // Up
                    case 39: // Right
                    case 40: // Down
                    break;
                    default:
                    var regex = new RegExp("^[a-zA-Z0-9]+$");
                    var key = event.key;
                    if (!regex.test(key)) {
                        event.preventDefault();
                        return false;
                    }
                    break;
                }*/
            },
            showAlert() {
                let that = this;
                this.$swal({
                    title: 'このレッスンを削除しますか？',
                    icon: "warning",
                    confirmButtonText: "削除する",
                    cancelButtonText: "閉じる",
                    showCancelButton: true
                }).then(result => {
                    if (result.value) {
                        that.flagShowLoader = true;
                        $('.loading-div').removeClass('hidden');
                        axios
                            .delete(that.deleteAction, {
                                _token: Laravel.csrfToken
                            })
                            .then(function (response) {
                                that.flagShowLoader = false;
                                that
                                    .$swal({
                                        title: response.data.message,
                                        icon: "success",
                                        confirmButtonText: "閉じる"
                                    })
                                    .then(function () {
                                        window.location.href = that.listLessonUrl;
                                    });
                            })
                            .catch(error => {
                                that.flagShowLoader = false;
                            });
                    }
                });
            },
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("displayOrder", this.displayOrder);
                formData.append("lessonName", this.lessonName);
                formData.append("lessonCode", this.lessonCode);
                // formData.append("isTestLesson", this.isTestLesson);
                // formData.append("isShowToSearch", this.isShowToSearch);
                // formData.append("isShowToSearchDetail", this.isShowToSearchDetail);
                formData.append("lessonDescription", this.lessonDescription);
                formData.append('_method', 'PUT');
                formData.append('id', this.id);

                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.updateUrl , formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                this.$swal({
                                    title: "レッスン編集が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = that.detailLessonUrl;
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
                                            title: "失敗したデータを編集しました",
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
