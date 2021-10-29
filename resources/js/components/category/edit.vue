<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            コースカテゴリ編集


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">コースカテゴリ情報

                                    </div>
                                    <div class="card-body">



                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="orderNum">表示順:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="orderNum" type="number" name="orderNum" @input="changeInput()" style="max-width: 100px" v-model="orderNum" value="1" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("orderNum") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="categoryIcon">表示アイコン:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6" style="display: flex">
                                                <input style="" class="form-control col-md-8 " id="categoryIcon" type="text" name="categoryIcon" @input="changeInput()"  v-model="categoryIcon"  v-validate="'required|max:255|url'" />
                                                <div class="col-md-1"></div>
                                                <button type="button" class="btn btn-outline-secondary col-md-2" v-on:click="checkLink(categoryIcon)">チェック</button>


                                            </div>
                                        </div>
                                        <div class="form-group row " style="margin-top: -15px">
                                            <div class="col-md-3">

                                            </div>
                                            <div class="col-md-6 input-group is-danger" role="alert">
                                                {{ errors.first("categoryIcon") }}
                                            </div>
                                        </div>






                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="categoryName">カテゴリ名:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6" >
                                                <input style="" class="form-control " id="categoryName" type="text" name="categoryName" @input="changeInput()"  v-model="categoryName"  v-validate="'required|max:255'" />
                                                <div class=" input-group is-danger" role="alert">
                                                    {{ errors.first("categoryName") }}
                                                </div>

                                            </div>
                                        </div>









                                        <div class="form-actions text-center">
                                            <div class="line"></div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                    <a :href="detailCategoryUrl" class="btn btn-default w-100">閉じる</a>
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
                    orderNum : {
                        require: "表示順を入力してください",
                        min_value: "表示順は1～1000000000 を入力してください",
                        max_value: "表示順は1～1000000000 を入力してください"
                    },
                    categoryIcon: {
                        required: "コース名表示アイコンを入力してください",
                        max: "コース名表示アイコンは255文字以内で入力してください。",
                        url: "コース名表示アイコンをURL形で入力してください。",

                    },
                    categoryName: {
                        required: "カテゴリ名を入力してください",
                        max: "カテゴリ名は255文字以内で入力してください。",
                        url: "カテゴリ名をURL形で入力してください。",

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
                id : this.category.id,
                csrfToken: Laravel.csrfToken,
                errorsData: {},
                orderNum : this.category.order_num,
                categoryIcon : this.category.category_icon,
                categoryName : this.category.category_name,
                flagShowLoader : false

            };
        },
        props: ["updateUrl", 'category', 'detailCategoryUrl'],
        mounted() {},
        methods: {
            checkLink(url) {
                if (url != null) {
                    window.open(url, '_blank');
                }
            },
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("orderNum", this.orderNum);
                formData.append("categoryIcon", this.categoryIcon);
                formData.append("categoryName", this.categoryName);
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
                                    title: "コースカテゴリ編集が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = that.detailCategoryUrl;
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
