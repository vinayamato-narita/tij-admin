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
                                                >オーダーID</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="order_id"
                                                    v-model="paymentInfoEx.order_id"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >学習者番号</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="student_id"
                                                    v-model="paymentInfoEx.student_id"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >受注日時</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="payment_date"
                                                    v-model="paymentInfoEx.payment_date"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >学習者名</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="j_student_name"
                                                    v-model="paymentInfoEx.j_student_name"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >法人名</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="j_company_name"
                                                    v-model="paymentInfoEx.j_company_name"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >コース名</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="item_name"
                                                    v-model="paymentInfoEx.item_name"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >売上</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="amount"
                                                    v-model="paymentInfoEx.amount"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >消費税</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="tax"
                                                    v-model="paymentInfoEx.tax"
                                                    :disabled="true"
                                                />
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
                                                    :class="paymentInfoEx.is_lms_user != 1 ? 'disabledSelect' : ''"
                                                    name="payment_way"
                                                    v-model="paymentInfoEx.payment_way"
                                                    v-validate="'required'"
                                                    :disabled="paymentInfoEx.is_lms_user != 1"
                                                >
                                                    <option :value="key" v-for="(value, key) in paymentInfoEx.payment_ways">
                                                        {{ value }}</option
                                                    >
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >付与回数</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="point_count"
                                                    v-model="paymentInfoEx.point_count"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >入金日</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="j_receive_payment_date"
                                                    v-model="paymentInfoEx.j_receive_payment_date"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>

                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center display-flex">
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

export default {
    created: function() {
        let messError = {
            custom: {
                corporation_code: {
                    payment_checkHankaku: "利用できる文字は「0-9」,「０-９」,「-」のみです",
                    max: "No.法人コードは255文字以内で入力してください",
                },
                customer_code: {
                    max: "得意先コードは255文字以内で入力してください",
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
            paymentInfoEx: this.paymentInfo
        };
    },
    props: ["urlAction", "urlPaymentHistoryList", "paymentInfo"],
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
                .put(that.urlAction, that.paymentInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "支払い履歴編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = this.urlPaymentHistoryList;
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
