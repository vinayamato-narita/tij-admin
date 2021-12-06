<template>
<div class="container">
    <div class="fade-in">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form ref="formData" @submit.prevent="save">
                    <input type="hidden" :value="csrfToken" name="_token" />
                    <div class="card">
                        <div class="card-body card-login">
                            <div class="row justify-content-center">
                                <div class="col-sm-8 text-center title-login">
                                    <h3>パスワードの再設定</h3>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label class="title-forgot-password">パスワード再設定のURLを記載したメールを送信します。</label>
                                        <input 
                                            class="form-control" 
                                            placeholder="メールアドレス" 
                                            type="text" 
                                            name="admin_email" 
                                            v-validate="'required|email'" 
                                            v-model="model.admin_email" 
                                        />
                                        <div 
                                            class="input-group error" 
                                            role="alert" 
                                            v-if="errors.has('admin_email')"
                                        >
                                            {{ errors.first("admin_email") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-8 p-l-20">
                                    <button class="btn btn-primary px-4 btn-login" type="submit">送信する</button>
                                </div>
                            </div>
                            <div class="row justify-content-center group-back-login">
                                <div class="col-sm-8 p-l-20">
                                    <a class="btn-link px-0" :href="urlLogin">ログイン画面へ</a><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <loader :flag-show="flagShowLoader"></loader>
</div>
</template>

<script>
import Loader from "../common/loader.vue"
import axios from "axios";

export default {
    created: function () {
        let messError = {
            custom: {
                admin_email: {
                    required: "メールアドレスを入力してください。",
                    email: "メールアドレス形式が正しくありません。"
                }
            }
        };
        this.$validator.localize("en", messError);
    },
    data() {
        return {
          flagShowLoader: false,
          model: {},
          csrfToken: Laravel.csrfToken,
        }
    },
    mounted() {},
    props: ['urlLogin', 'urlAction'],
    components: {
        Loader
    },
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
                });
        },
        submit(e) {
            let that = this;
            axios
                .post(that.urlAction, that.model)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "メール送信が完了しました。登録されたメールをチェックしてください。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = this.urlLogin;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                    that.errors.add({
                        field: 'admin_email',
                        msg: e.response.data.message
                    });
                });
        }
    }
}
</script>
