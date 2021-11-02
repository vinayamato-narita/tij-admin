<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            リマインドメール編集

                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">リマインドメール情報

                                    </div>
                                    <div class="card-body">


                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right">種類 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-4 text-md-left p-2">
                                                {{ this.remindMail.send_remind_mail_timing.send_remind_mail_timing_type_name }}


                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right">送信タイミング :
                                            </label>
                                            <div class="col-md-4 text-md-left p-2">

                                                <select name="timmingMinutes" style="width: 100px" class="form-control valid" id="timmingMinutes" v-model="timmingMinutes" aria-invalid="false">
                                                    <option v-for="tm in this.$attrs['enum']" :value="tm" :selected="(tm == timmingMinutes) ? true : false">
                                                        {{tm}}
                                                    </option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right">メール件名 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-4 text-md-left p-2">
                                                <input class="form-control" id="mailSubject" type="text" name="mailSubject" @input="changeInput()"  v-model="mailSubject"   v-validate="'required|max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("mailSubject") }}
                                                </div>

                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right">メール内容 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-4 text-md-left p-2">
                                                <textarea rows="20" class="form-control" id="mailBody" type="text" name="mailBody" @input="changeInput()"  v-model="mailBody"   v-validate="'required'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("mailBody") }}
                                                </div>

                                            </div>
                                            <div class="col-md-5 text-md-left p-2">
                                                <textarea rows="20" class="form-control" value=""  disabled>
                                                    【無料会員登録】
本登録用URL:　#CONFIRM_URL#
送信先生徒名:　#STUDENT_NAME
【レッスン予約時】
【レッスン予約後】
【レッスンキャンセル時】
【レッスン開始前】
先生名:　#TEACHER_NAME#
先生ニックネーム:　#TEACHER_NICKNAME#
生徒名:　#STUDENT_NAME#
生徒ニックネーム:　#STUDENT_NICKNAME#
レッスン日:　#LESSON_DATE#
レッスン時間:　#LESSON_TIME#
レッスン名:　#LESSON_NAME#
テキスト名:　#LESSON_TEXT_NAME#
生徒スカイプ名: #STUDENT_SKYPENAME#
講師スカイプ名: #TEACHER_SKYPENAME#

【GMO決済時】
オーダーID:　#ORDER_ID#
コースID:　#COURSE_ID#
コース名:　#COURSE_NAME#
コース料金:　#COURSE_PRICE#　円
消費税:　#COURSE_TAX#　円
お支払い金額:　#COURSE_TOTAL#　円
決済日時:　#ORDER_DATE#
有効期限:#EXPIRE_DATE#

【コンビニ決済】
受注番号:#POINT_SUBSCRIPTION_HISTORY_ID#
申込日時:#ORDER_DATE#
申込氏名:#STUDENT_NAME#
申込電話番号:#STUDENT_TELNUMBER#
商品お届け先:#STUDENT_ADDRESS#
コンビニ種類:#CVS_TYPE#
決済番号:#CVS_NUMBER#
確認番号:#CVS_CONFIRM_NUMBER#
入金日:#PAY_DATE#
支払期限日:#PAY_LIMIT_DATE#
受講開始日:#START_DATE#
受講有効期限:#EXPIRE_DATE#

【教師コメント登録メール】
先生ニックネーム:　#TEACHER_NICKNAME#
レッスン日:　#LESSON_DATE#
レッスン時間:　#LESSON_TIME#
レッスン名:　#LESSON_NAME#
テキスト名:　#LESSON_TEXT_NAME#
コメント内容:#TEACHER_COMMENT#

【人事担当者宛のメール送付】
送付先名:　#ADMIN_NAME#様
企業ID:　#PROJECT_CODE#
企業名:　#COMPANY_NAME#
送付先メール:　#ADMIN_EMAIL#
送付先パースワード:　#ADMIN_PASSWORD#

【セットコース購入完了メール】
オーダーID:　#ORDER_ID#
セットコースコード:　#SET_COURSE_ID#
セットコース名:　#SET_COURSE_NAME#
セットコース料金:　#SET_COURSE_PRICE#　円
消費税:　#SET_COURSE_TAX#　円
お支払い金額:　#SET_COURSE_TOTAL#　円
申込日時:　#ORDER_DATE#
コースリスト初め:　#COURSE_LIST_START#
コース名:　#COURSE_NAME#
コースID:　#COURSE_ID#

受注番号:　#POINT_SUBSCRIPTION_HISTORY_ID#
受講開始日:　#START_DATE#
有効期限日:　#EXPIRE_DATE#
コースリスト終わり:　#COURSE_LIST_END#

【一括登録　法人会員　継続メール】
先生徒名:　#STUDENT_NAME#
メールアドレス:　#STUDENT_EMAIL#
                                                </textarea>



                                            </div>
                                        </div>









                                        <div class="form-actions text-center">
                                            <div class="line"></div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                    <a :href="detailRemindMailUrl" class="btn btn-default w-100">閉じる</a>
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
                    mailSubject: {
                        required: "メール件名を入力してください",
                        max: "メール件名は255文字以内で入力してください。",
                    },
                    mailBody: {
                        required: "メール内容を入力してください",
                    },

                },
            };
            this.$validator.localize("en", messError);
            let that = this;
        },
        components: {
            Loader,
        },
        data() {
            return {
                timmingMinutes : this.remindMail.timing_minutes,
                id : this.remindMail.id,
                mailSubject : this.remindMail.mail_subject,
                mailBody : this.remindMail.mail_body,
                csrfToken: Laravel.csrfToken,
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},

            };
        },
        props: ['detailRemindMailUrl', 'updateRemindMailUrl', 'remindMail'],
        mounted() {
        },
        methods: {
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("timingMinutes", this.timmingMinutes);
                formData.append("mailSubject", this.mailSubject);
                formData.append("mailBody", this.mailBody);
                formData.append('_method', 'PUT');
                formData.append('id', this.id);

                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.updateRemindMailUrl , formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                this.$swal({
                                    title: "リマインドメール編集が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = that.detailRemindMailUrl;
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
                                            title: "失敗したデータを編集しました",
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
