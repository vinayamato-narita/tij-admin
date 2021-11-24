<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            レッスン多言語編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">レッスン情報
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                        >レッスンID :
                                        </label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ lesson.lesson_id }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                        >レッスン名:</label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ lesson.lesson_name }}
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
                                            >レッスン名:<span class="glyphicon glyphicon-star"
                                            ></span
                                            ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                        class="form-control"
                                                        name="lesson_name"
                                                        v-model="lessonInfoEx.lesson_name"
                                                        v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('lesson_name')"
                                                >
                                                    {{ errors.first("lesson_name") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                    class="col-md-3 col-form-label text-md-right"
                                                    for="text-input"
                                            >レッスン概要</label
                                            >
                                            <div class="col-md-6">
                                                <textarea
                                                        class="form-control"
                                                        name="lesson_description"
                                                        v-model="lessonInfoEx.lesson_description"
                                                        rows="5"
                                                />

                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('lesson_description')"
                                                >
                                                    {{ errors.first("lesson_description") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlLessonDetail" class="btn btn-default w-100">閉じる</a>
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
                    lesson_name: {
                        required: "レッスン名を入力してください。",
                        max: "レッスン名は255文字以内で入力してください。",
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
                lessonInfoEx: {
                    lesson_name : this.lessonInfo ? this.lessonInfo.lesson_name : '',
                    lesson_description :  this.lessonInfo ? this.lessonInfo.lesson_description : '',
                    lesson_id : this.lesson.lesson_id,
                    lang : this.lang
                }
            };
        },
        props: ["urlAction", "urlLessonDetail", "lessonInfo", "lesson", "lang"],
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
                    .post(that.urlAction, that.lessonInfoEx)
                    .then(response => {
                        that.flagShowLoader = false;
                        if (response.data.status == "OK") {
                            this.$swal({
                                text: "レッスン多言語編集が完了しました。",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(result => {
                                window.location.href = that.urlLessonDetail;
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
