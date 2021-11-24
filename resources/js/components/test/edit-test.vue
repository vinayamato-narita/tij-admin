<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            edit test
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">edit test</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >テスト種別<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-3">
                                                <select
                                                    class="form-control"
                                                    name="test_type"
                                                    v-model="testInfoEx.test_type"
                                                    v-validate="'required'"
                                                >
                                                    <option :value="key" v-for="(value, key) in testTypes">
                                                        {{ value }}</option
                                                    >
                                                </select>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('test_type')"
                                                >
                                                    {{ errors.first("test_type") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >テスト名<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-3">
                                                <input
                                                    class="form-control"
                                                    name="test_name"
                                                    v-model="testInfoEx.test_name"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('test_name')"
                                                >
                                                    {{ errors.first("test_name") }}
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
                                                    name="test_description"
                                                    v-model="testInfoEx.test_description"
                                                    v-validate="
                                                        'max:20000'
                                                    "
                                                ></textarea>
                                                
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('test_description')"
                                                >
                                                    {{ errors.first("test_description") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >制限時間</label
                                            >
                                            <div class="col-md-3">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="execution_time"
                                                    v-model="testInfoEx.execution_time"
                                                    v-validate="
                                                        'decimal|min_value:0|max_value:1000000000'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('execution_time')"
                                                >
                                                    {{ errors.first("execution_time") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >受講回数</label
                                            >
                                            <div class="col-md-3">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="expire_count"
                                                    v-model="testInfoEx.expire_count"
                                                    v-validate="
                                                        'decimal|min_value:0|max_value:1000000000'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('expire_count')"
                                                >
                                                    {{ errors.first("expire_count") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >合格点<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-3">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="passing_score"
                                                    v-model="testInfoEx.passing_score"
                                                    v-validate="
                                                        'required|decimal|min_value:0|max_value:1000000000'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('passing_score')"
                                                >
                                                    {{ errors.first("passing_score") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >合計点</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ testInfoEx.total_score }}
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlTestShow" class="btn btn-default w-100">閉じる</a>
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
                test_type: {
                    required: "テスト種別を入力してください",
                },
                test_name: {
                    required: "テスト名を入力してください",
                    max: "テスト名は255文字以内で入力してください",
                },
                test_description: {
                    required: "説明を入力してください",
                    max: "説明は20000文字以内で入力してください",
                },
                execution_time: {
                    decimal: "制限時間は半角数字を入力してください",
                    min_value: "制限時間は1～1000000000 を入力してください",
                    max_value: "制限時間は1～1000000000 を入力してください",
                },
                expire_count: {
                    decimal: "受講回数は半角数字を入力してください",
                    min_value: "受講回数は1～1000000000 を入力してください",
                    max_value: "受講回数は1～1000000000 を入力してください",
                },
                passing_score: {
                    required: "合格点を入力してください",
                    decimal: "合格点は半角数字を入力してください",
                    min_value: "合格点は1～1000000000 を入力してください",
                    max_value: "合格点は1～1000000000 を入力してください",
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
            testInfoEx: this.testInfo
        };
    },
    props: ["urlAction", "urlTestShow", "testTypes", "testInfo"],
    mounted() {
        
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
                })
                .catch(function(e) {});
        },
        submit(e) {
            let that = this;
            axios
                .put(that.urlAction, that.testInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "update test success",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = this.urlTestShow;
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
