<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            コース多言語編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">コース情報
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                        >コースID :
                                        </label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ course.course_id }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                        >コース名:</label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ course.course_name }}
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
                                        <h5 class="title-page" v-if="lang === 'en'">英語版</h5>
                                        <h5 class="title-page" v-if="lang === 'zh'">中国語版</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                    class="col-md-3 col-form-label text-md-right"
                                                    for="text-input"
                                            >コース名:<span class="glyphicon glyphicon-star"
                                            ></span
                                            ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                        class="form-control"
                                                        name="course_name"
                                                        v-model="courseInfoEx.course_name"
                                                        v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('course_name')"
                                                >
                                                    {{ errors.first("course_name") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                    class="col-md-3 col-form-label text-md-right"
                                                    for="text-input"
                                            >コース概要:</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                        class="form-control"
                                                        name="course_description"
                                                        v-model="courseInfoEx.course_description"
                                                        rows="5"
                                                />

                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('course_description')"
                                                >
                                                    {{ errors.first("course_description") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                    class="col-md-3 col-form-label text-md-right"
                                                    for="text-input"
                                            >コース対象者:</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                        class="form-control"
                                                        name="course_target"
                                                        v-model="courseInfoEx.course_target"
                                                        rows="5"
                                                />

                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('course_target')"
                                                >
                                                    {{ errors.first("course_target") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                    class="col-md-3 col-form-label text-md-right"
                                                    for="text-input"
                                            >到達目標:</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                        class="form-control"
                                                        name="course_attainment_target"
                                                        v-model="courseInfoEx.course_attainment_target"
                                                        rows="5"
                                                />

                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('course_attainment_target')"
                                                >
                                                    {{ errors.first("course_attainment_target") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlCourseDetail" class="btn btn-default w-100">閉じる</a>
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
                    course_name: {
                        required: "コース名を入力してください。",
                        max: "コース名は255文字以内で入力してください。",
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
                courseInfoEx: {
                    course_name : this.courseInfo ? this.courseInfo.course_name : '',
                    course_description :  this.courseInfo ? this.courseInfo.course_description : '',
                    course_target :  this.courseInfo ? this.courseInfo.course_target : '',
                    course_attainment_target :  this.courseInfo ? this.courseInfo.course_attainment_target : '',
                    course_id : this.course.course_id,
                    lang : this.lang
                }
            };
        },
        props: ["urlAction", "urlCourseDetail", "courseInfo", "course", "lang"],
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
                    .post(that.urlAction, that.courseInfoEx)
                    .then(response => {
                        that.flagShowLoader = false;
                        if (response.data.status == "OK") {
                            this.$swal({
                                text: "コース多言語編集が完了しました。",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(result => {
                                window.location.href = that.urlCourseDetail;
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
