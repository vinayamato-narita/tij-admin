<template>
    <div class="c-body csv-import">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card" style="margin: 10px; min-height: 600px;">
                                <div class="card-header" style="display: block;">
                                    <h5 class="title-page text-center">SEND MAIL</h5>
                                </div>
                                <div v-if="errorSendMailDataFlagEx == 1">
                                    <form class="basic-form" @submit.prevent="submit">
                                        <table class='table table-bordered table-sendmail' style="margin: 10px!important">
                                            <tr>
                                                <th class="text-center th-header" width="50">
                                                    <input type="checkbox" name="checkAll" class='checkAll' checked v-on:click="checkAll" v-model='isCheckAll' />
                                                </th>
                                                <th class="th-header">
                                                    氏名姓
                                                </th>
                                                <th class="th-header">
                                                    氏名名
                                                </th>
                                                <th class="th-header">
                                                    メールアドレス
                                                </th>
                                                <th class="th-header">
                                                    企業ID
                                                </th>
                                                <th class="th-header">
                                                    生徒番号
                                                </th>
                                            </tr>
                                            <tr v-for="(row, keyRow) in errorSendMailData" v-if="!isNaN(keyRow) && keyRow < 31">
                                                <td class="text-center">
                                                    <input type="checkbox" class='item-checkbox' checked v-model='keyRows' @change="checkItem" v-bind:value='keyRow' />
                                                </td>
                                                <td>
                                                    {{ row[studentFirstName] }}
                                                </td>
                                                <td>
                                                    {{ row[studentLastName] }}
                                                </td>
                                                <td>
                                                    {{ row[studentEmail] }}
                                                </td>
                                                <td>
                                                   {{ row[projectCode] }}
                                                </td>
                                                <td>
                                                    {{ row[studentId] }}
                                                </td>
                                            </tr>
                                        </table>
                                        <button type="submit" class="btn btn-default" style="margin: 20px auto; display: block;" :disabled="keyRows.length == 0">送信する</button>
                                    </form>
                                </div>
                                
                                <div class="text-center mt-20" v-if="sendMailEx == 1">
                                    <h3>メール送信が完了しました。</h3>
                                    <button class="btn btn-default" onclick="window.close()" style="width: 80px" >OK</button>
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
       
    },
    components: {
        Loader,
    },
    data() {
        return {
            flagShowLoader: false,
            sendMailEx: this.sendMail,
            isCheckAll: true,
            errorSendMailDataFlagEx: this.errorSendMailDataFlag,
            keyRows: this.getKeyRows()
        };
    },
    props: ["urlAction", "sendMail", "errorSendMailData", "errorSendMailDataFlag", "studentFirstName", "studentLastName", "studentEmail", "projectCode", "studentId"],
    mounted() {
        
    },
    methods: {
        submit(e) {
            let formData = new FormData();
            formData.append('_token', Laravel.csrfToken);
            formData.append('keyRows', this.keyRows);
            let that = this;
            that.flagShowLoader = true;
            axios
                .post(that.urlAction, formData)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        that.sendMailEx = 1;
                        that.errorSendMailDataFlagEx = 0;
                    }
                    
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
        checkAll(event) {
            this.isCheckAll = !this.isCheckAll;
            this.keyRows = [];
            if(this.isCheckAll){    // Check all
                for (var key in this.errorSendMailData) {
                    this.keyRows.push(key);
                }
            }
        },
        checkItem(event) {
            if(this.keyRows.length == Object.keys(this.errorSendMailData).length){
                this.isCheckAll = true;
            }else{
                this.isCheckAll = false;
            }
        },
        getKeyRows() {
            let arr = [];
            for (var key in this.errorSendMailData) {
                arr.push(key);
            }

            return arr;
        }
    }
};
</script>
