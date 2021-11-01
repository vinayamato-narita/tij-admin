<template>


    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            コース新規作成
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">コース情報

                                    </div>
                                    <div class="card-body">


                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="isShow"> 生徒に公開する :
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
                                                <input class="form-control" id="displayOrder" type="number" name="displayOrder" @input="changeInput()" style="max-width: 100px" v-model="displayOrder" value="1" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("displayOrder") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="courseName">コース名:
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
                                            <label class="col-md-3 col-form-label text-md-right" for="courseNameShort">短縮名              :

                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="courseNameShort" type="text" name="courseNameShort" @input="changeInput()"  v-model="courseNameShort"  v-validate="'max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("courseNameShort") }}
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="pointCount">付与チケット数:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="pointCount" type="number" name="pointCount" @input="changeInput()" style="max-width: 100px" v-model="pointCount" value="1" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("pointCount") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="pointExpireDay">チケット有効日数 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="pointExpireDay" type="number" name="pointExpireDay" @input="changeInput()" style="max-width: 100px" v-model="pointExpireDay" value="1" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("pointExpireDay") }}
                                                </div>

                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="maxReverseCount">レッスン予約可能最大数  :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="maxReverseCount" type="number" name="maxReverseCount" @input="changeInput()" style="max-width: 100px" v-model="maxReverseCount" value="1" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("maxReverseCount") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="amount">価格（税抜）  :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="amount" type="number" name="amount" @input="changeInput()" style="max-width: 100px" v-model="amount" value="1" onKeyDown="return false" v-validate="'decimal|min_value:0|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("amount") }}
                                                </div>

                                            </div>
                                        </div>


                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="paypalItemNumber">商品コード
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="paypalItemNumber" type="text" name="paypalItemNumber" @input="changeInput()"  v-model="paypalItemNumber"  v-validate="'max:45'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("paypalItemNumber") }}
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
                                                    {{ errors.first("isShow") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="campaignCode">キャンペーンコード              :
                                                :
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
                                            <label class="col-md-3 col-form-label text-md-right">
                                                レッスン時間制限:
                                            </label>
                                            <div class="col-md-6 col-form-label">
                                                <input class=" checkbox" id="isScheduleLimit_0" name="isScheduleLimit"
                                                       type="radio"
                                                       value="0"
                                                       @input="changeInput()" v-model="isScheduleLimit" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isScheduleLimit_0">
                                                    制限なし
                                                </label>
                                                <input class=" checkbox" id="isScheduleLimit_1" name="isScheduleLimit"
                                                       type="radio"
                                                       value="1"
                                                       @input="changeInput()" v-model="isScheduleLimit" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isScheduleLimit_1">
                                                    制限あり
                                                </label>


                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("isScheduleLimit") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="reverseStart">レッスン予約制限  :

                                            </label>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="row">
                                                    <input class="form-control col-md-3 col-sm-3" id="reverseStart" type="number" name="reverseStart" @input="changeInput()" style="max-width: 100px" v-model="reverseStart" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000|custom_compare'" />

                                                    <div class="col-md-2 text-center p-2 col-sm-2 ">分前 ~</div>

                                                    <input class="form-control col-md-3 col-sm-3" id="reverseEnd" type="number" name="reverseEnd" @input="changeInput()" style="max-width: 100px" v-model="reverseEnd" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000|custom_compare'" />

                                                    <div class="col-md-2 text-center p-2 col-sm-2">分前 </div>

                                                </div>




                                                <div class="input-group is-danger" role="alert" v-if="errors.first('reverseStart')">
                                                    {{ errors.first("reverseStart") }}

                                                </div>
                                                <div class="input-group is-danger" role="alert" v-if="errors.first('reverseEnd')">

                                                    {{ errors.first("reverseEnd") }}
                                                </div>

                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="cancelEnd">キャンセル時間制限    :

                                            </label>
                                            <div class="col-md-6 col-sm-6 row">
                                                    <input class="form-control col-md-3 col-sm-3" id="cancelEnd" type="number" name="cancelEnd" @input="changeInput()" style="max-width: 100px" v-model="cancelEnd" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                    <div class="col-md-2 text-center p-2 col-sm-2 ">分前まで</div>



                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("cancelEnd") }}
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
                                                    <a :href="listTextUrl" class="btn btn-default w-100">閉じる</a>
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
                    paypalItemNumber : {
                        max: "商品コードは45文字以内で入力してください。"
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
            let that = this;
            this.$validator.extend("custom_compare", {
                validate(value, args) {
                    if (that.reverseStart != '' && that.reverseStart < that.reverseEnd) return {valid: false }
                    return {valid: true}
                }
            });
        },
        components: {
            Loader,
        },
        computed : {
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
                isShow : true,
                tagValue: [],
                tagIds: [],
                options: [],
                displayOrder: 1,
                courseDesciption: '',
                courseName : '',
                courseNameShort: '',
                pointExpireDay: 1,
                pointCount: 1,
                maxReverseCount : 1,
                amount : 0,
                paypalItemNumber : '',
                isCampaign : false,
                campaignCode : '',
                courseDescription : '',
                isScheduleLimit : 0,
                reverseEnd : 60,
                reverseStart : '',
                cancelEnd : 720,
                isForLMS : true,
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},
            };
        },
        props: ["listCourseUrl", "createUrl", "tag"],
        mounted() {},
        methods: {
            loadTags() {
                let that = this;
                this.tag.forEach(function (e) {
                    that.options.push({name: e.tag_name, code: e.id})
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
                formData.append("pointExpireDay", this.pointExpireDay);
                formData.append("pointCount", this.pointCount);
                formData.append("maxReverseCount", this.maxReverseCount);
                formData.append("amount", this.amount);
                formData.append("paypalItemNumber", this.paypalItemNumber);
                formData.append("isCampaign", this.isCampaign);
                formData.append("campaignCode", this.campaignCode);
                formData.append("courseDescription", this.courseDescription);
                formData.append("isScheduleLimit", this.isScheduleLimit);
                formData.append("reverseEnd", this.reverseEnd);
                formData.append("reverseStart", this.reverseStart);
                formData.append("cancelEnd", this.cancelEnd);
                formData.append("isForLMS", this.isForLMS);
                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.createUrl , formData, {
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
                                });
                                that.flagShowLoader = false;
                                window.location.href = this.listCourseUrl;
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
