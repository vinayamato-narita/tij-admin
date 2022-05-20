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
【無料会員登録時】
【法人会員登録時】
【パスワード変更時】
本登録用URL:　#CONFIRM_URL#
送信先学習者名:　#STUDENT_NAME#
LPお問い合わせぺージURL：#LP_URL#
学習者パスワード：#STUDENT_PASSWORD#
管理者パスワード再発行ページURL：#ADMIN_CHANGEPASS_URL#
学習者パスワード再発行ページURL：#STUDENT_CHANGEPASS_URL#
講師パスワード再発行ページURL：#TEACHER_CHANGEPASS_URL#

【レッスン予約時】
【レッスン予約後】
【レッスンキャンセル時】
【レッスン開始前】
講師名:　#TEACHER_NAME#
講師ニックネーム:　#TEACHER_NICKNAME#
学習者名:　#STUDENT_NAME#
学習者ニックネーム:　#STUDENT_NICKNAME#
レッスン開始日：#LESSON_DATE#
レッスン開始時間：#LESSON_TIME#
レッスン開始日（JST）：#LESSON_DATE_JST#
レッスン開始時間（JST）：#LESSON_TIME_JST#
レッスン名:　#LESSON_NAME#
テキスト名:　#LESSON_TEXT_NAME#
ZoomミーティングURL：#ZOOM_URL#
学習者マイページログインリンク：#STUDENT_MY_PAGE_URL#
学習者登録情報ページURL：#STUDENT_SETTING_URL#
Zoomの利用方法案内リンク：#ZOOM_MANUAL_URL#

【実力テスト提出時】
実力テスト評価期限日（JST）：#TEST_LIMIT_DATE#
実力テスト評価期限時（JST）：#TEST_LIMIT_TIME#
講師マイページログインリンク：#TEACHER_MY_PAGE_URL#

【GMO決済時】
オーダーID:　#ORDER_ID#
コースID:　#COURSE_ID#
コース名:　#COURSE_NAME#
コース料金:　#COURSE_PRICE#　円
消費税:　#COURSE_TAX#　円
支払方法：#COURSE_PAYMENT#
お支払い金額:　#COURSE_TOTAL#　円
決済日時:　#ORDER_DATE#
有効期限:#EXPIRE_DATE#
受講回数：#COURSE_COUNT#

【講師コメント登録メール】
講師ニックネーム:　#TEACHER_NICKNAME#
レッスン日:　#LESSON_DATE#
レッスン時間:　#LESSON_TIME#
レッスン名:　#LESSON_NAME#
テキスト名:　#LESSON_TEXT_NAME#
コメント内容:#TEACHER_COMMENT#

【お問い合わせ時】
お問い合わせID：#INQUIRY_ID#
お問い合わせ日時：#INQUIRY_DATE#
お問い合わせ件名：#INQUIRY_TITLE#
お問い合わせお名前：#USER_TITLE#
お問い合わせメールアドレス：#USER_EMAIL#
お問い合わせ内容：#INQUIRY_BODY#

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
