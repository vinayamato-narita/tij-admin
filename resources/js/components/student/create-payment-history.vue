<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            支払い履歴新規作成
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save">
                                    <div class="card-header">
                                        <h5 class="title-page">支払い履歴情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >学習者番号</label
                                            >
                                            <div class="col-md-3 pt-7">
                                                {{ studentInfoEx.student_id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >学習者名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ studentInfoEx.student_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >支払方法</label
                                            >
                                            <div class="col-md-6">
                                                <select
                                                    class="form-control"
                                                    name="payment_way"
                                                    v-model="studentInfoEx.payment_way"
                                                >
                                                    <option :value="value" v-for="(name, value) in payment_ways">
                                                        {{ name }}</option
                                                    >
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >コース名<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                 <select
                                                    class="form-control"
                                                    name="course_id"
                                                    v-model="studentInfoEx.course_id"
                                                    v-validate="'required'"
                                                    @change = changeCourse
                                                >
                                                    <option :value="course.course_id" v-for="course in courseList">
                                                        {{ course.course_name }}</option
                                                    >
                                                </select>
                                                
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('course_id')"
                                                >
                                                    {{ errors.first("course_id") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >付与受講回数</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                <input
                                                    class="form-control"
                                                    name="point_count"
                                                    v-model="studentInfoEx.point_count"
                                                    type="hidden"
                                                />
                                                <div
                                                    class="point_count"
                                                >
                                                    {{ studentInfoEx.point_count }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >受注日<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <date-picker
                                                    name="payment_date"
                                                    v-model="studentInfoEx.payment_date"
                                                    :format="'YYYY/MM/DD'"
                                                    type="date"
                                                    v-validate="
                                                        'required'
                                                    "
                                                ></date-picker>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('payment_date')"
                                                >
                                                    {{ errors.first("payment_date") }}
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >受講開始日<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <date-picker
                                                    name="begin_date"
                                                    v-model="studentInfoEx.begin_date"
                                                    :format="'YYYY/MM/DD'"
                                                    type="date"
                                                    v-validate="
                                                        'required'
                                                    "
                                                ></date-picker>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('begin_date')"
                                                >
                                                    {{ errors.first("begin_date") }}
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >支払い金額<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-2">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="amount"
                                                    v-model="studentInfoEx.amount"
                                                    v-validate="
                                                        'required|decimal|min_value:0|max_value:1000000000'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('amount')"
                                                >
                                                    {{ errors.first("amount") }}
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlPaymentHistoryList" class="btn btn-default w-100">閉じる</a>
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
import moment from "moment-timezone";

export default {
    created: function() {
        let messError = {
            custom: {
                course_id: {
                    required: "コース名を選択してください",
                },
                payment_date: {
                    required: "受注日を入力してください",
                },
                begin_date: {
                    required: "受講開始日を入力してください",
                },
                amount: {
                    required: "支払い金額を入力してください",
                    decimal: "支払い金額は半角数字を入力してください",
                    min_value: "支払い金額は0以上で入力してください",
                    max_value: "支払い金額は0～1000000000 を入力してください",
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
            payment_ways: this.studentInfo.payment_ways,
            courseList: this.studentInfo.course_list,
            studentInfoEx: {
                student_id: this.studentInfo.student_id,
                student_name: this.studentInfo.student_name,
                is_lms_user: this.studentInfo.is_lms_user,
                point_count: this.studentInfo.point_count,
                payment_date: new Date(this.studentInfo.payment_date),
                begin_date: new Date(this.studentInfo.begin_date),
                amount: new Date(this.studentInfo.amount),
                payment_way: this.studentInfo.payment_way,
            }
        };
    },
    props: ["urlAction", "urlPaymentHistoryList", "studentInfo"],
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

            that.studentInfoEx.payment_date = moment(that.studentInfoEx.payment_date).format('YYYY-MM-DD');
            that.studentInfoEx.begin_date = moment(that.studentInfoEx.begin_date).format('YYYY-MM-DD');
            
            axios
                .post(that.urlAction, that.studentInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "支払い履歴新規作成が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlPaymentHistoryList;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
        changeCourse(event) {
            let that = this;
            let courseId = event.target.value;
            let courseList = this.courseList;
            for (var i = 0; i < courseList.length; i++) {
                if (courseList[i]['course_id'] == courseId) {
                    let course = courseList[i];
                    this.studentInfoEx.point_count = course['point_count'];
                    this.studentInfoEx.amount = course['price'];
                    
                }
            }
        },
        changeStartDate (event) {
            if (!this.studentInfoEx.is_lms_user) {
                this.studentInfoEx.begin_date = event != null ? new Date(event) : ""
            }
        }
    }
};
</script>
