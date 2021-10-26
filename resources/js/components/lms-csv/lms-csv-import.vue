<template>
    <div class="c-body csv-import">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            一括登録（法人・単体コース）
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card" style="margin: 10px; min-height: 600px;">
                                <div class="card-header">
                                    <h5 class="title-page">IMPORT FORM（法人・単体コース）</h5>
                                </div>
                                <form class="basic-form" enctype="multipart/form-data" @submit.prevent="save">
                                    <div class="card-body inputForm">
                                        <div class="form-group form-group-csv">
                                            <input type="text" v-model="file_name" class="form-control input-csv" readonly="readyonly" placeholder="ファイルを選択してください" width="48" @click="trigger" />
                                            <span class="btn btn-default btn-file">参照
                                                <input type="file" name="upload_csv" id="fileUpload" class="upload-csv" ref="fileInput" @change="handleFileUpload($event)" accept=".csv" 
                                                v-validate="'required|ext:csv'" />
                                            </span>
                                        </div>
                                        <button type="submit" class="btn btn-default" style="margin-top: 5px" id="submitButton">読込</button>
                                    </div>
                                    <div
                                        class="input-group is-danger"
                                        role="alert"
                                        v-if="errors.has('upload_csv')"
                                        style="margin-left: 115px;"
                                    >
                                        {{ errors.first("upload_csv") }}
                                    </div>
                                </form>
                                
                                <div class="error-des">
                                    <div>エラーの色の説明:</div>
                                    <table>
                                        <tr>
                                            <td class="error-td-class">3.1 空白チェック</td>
                                            <td class="error error-empty w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.2 文字数チェック</td>
                                            <td class="error error-length w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.3 禁則文字チェック</td>
                                            <td class="error error-special-text w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.4 性別チェック</td>
                                            <td class="error error-male w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.5 日付チェック</td>
                                            <td class="error error-date w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.6 生徒番号チェック</td>
                                            <td class="error error-student-id w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.7 コースコードチェック</td>
                                            <td class="error error-course-id w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.8 生徒番号整合チェック</td>
                                            <td class="error error-email w-100"></td>
                                        </tr>
                                         <tr>
                                            <td class="error-td-class">3.9 パスワードチェック</td>
                                            <td class="error error-pass w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.10 メールアドレス存在チェック</td>
                                            <td class="error error-email-noneId w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.11 メールアドレス形式チェック</td>
                                            <td class="error error-email-name w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.12 Skype名形式チェック</td>
                                            <td class="error error-skype-name w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.13 データの個数エラー</td>
                                            <td class="error error-common w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.14 数字と「-」だけチェック</td>
                                            <td class="error error-hankaku w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.15 開講希望月チェック</td>
                                            <td class="error error-course-begin-month w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.16 請求区分チェック</td>
                                            <td class="error error-corporation w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.17 都道府県チェック</td>
                                            <td class="error error-prefecture w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.18 企業IDチェック</td>
                                            <td class="error error-project w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.19 案件コースチェック</td>
                                            <td class="error error-project-course w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.20 全角カタカナのみチェック</td>
                                            <td class="error error-kana w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.21 半角カタカナチェック</td>
                                            <td class="error error-hankaku-kana w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.22 スペースチェック</td>
                                            <td class="error error-space w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.23 購入不可エラー</td>
                                            <td class="error error-cannot-buy w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.24 半角英数記号チェック</td>
                                            <td class="error error-alphanumeric-symbol w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.25 継続メール送信対象</td>
                                            <td class="error error-email-noneId-sendMail w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.26 デフォルト言語</td>
                                            <td class="error error-lang-type w-100"></td>
                                        </tr>
                                        <tr>
                                            <td class="error-td-class">3.27 タイムゾーン</td>
                                            <td class="error error-time-zone w-100"></td>
                                        </tr>
                                    </table>
                                </div>

                                <div v-if="!errors.has('upload_csv') && csv.length > 0">
                                    <div style="overflow: scroll;">
                                        <table class='table table-bordered resize' style="margin: 10px!important;" v-if="csv.length">
                                            <tr>
                                                <th v-for="header in csv[0]" class="th-header">{{ header }}</th>
                                            </tr>
                                            <tr v-for="(row, keyRow) in csv" v-if="keyRow != 0">
                                                <th v-for="(value, key) in csv[0]" class="error error-common" v-if="getConditionHeader(row)"> 
                                                    {{ row[key] }}
                                                </th>
                                                <td v-for="(value, key) in row" :class="getTdClass(row, key)" v-if="!isNaN(key) && getConditionTd(row)">
                                                    {{ getContentTd(value, row, key) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="csvError error-common"></div>

                                    <div style="margin-top: 20px; margin-bottom: 20px;">
                                       <form class="form-inline" role="form" style="text-align: center; display: block;" @submit.prevent="importCsv"> 
                                            <button type="submit" class="btn btn-default" v-if="notErrorData > 1 && !errorFlag">登録</button>
                                            <a :href="urlImportSendMail" target="_blank" class="btn btn-default" v-if="errorSendMailDataFlag">登録済みユーザに継続メールを送る</a>
                                       </form>      
                                    </div>
                                </div>

                                <div v-if="finaldata.length > 0">
                                    <div style="overflow: scroll;">
                                        <table class='table table-bordered resize' style="margin: 10px!important">
                                            <tr>
                                                <th v-for="header in finaldata[0]" class="th-header">{{ header }}</th>
                                            </tr>
                                            <tr v-for="(row, keyRow) in finaldata" v-if="keyRow != 0">
                                                <td v-for="(value, key) in row">
                                                    {{ value }}
                                                </td>
                                            </tr>
                                        </table>
                                        
                                    </div>
                                    <div style="margin-top: 20px; margin-bottom: 20px; text-align: center;">
                                       <a :href="urlImportSendMail" target="_blank" class="btn btn-default" v-if="errorSendMailDataFlag">登録済みユーザに継続メールを送る</a> 
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

<script type="text/javascript">
import axios from "axios";
import Loader from "./../common/loader.vue";

export default {
    created: function() {
        let messError = {
            custom: {
                upload_csv: {
                    required: "ファイルを指定してください。",
                    ext: "ファイル形式が正しくありません。"
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
            upload_csv: '',
            csv: [],
            errorFlag: false,
            errorSendMailDataFlag: false,
            notErrorData: 0,
            finaldata: [],
            file_name: ""
        };
    },
    props: ["urlAction", "urlImportCsv", "studentId", "urlImportSendMail"],
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

            let formData = new FormData();
            formData.append('upload_csv', this.upload_csv);
            formData.append('_token', Laravel.csrfToken);
                    
            const inputFile = this.$refs.fileInput
            inputFile.type = 'text'
            inputFile.type = 'file'
            this.file_name = "";
            
            let that = this;
            axios
                .post(that.urlAction, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        let dataRes = response.data;
                        that.csv = dataRes.csv;
                        that.errorFlag = dataRes.errorFlag;
                        that.errorSendMailDataFlag = dataRes.errorSendMailDataFlag;
                        that.notErrorData = dataRes.notErrorData;
                        that.finaldata = [];
                        this.errors.add({
                            field: 'upload_csv',
                            msg: dataRes.message
                        }); 
                    }
                    
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
        trigger () {
            this.$refs.fileInput.click()
        },
        handleFileUpload(event ) {
            let files = event.target.files || event.dataTransfer.files;
            if (!files.length)
                return;
            this.upload_csv = event.target.files[0];
            this.file_name = event.target.files[0].name;
        },
        getTdClass(row, keyRow) {
            let that = this;
            let tdClass = "";
            if (row['error_list'] != undefined && row['error_list']['class'] != undefined && row['error_list']['class'] == 'error-common')
            {
                tdClass = 'error error-common';
            }else if(row['error_list'] != undefined && row['error_list'][keyRow] != undefined && row['error_list'][keyRow] != "") {
                tdClass = 'error '+ row['error_list'][keyRow]['class'];
                if (row['error_list'][keyRow]['class'] == undefined || row['error_list'][keyRow]['class'] != 'error-email-noneId-sendMail') 
                {
                    that.errorFlag = true;
                }
            }

            return tdClass;
        },
        getContentTd(value, row, key) {
            let result = "";
            if (key == this.studentId && row['error_list'] != undefined && row['error_list']['student_id'] != undefined && row['error_list']['student_id'] != "") {
                result = row['error_list']['student_id'];
            }else {
                result = value;
            }
            return result;
        },
        importCsv() {
            let that = this;
            let formData = new FormData();
            formData.append('_token', Laravel.csrfToken);
            axios
                .post(that.urlImportCsv, formData)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        let dataRes = response.data;
                        that.finaldata = dataRes.finaldata;
                        that.errorSendMailDataFlag = dataRes.errorSendMailDataFlag;
                        that.csv = [];
                    }
                    
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
        getConditionHeader(row) {
            return row['error_list'] != undefined && row['error_list']['class'] != undefined && row['error_list']['class'] == 'error-common';
        },
        getConditionTd(row) {
            return row['error_list'] == undefined || row['error_list']['class'] == undefined || row['error_list']['class'] != 'error-common';
        }
    }
};
</script>
