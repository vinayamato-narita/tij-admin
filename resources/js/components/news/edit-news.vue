<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            お知らせ編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save">
                                    <input
                                        name="_token"
                                        type="hidden"
                                        v-model="newsInfo._token"
                                    />
                                    <div class="card-header">
                                        <h5 class="title-page">お知らせ情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >対象<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-3">
                                                <select
                                                    class="form-control"
                                                    name="news_subject_id"
                                                    v-model="newsInfo.news_subject_id"
                                                    v-validate="'required'"
                                                >
                                                    <option :value="subject.news_subject_id" v-for="subject in newsSubjects">
                                                        {{ subject.news_subject_ja }}</option
                                                    >
                                                </select>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('news_subject_id')"
                                                >
                                                    {{ errors.first("news_subject_id") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >タイトル<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="news_title"
                                                    v-model="newsInfo.news_title"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('news_title')"
                                                >
                                                    {{ errors.first("news_title") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >内容<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows = "5"
                                                    name="news_body"
                                                    v-model="newsInfo.news_body"
                                                    v-validate="
                                                        'required|max:20000'
                                                    "
                                                ></textarea>
                                                
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('news_body')"
                                                >
                                                    {{ errors.first("news_body") }}
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
                                                <a :href="urlNewsDetail" class="btn btn-default w-100">閉じる</a>
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
                news_subject_id: {
                    required: "対象を選択してください",
                },
                news_title: {
                    required: "タイトルを入力してください",
                    max: "タイトルは255文字以内で入力してください",
                },
                news_body: {
                    required: "内容を入力してください",
                    max: "内容は20000文字以内で入力してください",
                },
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
        };
    },
    props: ["urlAction", "urlNewsDetail", "newsSubjects", "newsInfo", 'deleteAction', 'messageConfirm', 'urlRedirect'],
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
                .put(that.urlAction, that.newsInfo)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "お知らせ編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = this.urlNewsDetail;
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
