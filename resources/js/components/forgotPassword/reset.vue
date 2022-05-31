<template>
<div class="container">
    <div class="fade-in">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form ref="formData" @submit.prevent="save">
                    <input type="hidden" name="_token" v-model="dataToken._token" />
                    <input type="hidden" name="remember_token" v-model="dataToken.remember_token" />
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
                                        <label for="ccnumber">パスワード<span class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password" v-model="dataToken.password"  v-validate="{
                            regex: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d#$%^&*()!@`'_+=\-\[\]\';,.\/{}|:<>?~\\\\]{8,16}$/,
                            required: true,
                            max: 16,
                            min : 8
                                                }"  ref="password">
                                        <div class="input-group error" role="alert" v-if="errors.has('password')">
                                            {{ errors.first("password") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="ccnumber">パスワード（確認用）<span class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password_confirm" v-validate="'required|confirmed:password'" v-model="dataToken.password_confirm">
                                        <div class="input-group error" role="alert" v-if="errors.has('password_confirm')">
                                            {{ errors.first("password_confirm") }}
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
export default {
    created: function () {
        let messError = {
            custom: {
                password: {
                   required: 'パスワードを入力してください。',
                    max: 'パスワードは少なくとも、英字1字と数字1字を含む、記号を除く8字～16字の半角英数字で入力してください。',
                    min: 'パスワードは少なくとも、英字1字と数字1字を含む、記号を除く8字～16字の半角英数字で入力してください。',
                    regex: 'パスワードは少なくとも、英字1字と数字1字を含む、8字～16字の半角英数字または記号で入力してください。'
                },
                password_confirm: {
                    required: "パスワード（確認用）を入力してください。",
                    confirmed: "パスワード（確認用）が入力されたものと異なります。"
                }
            }
        };
        this.$validator.localize("en", messError);
    },
    data() {
        return {
            flagShowLoader: false,
        }
    },
    mounted() {},
        props: ['dataToken', 'urlLogin', 'urlAction'],
        components: {
            Loader
        },
        methods: {
            save() {
                let that = this;
                this.$validator.validateAll().then(valid => {
                    if (valid) {
                        this.flagShowLoader = true;
                        that.submit();
                    } 
                });
            },
            submit(e) {
            let that = this;
            axios
                .post(that.urlAction, that.dataToken)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "パスワードの更新が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = this.urlLogin;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                    this.$swal({
                        text: "期限切れのリンク。",
                        icon: "error",
                        confirmButtonText: "閉じる"
                    })
                });
        }
        }
}
</script>
