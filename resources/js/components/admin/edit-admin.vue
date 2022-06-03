<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            管理ユーザ編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save" ref="formSubmit" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">管理ユーザ情報</h5>
                                    </div>
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
                                                    v-model="adminInfoEx.admin_user_name"
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
                                                    v-model="adminInfoEx.admin_user_email"
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
                                                    name="password"
                                                    v-model="adminInfoEx.password"
                                                    ref="password"
                                                    v-validate="{
                                                        max: 16,
                                                        min : 8,
                                                        regex: regexMixin,
                                                }"
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('password')"
                                                >
                                                    {{ errors.first("password") }}
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
                                                    v-model="adminInfoEx.password_confirm"
                                                    v-validate="
                                                        'confirmed:password'
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
                                                >権限</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                <input
                                                    class="checkbox"
                                                    type="radio"
                                                    name="role"
                                                    id="role_business"
                                                    v-model="adminInfoEx.role"
                                                    value="1"
                                                />
                                                <label for="role_business" class="mr-2">システム管理者</label>
                                                <input
                                                    class="checkbox"
                                                    type="radio"
                                                    name="role"
                                                    id="role_system"
                                                    v-model="adminInfoEx.role"
                                                    value="2"
                                                />
                                                <label for="role_system">業務管理者</label>
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
                                                    v-model="adminInfoEx.admin_user_description"
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
                                            <div class="text-center display-flex">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <btn-delete :delete-action="deleteAction"
                                                            :message-confirm="messageConfirm" 
                                                            :url-redirect="urlRedirect"></btn-delete>
                                                <a :href="urlAdminList" class="btn btn-default w-100">閉じる</a>
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
import BtnDelete from "./../common/btn-delete.vue";

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
                password: {
                    max: 'パスワードは16文字以内で入力してください。',
                    min: 'パスワードは8文字以上で入力してください。',
                    regex: 'パスワードは少なくとも、英字1字と数字1字を含む、8字～16字の半角英数字または記号で入力してください。'
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
        BtnDelete
    },
    data() {
        return {
            flagShowLoader: false,
            adminInfoEx: this.adminInfo
        };

    },
    props: ["urlAction", "urlAdminList", 'adminInfo', 'deleteAction', 'messageConfirm', 'urlRedirect'],
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
                .put(that.urlAction, that.adminInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        that.$swal({
                            text: "管理ユーザ編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = that.urlAdminList;
                        });
                    }
                })
                .catch(e => {
                    that.flagShowLoader = false;
                    that.errors.add({
                        field: 'admin_user_email',
                        msg: e.response.data.errors.admin_user_email[0]
                    });
                });
        }
    }
};
</script>
