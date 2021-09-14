<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            管理ユーザ新規作成
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save" ref="formSubmit">
                                    <input
                                        name="_token"
                                        type="hidden"
                                        v-model="adminInfo._token"
                                        value=""
                                        v-validate="
                                            'required|max:255'
                                        "
                                    />
                                    <div class="card-header">管理ユーザ情報</div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >ユーザ名<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    name="admin_user_name"
                                                    v-model="adminInfo.admin_user_name"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('admin_user_name')"
                                                >
                                                    {{ errors.first("admin_user_name") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >メールアドレス<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    name="admin_user_email"
                                                    v-model="adminInfo.admin_user_email"
                                                    @change="emailUnique = ''"
                                                    v-validate="
                                                        'required|email|max:255'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('admin_user_email')"
                                                >
                                                    {{ errors.first("admin_user_email") }}
                                                </div>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="
                                                        emailUnique &&
                                                            !errors.has('admin_user_email')
                                                    "
                                                >
                                                    {{ emailUnique }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >パスワード</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    type="password"
                                                    name="admin_user_password"
                                                    v-model="adminInfo.admin_user_password"
                                                    ref="admin_user_password"
                                                    v-validate="
                                                        'password_rule|min:8|max:32'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('admin_user_password')"
                                                >
                                                    {{ errors.first("admin_user_password") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >確認</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    type="password"
                                                    name="password_confirm"
                                                    v-model="adminInfo.password_confirm"
                                                    v-validate="
                                                        'confirmed:admin_user_password'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('password_confirm')"
                                                >
                                                    {{ errors.first("password_confirm") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >説明</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows = "5"
                                                    name="admin_user_description"
                                                    v-model="adminInfo.admin_user_description"
                                                    v-validate="
                                                        'max:2000'
                                                    "
                                                ></textarea>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('admin_user_description')"
                                                >
                                                    {{ errors.first("admin_user_description") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlAdminDetail" class="btn btn-default w-100">閉じる</a>
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
                admin_user_name: {
                    required: "ユーザ名を入力してください",
                    max: "ユーザ名は255文字以内で入力してください"
                },
                admin_user_email: {
                    required: "メールアドレスを入力してください",
                    max: "メールアドレスは255文字以内で入力してください",
                    email: "メールアドレスを正確に入力してください"
                },
                admin_user_password: {
                    max: "パスワードは32文字以内で入力してください",
                    min: "パスワードは8文字以上で入力してください",
                    password_rule:
                        "パスワードは半角英字・記号と数字混在で入力してください"
                },
                password_confirm: {
                    confirmed: "確認が一致しません"
                },
                admin_user_description: {
                    max: "説明は2000文字以内で入力してください",
                }
            }
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
            emailUnique: "",
        };
    },
    props: ["urlAction", "urlAdminDetail", 'adminInfo'],
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
                .put(that.urlAction, that.adminInfo)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "管理ユーザ編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = this.urlAdminDetail;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                    this.emailUnique = e.response.data.errors.hasOwnProperty(
                        "admin_user_email"
                    )
                        ? e.response.data.errors.admin_user_email[0]
                        : "";
                });
        }
    }
};
</script>
