<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            コースカテゴリ多言語編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">コースカテゴリ情報
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                        >カテゴリID :
                                        </label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ category.category_id }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                        >カテゴリ名:</label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ category.category_name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">英語版</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                    class="col-md-3 col-form-label text-md-right"
                                                    for="text-input"
                                            >カテゴリ名:<span class="glyphicon glyphicon-star"
                                            ></span
                                            ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                        class="form-control"
                                                        name="category_name"
                                                        v-model="categoryInfoEx.category_name"
                                                        v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('category_name')"
                                                >
                                                    {{ errors.first("category_name") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlCategoryDetail" class="btn btn-default w-100">閉じる</a>
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
                    category_name: {
                        required: "カテゴリ名を入力してください。",
                        max: "カテゴリ名は255文字以内で入力してください。",
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
                categoryInfoEx: {
                    category_name : this.categoryInfo ? this.categoryInfo.category_name : '',
                    category_id : this.category.category_id,
                    lang : this.lang
                }
            };
        },
        props: ["urlAction", "urlCategoryDetail", "categoryInfo", "category", "lang"],
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
                    .post(that.urlAction, that.categoryInfoEx)
                    .then(response => {
                        that.flagShowLoader = false;
                        if (response.data.status == "OK") {
                            this.$swal({
                                text: "コースカテゴリ多言語編集が完了しました。",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(result => {
                                window.location.href = that.urlCategoryDetail;
                            });
                        }
                    })
                    .catch(e => {
                        this.flagShowLoader = false;
                    });
            }
        }
    };
</script>
