<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            支払い履歴編集
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
                                                {{ paymentInfoEx.student_id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >学習者名</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ paymentInfoEx.student_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >受注番号</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                {{ paymentInfoEx.point_subscription_history_id }}
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
                                                    v-model="paymentInfoEx.payment_way"
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
                                            <div class="col-md-6 pt-7">
                                                {{ paymentInfoEx.course_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >付与受講回数</label
                                            >
                                            <div class="col-md-6 pt-7">
                                                <div
                                                    class="point_count"
                                                >
                                                    {{ paymentInfoEx.point_count }}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- test -->
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >Test</label
                                            >
                                            <div class="col-md-6">
                                                <date-picker 
                                                    :format="'YYYY/MM/DD'" 
                                                    type="date" 
                                                    readonly="readonly" 
                                                    name="lesson_date_from" 
                                                    id="lesson_date_from" 
                                                    v-model="startDate"
                                                >
                                                </date-picker>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >Test 2</label
                                            >
                                            <div class="col-md-6">
                                                <date-picker 
                                                    :format="'YYYY/MM/DD'" 
                                                    type="date" 
                                                    readonly="readonly" 
                                                    name="lesson_date_to" 
                                                    id="lesson_date_to" 
                                                    v-model="endDate"
                                                >
                                                </date-picker>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('lesson_date_to')"
                                                >
                                                    {{ errors.first("lesson_date_to") }}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end test -->
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
                                                    :format="'YYYY/MM/DD'" 
                                                    type="date" 
                                                    readonly="readonly" 
                                                    name="payment_date" 
                                                    id="payment_date" 
                                                    v-model="paymentInfoEx.payment_date"
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
                                                    :format="'YYYY/MM/DD'" 
                                                    type="date" 
                                                    readonly="readonly" 
                                                    name="begin_date" 
                                                    id="begin_date" 
                                                    v-model="begin_date"
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

                                        <div class="form-group row" v-if="this.paymentInfo.course_type !== 1">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >有効期限日<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-6">
                                                <date-picker
                                                    :format="'YYYY/MM/DD'" 
                                                    type="date" 
                                                    readonly="readonly" 
                                                    name="point_expire_date" 
                                                    id="point_expire_date" 
                                                    v-model="point_expire_date"
                                                ></date-picker>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('point_expire_date')"
                                                >
                                                    {{ errors.first("point_expire_date") }}
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
                                                    v-model="paymentInfoEx.amount"
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

                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >消費税<span class="glyphicon glyphicon-star"
                                                    ></span
                                                ></label
                                            >
                                            <div class="col-md-2">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    name="tax"
                                                    v-model="paymentInfoEx.tax"
                                                    v-validate="
                                                        'required|decimal|min_value:0|max_value:1000000000'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('tax')"
                                                >
                                                    {{ errors.first("tax") }}
                                                </div>
                                            </div>
                                        </div> 

                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <button type="button" class="btn btn-danger w-100 mr-2" @click="destroyPaymentHistory">削除</button>
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
                point_expire_date: {
                    required: "有効期限日を入力してください",
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
                tax: {
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
            payment_ways: this.paymentInfo.payment_ways,
            paymentInfoEx: {
                point_subscription_history_id: this.paymentInfo.point_subscription_history_id,
                student_id: this.paymentInfo.student_id,
                student_name: this.paymentInfo.student_name,
                payment_way: this.paymentInfo.payment_way,
                course_id: this.paymentInfo.course_id,
                course_name: this.paymentInfo.course_name,
                is_lms_user: this.paymentInfo.is_lms_user,
                point_count: this.paymentInfo.point_count,
                payment_date: new Date(this.paymentInfo.payment_date),
                begin_date: new Date(this.paymentInfo.begin_date),
                point_expire_date: new Date(this.paymentInfo.point_expire_date),
                amount: this.paymentInfo.amount,
                tax: this.paymentInfo.tax,
            },
            begin_date: new Date(this.paymentInfo.begin_date),
            point_expire_date: new Date(this.paymentInfo.point_expire_date),
            startDate : new Date(),
            endDate : new Date(this.paymentInfo.payment_date),
        };
    },
    props: ["urlAction", "urlDestroyPaymentHistory", "urlPaymentHistoryList", "paymentInfo"],
    mounted() {},
    methods: {
        disabledBeforeToday(date) {
            return date < new Date(new Date().setHours(0, 0, 0, 0));
        },
        save() {
            let that = this;
            this.$validator
                .validateAll()
                .then(valid => {
                    if (valid) {
                        let date1 = moment(that.paymentInfo.point_expire_date).format("YYYY/MM/DD");
                        let date2 = moment(that.paymentInfoEx.point_expire_date).format("YYYY/MM/DD");

                        that.paymentInfoEx.payment_date = moment(that.paymentInfoEx.payment_date).format('YYYY-MM-DD');
                        that.paymentInfoEx.begin_date = moment(that.paymentInfoEx.begin_date).format('YYYY-MM-DD');
                        that.paymentInfoEx.point_expire_date = moment(that.paymentInfoEx.point_expire_date).format('YYYY-MM-DD');
                        
                        if (that.paymentInfo.is_payment_expired == 1 && date1 != date2) {
                            that.$swal({
                                title: 'この履歴は"期限失効"となっています。"期限失効"データを削除して、有効期限を変更しますか？',
                                icon: "warning",
                                confirmButtonText: "OK",
                                cancelButtonText: "キャンセル",
                                showCancelButton: true
                              }).then(result => {
                                if (result.value) {
                                    that.flagShowLoader = true;
                                    that.submit();
                                }
                              });
                        }else {
                            that.flagShowLoader = true;
                            that.submit();
                        }
                        
                    }
                })
                .catch(function(e) {});
        },
        submit(e) {
            let that = this;

            axios
                .post(that.urlAction, that.paymentInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "支払い履歴編集が完了しました。",
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
        destroyPaymentHistory() {
            let that = this;
            const inputOptions = {
                0 : '残さない',
                1: '残す',
            };
            this.$swal({
                text: "レッスンキャンセル履歴を残しますか？",
                input: 'radio',
                inputOptions: inputOptions,
                confirmButtonText: "削除する",
                showCancelButton: true,
                cancelButtonText: "閉じる",
                inputValue: 1,
            }).then(result => {
                if (result.isConfirmed) {
                    that.flagShowLoader = true;
                    that.submitDestroyPaymentHistory(result.value);
                }
            });
        },
        submitDestroyPaymentHistory(type) {
            let that = this;
            axios
                .post(that.urlDestroyPaymentHistory, {
                    cancel_type: type,
                    point_subscription_history_id: that.paymentInfoEx.point_subscription_history_id,
                    _token: Laravel.csrfToken
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "支払履歴削除が完了しました。",
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
    }
};
</script>
