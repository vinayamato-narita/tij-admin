<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            Zoomアカウント編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">Zoomアカウント編集</div>
                                    <div class="card-body">

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="zoomAccountName">Zoomアカウント名:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                                <div class="col-md-6">
                                                <input class="form-control" id="zoomAccountName" type="text" name="zoomAccountName" @input="changeInput()"  v-model="zoomAccountName"  v-validate="'required|max:50'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("zoomAccountName") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="apiKey">APIキー:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="apiKey" type="text" name="apiKey" @input="changeInput()"  v-model="apiKey"  v-validate="'required|max:50'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("apiKey") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="apiSecret">API SECRET:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="apiSecret" type="text" name="apiSecret" @input="changeInput()"  v-model="apiSecret"  v-validate="'required|max:50'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("apiSecret") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="apiSecret">メールアドレス :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="zoomEmail" type="text" name="zoomEmail" @input="changeInput()"  v-model="zoomEmail"  v-validate="'required|email|max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("zoomEmail") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions text-center">
                                            <div class="line"></div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                    <a :href="listZoomAccountUrl" class="btn btn-default w-100">キャンセル</a>
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
    import jwt from 'jsonwebtoken'

    export default {
        created: function () {
            let messError = {
                custom: {
                    zoomAccountName: {
                        required: "Zoomアカウント名を入力してください",
                        max: "Zoomアカウント名は50文字以内で入力してください。",
                    },
                    apiKey: {
                        required: "APIキーを入力してください",
                        max: "APIキーは50文字以内で入力してください。",
                    },
                    apiSecret: {
                        required: "API SECRETを入力してください",
                        max: "API SECRETは50文字以内で入力してください。",
                    },
                    zoomEmail: {
                        required: "メールアドレスを入力してください",
                        max: "メールアドレスは255文字以内で入力してください。",
                        email: "メールアドレス形式が正しくありません。"
                    }
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
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},
                zoomAccountName: this.zoomAccount.zoom_account_name,
                apiKey: this.zoomAccount.api_key,
                apiSecret: this.zoomAccount.api_secret,
                zoomEmail: this.zoomAccount.zoom_email

            };
        },
        props: ["listZoomAccountUrl", "updateUrl", "zoomAccount"],
        mounted() {},
        methods: {
            register() {
                let that = this;
                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        const payload = {
                            iss: that.apiKey,
                            exp: new Date().getTime() + 5000
                        }
                        const token = jwt.sign(payload, this.apiSecret)
                        let formData = new FormData();
                        formData.append('token', token);
                        formData.append('zoom_account_name', this.zoomAccountName);
                        formData.append('api_key', this.apiKey);
                        formData.append('api_secret', this.apiSecret);
                        formData.append('zoom_email', this.zoomEmail);
                        formData.append('_method', 'PUT');
                        axios
                            .post(that.updateUrl , formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                this.$swal({
                                    title: "Zoom設定情報の編集が完了しました",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = that.listZoomAccountUrl;
                                });
                                that.flagShowLoader = false;
                            })
                            .catch((err) => {
                                switch (err.response.status) {
                                    case 422:
                                        this.$swal({
                                            title: err.response.data.message,
                                            icon: "error",
                                            confirmButtonText: "OK",
                                        }).then(function (confirm) {});
                                        that.flagShowLoader = false;
                                        break;
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
