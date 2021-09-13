<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            講師新規作成
                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                    </div>
                </div>
                <div class="clear"></div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row col-xs-12 col-lg-8 col-xl-9 m-auto">
                                        <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                            <div class="card-body">
                                                <div class="form-group row col-md-9">
                                                    <label class="col-md-3 col-form-label" for="displayOrder">表示順:
                                                        <span class="required fa fa-star" aria-required="true"></span></label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="displayOrder" type="number" name="displayOrder" @input="changeInput()" style="max-width: 100px" v-model="displayOrder" value="1" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("displayOrder") }}
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group row col-md-9">
                                                    <label class="col-md-3 col-form-label" for="teacherName">講師名:
                                                        <span class="required fa fa-star" aria-required="true"></span></label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="teacherName" type="text" name="teacherName" @input="changeInput()"  v-model="teacherName"  v-validate="'required|max:255'" />

                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("teacherName") }}
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group row col-md-9">
                                                    <label class="col-md-3 col-form-label" for="teacherName">ニックネーム:
                                                        <span class="required fa fa-star" aria-required="true"></span></label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="nickName" type="text" name="nickName" @input="changeInput()"  v-model="nickName"  v-validate="'required|max:255'" />

                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("nickName") }}
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group row col-md-9">
                                                    <label class="col-md-3 col-form-label" for="teacherName">メールアドレス:
                                                        <span class="required fa fa-star" aria-required="true"></span></label>
                                                    <div class="col-md-9">
                                                        <input class="form-control" id="mail" type="text" name="mail" @input="changeInput()"  v-model="mail"  v-validate="'required|email_format|max:255'" />

                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("mail") }}
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group row col-md-9">
                                                    <label class="col-md-3 col-form-label" for="timeZone">タイムゾーン:
                                                    </label>
                                                    <div class="col-md-9">
                                                        <select name="timeZone" class="form-control valid" id="timeZone" v-model="timeZone" aria-invalid="false">
                                                            <option value="0" selected="selected"></option>
                                                        </select>
                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("timeZone") }}
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group row col-md-9">
                                                    <label class="col-md-3 col-form-label" >固定/自由: </label>
                                                    <div class="col-md-9">
                                                        <div style="margin-top: 5px">
                                                            <label class="radio" for="is-free-teacher-0">
                                                                <input name="isFreeTeacher" id="is-free-teacher-0" value="0" type="radio">
                                                                固定
                                                            </label>
                                                            &nbsp;
                                                            <label class="radio" for="is-free-teacher-1">
                                                                <input name="isFreeTeacher" value="1" id="is-free-teacher-1"  type="radio">
                                                                自由
                                                            </label>
                                                            <div class="input-group is-danger" role="alert">
                                                                {{ errors.first("isFreeTeacher") }}
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <svg class="c-icon">
                                            <use xlink:href="/assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                                        </svg>
                                    </span>
                                                    </div>
                                                    <input type="text" class="form-control" id="nameUser" name="nameUser" placeholder="お名前" v-model="nameUser" @input="changeInput()" v-validate="'required|max:255'" />
                                                    <div class="input-group is-danger" role="alert">
                                                        {{ errors.first("nameUser") }}
                                                    </div>
                                                </div>
                                                <div class="form-actions text-center">
                                                    <button class="btn btn-primary" type="submit">新規登録</button>
                                                    <a :href="listUserUrl" class="btn btn-secondary" type="button">キャンセル</a>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
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
                        requirecustom: "表示順を入力してください",
                        min_value: "表示順は1～1000000000 を入力してください",
                        max_value: "表示順は1～1000000000 を入力してください"
                    },
                    teacherName: {
                        required: "講師名を入力してください",
                        max: "名前は255文字以内で入力してください。",
                    },
                    nickName: {
                        required: "ニックネームを入力してください",
                        max: "ニックネームは255文字以内で入力してください。",
                    },
                    mail: {
                        required: "メールアドレスを入力してください",
                        email_format: "メールアドレス形式は正しくありません。",
                        max: "メールアドレスは255文字以内で入力してください。",
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
                nameUser: '',
                displayOrder: '',
                email: '',
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},
                timeZone : 0
            };
        },
        props: ["listUserUrl"],
        mounted() {},
        methods: {
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("name", this.nameUser);
                formData.append("email", this.email);
                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(`post-register-user`, formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                this.$swal({
                                    title: "営業マンが追加されました",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    location.replace(res.data);
                                });
                            })
                            .catch((err) => {
                                switch (err.response.status) {
                                    case 400:
                                        this.errorsData = err.response.data;
                                        break;
                                    case 500:
                                        this.$swal({
                                            title: "失敗したデータを追加しました",
                                            icon: "error",
                                            confirmButtonText: "Cool",
                                        }).then(function (confirm) {});
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
            handleType(event) {
                const {
                    srcElement,
                    type
                } = event;
                const {
                    name,
                    value
                } = srcElement;

                if (type === "blur" && !value) {
                    this.fieldTypes[name] = "text";
                } else {
                    this.fieldTypes[name] = "password";
                }
            },
        },
    }
</script>
