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
                                            <label class="col-md-3 col-form-label text-md-right" > 公開日時 :
                                                <span class="glyphicon glyphicon-star"
                                            ></span>
                                                </label>

                                            <div class="col-md-9 col-form-label row">
                                                <div class="row ml-0">
                                                    <div class="col-md-2 pr-0">
                                                        <date-picker
                                                                v-model="publish_date_from_date"
                                                                :format="'YYYY/MM/DD'"
                                                                type="date"
                                                        ></date-picker>
                                                        <input type="hidden" name="publish_date_from_date" v-validate="'required|compare_publish_date'" v-model="publish_date_from_date">


                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("publish_date_from_date") }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 pr-0">
                                                        <date-picker
                                                                v-model="publish_date_from_time"
                                                                :format="'HH:mm'"
                                                                type="time"
                                                        ></date-picker>
                                                        <input type="hidden" name="publish_date_from_date" v-validate="'required'" v-model="publish_date_from_time">

                                                    </div>
                                                    <div class="d-flex align-items-center" style="padding: 0px 10px;height: 34px">
                                                        ～
                                                    </div>


                                                    <div class="col-md-2 pl-0 pr-0">
                                                        <date-picker
                                                                v-model="publish_date_to_date"
                                                                :format="'YYYY/MM/DD'"
                                                                type="date"
                                                        ></date-picker>

                                                        <input type="hidden" name="publish_date_to_date" v-validate="'required'" v-model="publish_date_to_date">

                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("publish_date_to_date") }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 ">
                                                        <date-picker
                                                                v-model="publish_date_to_time"
                                                                :format="'HH:mm'"
                                                                type="time"
                                                        ></date-picker>

                                                        <input type="hidden" name="publish_date_to_date" v-validate="'required'" v-model="publish_date_to_time">

                                                    </div>

                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="displayOrder">表示順 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="displayOrder" type="number" name="displayOrder" @input="changeInput()" style="max-width: 100px" v-model="displayOrder"  onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("displayOrder") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="courseName">コース名 :
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
                                            <label class="col-md-3 col-form-label text-md-right" for="courseNameShort">短縮名 :

                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="courseNameShort" type="text" name="courseNameShort" @input="changeInput()"  v-model="courseNameShort"  v-validate="'max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("courseNameShort") }}
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="pointCount">受講回数 :
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
                                            <label class="col-md-3 col-form-label text-md-right" for="amount">価格（税抜）:
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
                                            <label class="col-md-3 col-form-label text-md-right" for="paypalItemNumber">商品コード :
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="paypalItemNumber" type="text" name="paypalItemNumber" @input="changeInput()"  v-model="paypalItemNumber"  v-validate="'max:45'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("paypalItemNumber") }}
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
                                                法人・個人 :
                                            </label>
                                            <div class="col-md-6 col-form-label">
                                                <input class=" checkbox" id="isForlms_0" name="isForLMS"
                                                       type="radio"
                                                       value="0"
                                                       @input="changeInput()" v-model="isForLMS" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isForlms_0">
                                                    個人
                                                </label>
                                                &nbsp;
                                                <input class=" checkbox" id="isForlms_1" name="isForLMS"
                                                       type="radio"
                                                       value="1"
                                                       @input="changeInput()" v-model="isForLMS" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="isForlms_1">
                                                    法人
                                                </label>


                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("isForLMS") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right">
                                                コースタイプ :
                                            </label>
                                            <div class="col-md-6 col-form-label">
                                                <input class=" checkbox" id="courseType_0" name="courseType"
                                                       type="radio"
                                                       value="0"
                                                       @input="changeInput()" v-model="courseType" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="courseType_0">
                                                    通常コース
                                                </label>
                                                &nbsp;
                                                &nbsp;
                                                <input class=" checkbox" id="courseType_1" name="courseType"
                                                       type="radio"
                                                       value="1"
                                                       @input="changeInput()" v-model="courseType" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="courseType_1">
                                                    実力テスト用コース
                                                </label>
                                                &nbsp;
                                                &nbsp;

                                                <input class=" checkbox" id="courseType_2" name="courseType"
                                                       type="radio"
                                                       value="2"
                                                       @input="changeInput()" v-model="courseType" style="width: auto;height: auto;display: inline-block; ">
                                                <label class="" for="courseType_2">
                                                    グループコース
                                                </label>


                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("courseType") }}
                                                </div>

                                            </div>
                                        </div>


                                        <div class="form-group row " v-if="courseType == 0 || courseType == 1">
                                            <label class="col-md-3 col-form-label text-md-right" for="expireDay">有効日数 :
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" style="max-width: 100px" id="expireDay" type="number" name="expireDay" @input="changeInput()"  v-model="expireDay"  />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("expireDay") }}
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group row " v-if="courseType == 2">
                                            <label class="col-md-3 col-form-label text-md-right" for="minReserveCount">最小開催人数 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">

                                                <input class="form-control" id="minReserveCount" type="number" name="minReserveCount" style="max-width: 100px"  onKeyDown="return false" v-validate="'required|min_value:1|max_value:1000000000|custom_compare'" @input="changeInput()" @change="validateDepend('maxReserveCount')"  v-model="minReserveCount"  />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("minReserveCount") }}
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group row " v-if="courseType == 2">
                                            <label class="col-md-3 col-form-label text-md-right" for="maxReserveCount">最大申込人数 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="maxReserveCount" style="max-width: 100px" onKeyDown="return false" v-validate="'required|min_value:1|max_value:1000000000|custom_compare'" type="number" name="maxReserveCount" @input="changeInput()" @change="validateDepend('minReserveCount')"  v-model="maxReserveCount"  />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("maxReserveCount") }}
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group row " v-if="courseType == 2">
                                            <label class="col-md-3 col-form-label text-md-right" > 開催決定日時 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>

                                            <div class="col-md-9 col-form-label row">
                                                <div class="row ml-0">
                                                    <div class="col-md-4 pr-0">
                                                        <date-picker
                                                                v-model="decideDateDate"
                                                                :format="'YYYY/MM/DD'"
                                                                type="date"
                                                        ></date-picker>
                                                        <input type="hidden" name="decideDateDate" v-validate="'required'" v-model="decideDateDate">


                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("decideDateDate") }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 pr-0">
                                                        <date-picker
                                                                v-model="decideDateTime"
                                                                :format="'HH:mm'"
                                                                type="time"
                                                        ></date-picker>
                                                        <input type="hidden" name="decideDateDate" v-validate="'required'" v-model="decideDateTime">

                                                    </div>


                                                </div>


                                            </div>
                                        </div>
                                        <div class="form-group row " v-if="courseType == 2">
                                            <label class="col-md-3 col-form-label text-md-right" > 申込期限 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>

                                            <div class="col-md-9 col-form-label row">
                                                <div class="row ml-0">
                                                    <div class="col-md-4 pr-0">
                                                        <date-picker
                                                                v-model="reverseEndDateDate"
                                                                :format="'YYYY/MM/DD'"
                                                                type="date"
                                                        ></date-picker>
                                                        <input type="hidden" name="reverseEndDateDate" v-validate="'required'" v-model="reverseEndDateDate">


                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("reverseEndDateDate") }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 pr-0">
                                                        <date-picker
                                                                v-model="reverseEndDateTime"
                                                                :format="'HH:mm'"
                                                                type="time"
                                                        ></date-picker>
                                                        <input type="hidden" name="reverseEndDateDate" v-validate="'required'" v-model="reverseEndDateTime">

                                                    </div>


                                                </div>


                                            </div>
                                        </div>
                                        <div class="form-group row " v-if="courseType == 2">
                                            <label class="col-md-3 col-form-label text-md-right" > 開講日時 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>

                                            <div class="col-md-9 col-form-label row">
                                                <div class="row ml-0">
                                                    <div class="col-md-4 pr-0">
                                                        <date-picker
                                                                v-model="courseStartDateDate"
                                                                :format="'YYYY/MM/DD'"
                                                                type="date"
                                                        ></date-picker>
                                                        <input type="hidden" name="courseStartDateDate" v-validate="'required'" v-model="courseStartDateDate">


                                                        <div class="input-group is-danger" role="alert">
                                                            {{ errors.first("courseStartDateDate") }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 pr-0">
                                                        <date-picker
                                                                v-model="courseStartDateTime"
                                                                :format="'HH:mm'"
                                                                type="time"
                                                        ></date-picker>
                                                        <input type="hidden" name="courseStartDateDate" v-validate="'required'" v-model="courseStartDateTime">

                                                    </div>


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

            let messError = {
                custom: {
                    publish_date_from_date : {
                        required :  "公開開始日時を選択してください",
                        compare_publish_date : "公開開始日時は公開終了日時より小さくなければなりません"
                    },
                    publish_date_to_date : {
                        required :  "公開終了日時を選択してください"
                    },
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
                        require: "受講回数はを入力してください",
                        min_value: "受講回数は1～1000000000 を入力してください",
                        max_value: "受講回数は1～1000000000 を入力してください"
                    },
                    amount : {
                        require: "価格（税抜）はを入力してください",
                        min_value: "価格（税抜）は0～1000000000 を入力してください",
                        max_value: "価格（税抜）は0～1000000000 を入力してください"
                    },
                    paypalItemNumber : {
                        max: "商品コードは45文字以内で入力してください。"
                    },
                    minReserveCount : {
                        require: "最小開催人数はを入力してください",
                        min_value: "最小開催人数は1～1000000000 を入力してください",
                        max_value: "最小開催人数は1～1000000000 を入力してください",
                        custom_compare: "最小開催人数は最大申込人数より小さくなければなりません"
                    },
                    maxReserveCount : {
                        require: "最大申込人数を入力してください",
                        min_value: "最大申込人数は1～1000000000 を入力してください",
                        max_value: "最大申込人数は1～1000000000 を入力してください",
                        custom_compare: "最小開催人数は最大申込人数より小さくなければなりません"
                    },
                    decideDateDate : {
                        required :  "開催決定日時 を選択してください",
                    },
                    reverseEndDateDate : {
                        required :  "申込期限を選択してください"
                    },
                    courseStartDateDate : {
                        required :  "開講日時を選択してください"
                    },



                },
            };
            this.$validator.localize("en", messError);
            let that = this;
            this.$validator.extend("custom_compare", {
                validate(value, args) {
                    if (that.maxReserveCount < that.minReserveCount) return {valid: false }
                    return {valid: true}
                }
            });
            this.$validator.extend("compare_publish_date", {
                validate(value, args) {
                    if (!(that.publish_date_from_date && that.publish_date_from_time && that.publish_date_to_date && that.publish_date_to_time)) return {valid: true };
                    var fromDate = that.publish_date_from_date.setHours(that.publish_date_from_time.getHours(), that.publish_date_from_time.getMinutes());
                    var toDate = that.publish_date_to_date.setHours(that.publish_date_to_time.getHours(), that.publish_date_to_time.getMinutes());
                    return {valid: (fromDate - toDate) < 0 ? true : false}
                }
            });
        },
        components: {
            Loader,
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
                publish_date_from_date : null,
                publish_date_from_time : null,
                publish_date_to_date : null,
                publish_date_to_time : null,
                decideDateTime : null,
                decideDateDate : null,
                reverseEndDateTime :null,
                reverseEndDateDate :null,
                courseStartDateTime : null,
                courseStartDateDate : null,
                maxReserveCount : 1,
                minReserveCount : 1,
                expireDay : 1,
                displayOrder: 1,
                courseDescription : '',
                courseName : '',
                courseNameShort: '',
                pointCount: 1,
                amount : 1,
                paypalItemNumber : '',
                courseType: 0,
                isForLMS : 0,
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},
            };
        },
        props: ["listCourseUrl", "createUrl", "tag"],
        mounted() {},
        computed : {
        },
        methods: {
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("displayOrder", this.displayOrder);
                formData.append("courseNameShort", this.courseNameShort);
                formData.append("courseName", this.courseName);
                formData.append("pointCount", this.pointCount);
                formData.append("maxReserveCount", this.maxReserveCount);
                formData.append("minReserveCount", this.minReserveCount);
                formData.append("amount", this.amount);
                formData.append("paypalItemNumber", this.paypalItemNumber);
                formData.append("courseDescription", this.courseDescription);
                formData.append("isForLMS", this.isForLMS);
                formData.append("courseType", this.courseType);
                formData.append("expireDay", this.expireDay);

                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.publish_date_from_date.setHours(that.publish_date_from_time.getHours(), that.publish_date_from_time.getMinutes());
                        formData.append("fromDate", that.publish_date_from_date.toLocaleString());
                        that.publish_date_to_date.setHours(that.publish_date_to_time.getHours(), that.publish_date_to_time.getMinutes());
                        formData.append("toDate", that.publish_date_to_date.toLocaleString());
                        if (that.courseType == 2) {
                            formData.append("minReserveCount", this.minReserveCount);
                            formData.append("maxReserveCount", this.maxReserveCount);
                            that.decideDateDate.setHours(that.decideDateTime.getHours(), that.decideDateTime.getMinutes())
                            formData.append("decideDate", that.decideDateDate.toLocaleString());
                            that.reverseEndDateDate.setHours(that.reverseEndDateTime.getHours(), that.reverseEndDateTime.getMinutes())
                            formData.append("reverseEndDate", that.reverseEndDateDate.toLocaleString());
                            that.courseStartDateDate.setHours(that.courseStartDateTime.getHours(), that.courseStartDateTime.getMinutes())
                            formData.append("courseStartDate", that.courseStartDateDate.toLocaleString());
                        }
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
            validateDepend(depend) {
                this.$validator.validate(depend)
            }
        },
     }
</script>

