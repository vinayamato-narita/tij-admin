<template>


    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            セットコース編集


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">セットコース情報



                                    </div>
                                    <div class="card-body">


                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="isShow"> 学習者に公開する :
                                            </label>

                                            <div class="col-md-6 col-form-label">
                                                <input class=" checkbox" id="isShow" name="isShow"
                                                       type="checkbox"
                                                       @input="changeInput()" v-model="isShow" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isShow">
                                                    公開する
                                                </label>


                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("isShow") }}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="tagValue">タグ :

                                            </label>
                                            <div class="col-md-6">

                                                <multiselect class="" v-model="tagValue"   placeholder="" id="tagValue" name="tagValue" label="name" track-by="code" :options="options" :multiple="true" :taggable="true" ></multiselect>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("tagValue") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="displayOrder">表示順:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="displayOrder" type="number" name="displayOrder" @input="changeInput()" style="max-width: 100px" v-model="displayOrder" value="1" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("displayOrder") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="courseName">セットコース名:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="courseName" type="text" name="courseName" @input="changeInput()"  v-model="courseName"  v-validate="'required|max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("courseName") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="courseNameShort">セットコース短縮名:

                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="courseNameShort" type="text" name="courseNameShort" @input="changeInput()"  v-model="courseNameShort"  v-validate="'max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("courseNameShort") }}
                                                </div>


                                            </div>
                                        </div>





                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="amount">セットコース価格（税抜）:

                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="amount" type="text" name="amount" @input="changeInput()" disabled v-model="amount"  onKeyDown="return false" v-validate="'decimal|min_value:0|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("amount") }}
                                                </div>

                                            </div>
                                        </div>




                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="isCampaign"> キャンペーンコース :
                                            </label>

                                            <div class="col-md-6 col-form-label">
                                                <input class=" checkbox" id="isCampaign" name="isCampaign"
                                                       type="checkbox"
                                                       @input="changeInput()" v-model="isCampaign" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isShow">
                                                    キャンペーンコースに登録する
                                                </label>


                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("isCampaign") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="campaignCode">キャンペーンコード              :

                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="campaignCode" type="text" name="campaignCode" @input="changeInput()"  v-model="campaignCode"  v-validate="'max:8'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("campaignCode") }}
                                                </div>


                                            </div>
                                        </div>


                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="courseDescription"> コース概要 : </label>

                                            <div class="col-md-6">
                                                            <textarea class="form-control" id="courseDescription"  name="courseDescription"
                                                                      @input="changeInput()"  v-model="courseDescription">
                                                            </textarea>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("courseDescription") }}
                                                </div>

                                            </div>
                                        </div>





                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="isCampaign"> LMS表示用 :

                                            </label>

                                            <div class="col-md-6 col-form-label">
                                                <input class=" checkbox" id="isForLMS" name="isForLMS"
                                                       type="checkbox"
                                                       @input="changeInput()" v-model="isForLMS" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isForLMS">
                                                    LMS表示用
                                                </label>


                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("isForLMS") }}
                                                </div>

                                            </div>
                                        </div>




                                        <div class="form-actions text-center">
                                            <div class="line"></div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                    <a :href="listCourseUrl" class="btn btn-default w-100">閉じる</a>
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
            this.loadTags();

            let messError = {
                custom: {
                    displayOrder : {
                        require: "表示順を入力してください",
                        min_value: "表示順は1～1000000000 を入力してください",
                        max_value: "表示順は1～1000000000 を入力してください"
                    },
                    courseName: {
                        required: "コース名を入力してください",
                        max: "コース名は255文字以内で入力してください。",
                    },
                    courseNameShort: {
                        max: "短縮名は255文字以内で入力してください。",
                    },
                    pointCount : {
                        require: "付与チケット数はを入力してください",
                        min_value: "付与チケット数は1～1000000000 を入力してください",
                        max_value: "付与チケット数は1～1000000000 を入力してください"
                    },
                    pointExpireDay : {
                        require: "チケット有効日数はを入力してください",
                        min_value: "チケット有効日数は1～1000000000 を入力してください",
                        max_value: "チケット有効日数は1～1000000000 を入力してください"
                    },
                    maxReverseCount : {
                        require: "レッスン予約可能最大数はを入力してください",
                        min_value: "レッスン予約可能最大数は1～1000000000 を入力してください",
                        max_value: "レッスン予約可能最大数は1～1000000000 を入力してください"
                    },
                    amount : {
                        require: "価格（税抜）はを入力してください",
                        min_value: "価格（税抜）は0～1000000000 を入力してください",
                        max_value: "価格（税抜）は0～1000000000 を入力してください"
                    },
                    campaignCode : {
                        max: "キャンペーンコードは8文字以内で入力してください。"
                    },
                    reverseStart : {
                        min_value: "レッスン予約制限は1～1000000000 を入力してください",
                        max_value: "レッスン予約制限は1～1000000000 を入力してください",
                        custom_compare: "ToはFromを超えないで設定してください"
                    },
                    reverseEnd : {
                        min_value: "レッスン予約制限は1～1000000000 を入力してください",
                        max_value: "レッスン予約制限は1～1000000000 を入力してください",
                        custom_compare: "ToはFromを超えないで設定してください"
                    },

                },
            };
            this.$validator.localize("en", messError);
            let  that = this;
            this.course.tags.forEach(function (e) {
                that.tagValue.push({name: e.tag_name, code: e.tag_id});
            })
        },
        components: {
            Loader,
        },
        computed : {
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
                isShow : this.course.is_show,
                tagValue: [],
                tagIds: [],
                options: [],
                displayOrder: this.course.display_order,
                courseName : this.course.course_name,
                courseNameShort: this.course.course_name_short,
                amount : this.course.amount,
                isCampaign : this.course.is_campaign,
                campaignCode : this.course.campaign_code,
                courseDescription : this.course.course_description,
                isForLMS : this.course.is_for_lms,
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},
                errorGetCourse: '',

            };
        },
        props: ["listCourseUrl", "updateUrl", "tags", 'getCourse', 'course', 'detailUrl'],
        mounted() {},
        methods: {
            loadTags() {
                let that = this;
                this.tags.forEach(function (e) {
                    that.options.push({name: e.tag_name, code: e.tag_id})
                });
            },
            convertTagIds() {
                let that = this;
                this.tagValue.forEach(function (e) {
                    that.tagIds.push(e.code)
                });
            },
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("isShow", this.isShow);
                this.convertTagIds();
                formData.append("tagIds", this.tagIds);
                formData.append("displayOrder", this.displayOrder);
                formData.append("courseNameShort", this.courseNameShort);
                formData.append("courseName", this.courseName);
                formData.append("amount", this.amount);
                formData.append("isCampaign", this.isCampaign);
                formData.append("campaignCode", this.campaignCode);
                formData.append("courseDescription", this.courseDescription);
                formData.append("isForLMS", this.isForLMS);
                formData.append('id', this.course.course_id);
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
                                    title: "コース新規作成が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = that.detailUrl;
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
                                            title: "失敗したデータを追加しました",
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
