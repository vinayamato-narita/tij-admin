<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>一括登録（個人・単体コース）
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="basic-form">

                                    <div class="card-header">
                                        <h5 class="title-page">IMPORT FORM(個人・単体コース）
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-md-1">

                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <form method="post"  enctype="multipart/form-data" :action="this.postUrl">
                                                    <input
                                                            name="_token"
                                                            type="hidden"
                                                            v-model="csvExport._token"
                                                    />
                                                    <div class="row">
                                                        <div class="col-md-2">

                                                        </div>

                                                        <div class="col-md-5">
                                                            <input
                                                                    class="form-control"
                                                                    type="text"
                                                                    v-model="fileCsvPath"
                                                                    placeholder="ファイルを選択してください"
                                                            />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <button type="button"  class="btn btn-default" v-on:click="selectFile">
                                                                参照
                                                                <input type="file" name="uploaded" id="fileUpload" hidden>

                                                            </button>
                                                            <button class="btn btn-default" v-on:click="showLoader" name="upfile">

                                                                読込

                                                            </button>

                                                        </div>
                                                        <div class="col-md-2">
                                                        </div>
                                                        <div class="col-md-5 text-left error text text-nowrap">
                                                            {{this.errMsg}}

                                                        </div>

                                                    </div>

                                                </form>

                                            </div>
                                            <div class="col-md-5">

                                            </div>

                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-1">

                                            </div>
                                            <div class="col-md-10">
                                                <div>
                                                    エラーの色の説明:
                                                </div>
                                                <table>
                                                    <tbody><tr>
                                                        <td class="error-td-class">3.1 空白チェック</td>
                                                        <td class="error error-empty width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.2 文字数チェック</td>
                                                        <td class="error error-length width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.3 禁則文字チェック</td>
                                                        <td class="error error-special-text width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.4 性別チェック</td>
                                                        <td class="error error-male width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.5 日付チェック</td>
                                                        <td class="error error-date width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.6 生徒番号チェック</td>
                                                        <td class="error error-student-id width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.7 コースコードチェック</td>
                                                        <td class="error error-course-id width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.8 生徒番号整合チェック</td>
                                                        <td class="error error-email width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.9 パスワードチェック</td>
                                                        <td class="error error-pass width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.10 メールアドレス存在チェック</td>
                                                        <td class="error error-email-noneId width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.11 メールアドレス形式チェック</td>
                                                        <td class="error error-email-name width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.12 Skype名形式チェック</td>
                                                        <td class="error error-skype-name width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.13 データの個数エラー</td>
                                                        <td class="error error-common width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.14 数字と「-」だけチェック</td>
                                                        <td class="error error-hankaku width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.15 開講希望月チェック</td>
                                                        <td class="error error-course-begin-month width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.16 請求区分チェック</td>
                                                        <td class="error error-corporation width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.17 都道府県チェック</td>
                                                        <td class="error error-prefecture width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.18 企業IDチェック</td>
                                                        <td class="error error-project width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.19 案件コースチェック</td>
                                                        <td class="error error-project-course width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.20 全角カタカナのみチェック</td>
                                                        <td class="error error-kana width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.21 半角カタカナチェック</td>
                                                        <td class="error error-hankaku-kana width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.22 スペースチェック</td>
                                                        <td class="error error-space width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.23 購入不可エラー</td>
                                                        <td class="error error-cannot-buy width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.24 半角英数記号チェック</td>
                                                        <td class="error error-alphanumeric-symbol width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.25 継続メール送信対象</td>
                                                        <td class="error error-email-noneId-sendMail width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.26 デフォルト言語</td>
                                                        <td class="error error-lang-type width-100"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="error-td-class">3.27 タイムゾーン</td>
                                                        <td class="error error-time-zone width-100"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>


                                            </div>
                                            <div class="col-md-1">

                                            </div>

                                        </div>
                                        <div v-if="this.isShowTable">
                                            <table  class="table table-bordered resize JColResizer" style="margin: 10px!important">
                                                <tbody>
                                                <tr>
                                                    <th class="th-header">氏名姓 student_first_name</th>
                                                    <th class="th-header">氏名名 student_last_name</th>
                                                    <th class="th-header">ニックネーム student_nickname</th>
                                                    <th class="th-header">パスワード student_password</th>
                                                    <th class="th-header">メールアドレス student_email</th>
                                                    <th class="th-header">Skype名 student_skypename</th>
                                                    <th class="th-header">デフォルト言語</th>
                                                    <th class="th-header">タイムゾーン timezone_id</th>
                                                    <th class="th-header">コースコード course_id</th>
                                                    <th class="th-header">得意先コード</th>
                                                    <th class="th-header">法人コード</th>
                                                    <th class="th-header">法人名 company_name</th>
                                                    <th class="th-header">受注日</th>
                                                    <th class="th-header">基準日</th>
                                                    <th class="th-header">有効期限 expire_date</th>
                                                    <th class="th-header">共通管理番号</th>
                                                    <th class="th-header">生徒番号 student_id</th>
                                                    <th class="th-header">メール送信</th>
                                                </tr>
                                                <tr >
                                                    <th class="th-header" v-for="hd in this.csv[0]">
                                                        {{hd}}
                                                    </th>

                                                    <th class="th-header"></th>
                                                </tr>
                                                <tr v-for="(data, index) in this.csv" >


                                                    <td v-for="(itemData, indexTd) in data" v-if="index !== 0 && indexTd !== 'error_list'" :class="(data['error_list'] == null || data['error_list'][indexTd] == null) ? '' : data['error_list'][indexTd].class"
                                                    >
                                                        {{itemData}}
                                                    </td>



                                                    <td class="result-status"></td>
                                                </tr>
                                                <tr>
                                                    <th class="error error-common" v-for="hd in this.csv[0]">
                                                    </th>
                                                </tr>



                                                </tbody>
                                            </table>
                                            <div>
                                                <form class="form-inline" role="form" enctype="multipart/form-data" method="post">
                                                    <input
                                                            name="_token"
                                                            type="hidden"
                                                            v-model="csvExport._token"
                                                    />
                                                    <div style="text-align: center;width: 100%">
                                                        <button type="submit" class="btn btn-sm btn-default" name="dataCommit" id="csvButton" style="text-align: center;" v-if="!this.errFlag">登録</button>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </main>
        <loader :flag-show="flagShowLoader"></loader>
    </div>
</template>

<style scoped>

</style>

<script type="text/javascript">
    import axios from "axios";
    import Loader from "../common/loader.vue";

    export default {
        created: function () {
            console.log(this.csv)
        },
        components: {
            Loader,
        },
        data() {
            return {
                fileCsvPath : '',
                flagShowLoader: false,
                csvExport: {
                    _token: Laravel.csrfToken,
                },
            };
        },
        props: [ 'postUrl', 'errMsg', 'errFlag', 'csv', 'isShowTable'],
        mounted() {
            let that = this;
            $('input[type=file]').change(function () {
                that.fileCsvPath = this.files[0].name;
            });
        },
        methods: {
            showLoader() {
                this.flagShowLoader = true;
            },
            selectFile(){
                $('#fileUpload').click();
            },
/*
            exportLessonHistory() {
                this.flagShowLoader = true;
                let that = this;

                axios
                    .post(that.urlExportLessonHistory, {
                        lesson_result_date1: this.exportLessonHistoryStart,
                        lesson_result_date2: this.exportLessonHistoryEnd,
                        lesson_result_number: this.lessonResultNumber,
                        lesson_result_campaign: this.lessonResultCampaign,
                        lesson_result_product: this.lessonResultProduct,
                        lesson_result_customer: this.lessonResultCustomer,
                        _token: Laravel.csrfToken,
                    })
                    .then((res) => {
                        this.flagShowLoader = false;
                        console.log(res.data)
                        if (res.data.status == 200) {
                            var fileLink = document.createElement("a");
                            fileLink.href = res.data.path;
                            fileLink.setAttribute("download", res.data.file_name);
                            document.body.appendChild(fileLink);

                            window.setTimeout(function () {
                                fileLink.click();
                            }, 1000);
                        } else {
                            this.showAlert(res.data.message)
                        }
                    })
                    .catch((error) => {
                        this.flagShowLoader = true;
                    });
            },
*/
            showAlert(message) {
                let that = this;
                this.$swal({
                    title: message,
                    icon: "warning",
                    showCancelButton: false,
                }).then(result => {});
            }
        },
    };
</script>
