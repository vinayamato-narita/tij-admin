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
                                                >生徒番号</label
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
                                                >生徒名</label
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
                                                >企業名</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="company_name"
                                                    v-model="paymentInfoEx.company_name"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >企業ID</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="j_project_code"
                                                    v-model="paymentInfoEx.j_project_code"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >法人コード</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="corporation_code"
                                                    v-model="paymentInfoEx.corporation_code"
                                                    v-validate="
                                                        'payment_checkHankaku|max:255'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('corporation_code')"
                                                >
                                                    {{ errors.first("corporation_code") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >得意先コード</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="customer_code"
                                                    v-model="paymentInfoEx.customer_code"
                                                    v-validate="
                                                        'max:255'
                                                    "
                                                />
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('customer_code')"
                                                >
                                                    {{ errors.first("customer_code") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >商品名</label
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
                                                >キャンペーンコード</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="j_campaign_code"
                                                    v-model="paymentInfoEx.j_campaign_code"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >支払い状況</label
                                            >
                                            <div class="col-md-6">
                                                <select
                                                    class="form-control"
                                                    :class="paymentInfoEx.is_lms_user != 1 ? 'disabledSelect' : ''"
                                                    name="j_paid_status"
                                                    v-model="paymentInfoEx.j_paid_status"
                                                    v-validate="'required'"
                                                    :disabled="paymentInfoEx.is_lms_user != 1"
                                                >
                                                    <option :value="key" v-for="(value, key) in paymentInfoEx.payment_types">
                                                        {{ value }}</option
                                                    >
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >ポイント数</label
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
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >支払期限</label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    name="j_payment_term"
                                                    v-model="paymentInfoEx.j_payment_term"
                                                    :disabled="true"
                                                />
                                            </div>
                                        </div>

                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center display-flex">
                                                <button type="submit" class="btn btn-primary w-100 mr-2" :disabled="adminCanEdit == 0">登録</button>
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
    props: ["urlAction", "urlPaymentHistoryList", "paymentInfo", "adminCanEdit"],
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
