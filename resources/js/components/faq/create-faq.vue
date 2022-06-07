<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            FAQ新規作成
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">FAQ情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                > 表示順<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-3">
                                                <input
                                                    class="form-control"
                                                    type="number"
                                                    name="no_faq"
                                                    v-model="faqInfo.no_faq"
                                                    v-validate="
                                                        'required|decimal|min_value:0|max_value:1000000000'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('no_faq')"
                                                >
                                                    {{ errors.first("no_faq") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >カテゴリ<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <select
                                                    class="form-control"
                                                    name="faq_category_id"
                                                    v-model="faqInfo.faq_category_id"
                                                    v-validate="'required'"
                                                >
                                                    <option :value="category.faq_category_id" v-for="category in faqCategories">
                                                        {{ category.faq_category_name }}</option
                                                    >
                                                </select>

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('faq_category_id')"
                                                >
                                                    {{ errors.first("faq_category_id") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >質問・Q<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="question"
                                                    v-model="faqInfo.question"
                                                    v-validate="
                                                        'required|max:2000'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('question')"
                                                >
                                                    {{ errors.first("question") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >答え・A<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows = "5"
                                                    name="answer"
                                                    v-model="faqInfo.answer"
                                                    v-validate="
                                                        'required|max:2000'
                                                    "
                                                ></textarea>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('answer')"
                                                >
                                                    {{ errors.first("answer") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlFaqList" class="btn btn-default w-100">閉じる</a>
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
                no_faq: {
                    required: "表示順を入力してください",
                    decimal: "表示順は半角数字を入力してください",
                    min_value: "表示順は1～1000000000 を入力してください",
                    max_value: "表示順は1～1000000000 を入力してください",
                },
                faq_category_id: {
                    required: "カテゴリを入力してください",
                },
                question: {
                    required: "質問・Qを入力してください",
                    max: "質問・Qは2000文字以内で入力してください",
                },
                answer: {
                    required: "答え・Aを入力してください",
                    max: "答え・Aは2000文字以内で入力してください"
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
            faqInfo: {
                _token: Laravel.csrfToken
            }
        };
    },
    props: ["urlAction", "urlFaqList", "faqCategories"],
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
                .post(that.urlAction, that.faqInfo)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "FAQ新規作成が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            let id = response.data.id;
                            window.location.href = baseUrl + "/faq/" + id;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
    }
};
</script>
