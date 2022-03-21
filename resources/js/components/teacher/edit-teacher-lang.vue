<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            講師情報多言語編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">講師情報</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >講師名:
                                            </label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ teacherInfoEx.teacher_name }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                            for="text-input"
                                            >ニックネーム :</label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ teacherInfoEx.teacher_nickname }}
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
                                        <h5 class="title-page">{{ teacherInfoEx.title }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >講師名<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="teacher_name_lang"
                                                    v-model="teacherInfoEx.teacher_name_lang"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('teacher_name_lang')"
                                                >
                                                    {{ errors.first("teacher_name_lang") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >ニックネーム<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="teacher_nickname_lang"
                                                    v-model="teacherInfoEx.teacher_nickname_lang"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('teacher_nickname_lang')"
                                                >
                                                    {{ errors.first("teacher_nickname_lang") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >出身国</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="teacher_university_lang"
                                                    v-model="teacherInfoEx.teacher_university_lang"
                                                    v-validate="
                                                        'max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('teacher_university_lang')"
                                                >
                                                    {{ errors.first("teacher_university_lang") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >居住地</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="teacher_department_lang"
                                                    v-model="teacherInfoEx.teacher_department_lang"
                                                    v-validate="
                                                        'max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('teacher_department_lang')"
                                                >
                                                    {{ errors.first("teacher_department_lang") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >自己紹介</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows = "5"
                                                    name="teacher_introduction_lang"
                                                    v-model="teacherInfoEx.teacher_introduction_lang"
                                                    v-validate="
                                                        'max:20000'
                                                    "
                                                ></textarea>
                                                
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('teacher_introduction_lang')"
                                                >
                                                    {{ errors.first("teacher_introduction_lang") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >管理者からの紹介</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows = "5"
                                                    name="introduce_from_admin_lang"
                                                    v-model="teacherInfoEx.introduce_from_admin_lang"
                                                    v-validate="
                                                        'max:20000'
                                                    "
                                                ></textarea>
                                                
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('introduce_from_admin_lang')"
                                                >
                                                    {{ errors.first("introduce_from_admin_lang") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlTeacherDetail" class="btn btn-default w-100">閉じる</a>
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
                teacher_name_lang: {
                    required: "講師名を入力してください",
                    max: "講師名は255文字以内で入力してください",
                },
                teacher_nickname_lang: {
                    required: "ニックネームを入力してください",
                    max: "ニックネームは255文字以内で入力してください",
                },
                teacher_university_lang: {
                    max: "出身国は255文字以内で入力してください",
                },
                teacher_department_lang: {
                    max: "出身国は255文字以内で入力してください",
                },
                teacher_introduction_lang: {
                    max: "自己紹介は20000文字以内で入力してください",
                },
                introduce_from_admin_lang: {
                    max: "管理者からの紹介は20000文字以内で入力してください",
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
            teacherInfoEx: this.teacherInfo
        };
    },
    props: ["urlAction", "urlTeacherDetail", "teacherInfo"],
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
                .post(that.urlAction, that.teacherInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "講師情報多言語編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlTeacherDetail;
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
