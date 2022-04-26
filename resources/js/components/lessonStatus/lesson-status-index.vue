<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="pull-left">
                        <h5>
                            レッスン状況
                        </h5>
                    </div>
                    <div class="pull-right mrb-5">
                        
                    </div>
                </div>
                <div class="clear"></div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="well" v-if="adminSystem">
                                        <div class="form-inline">
                                            <div class="input text">
                                                <label for="lesson_date_from">レッスン日&nbsp;</label>
                                                <date-picker :format="'YYYY/MM/DD'" type="date" readonly="readonly" name="lesson_date_from" id="lesson_date_from" v-model="startDate">
                                                </date-picker>
                                            </div>
                                            <div class="form-group">
                                                <div class="input text"><label for="lesson_date_to">&nbsp;～&nbsp;</label>
                                                    <date-picker :format="'YYYY/MM/DD'" type="date" readonly="readonly" name="lesson_date_to" id="lesson_date_to" v-model="endDate">
                                                    </date-picker>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-primary" formaction="lessonStatus/lessoninfomationstatusexportcsv" id="lesson_detail_status_btn" @click="lessonInfomationStatusExportCsv()">集計CSV出力</button>
                                                <button type="button" class="btn btn-sm btn-primary" id="lesson_detail_btn" @click="lessonInfomationDetailExportCsv()">明細CSV出力</button>
                                            </div>
                                        </div>
                                    </div>
                                    <form class="basic-form" @submit.prevent="save" ref="formSubmit">
                                        <input
                                            name="_token"
                                            type="hidden"
                                            v-model="lessonStatus._token"
                                        />
                                        <div id="table-container">
                                            <div class="row">
                                                <button class="col-sm-2 btn-link text-left" type="button" @click="handleSelectWeek(0)">先週</button>
                                                <div class="col-sm-8 text-center">
                                                    <button type="button" class="btn btn-sm btn-default" @click="selectThisWeek()">今週に戻る</button>
                                                    <button type="button" id="lesson_free_setting" class="btn btn-sm btn-primary" v-if="!editFlg" @click="changeEditFlg()">
                                                        自由枠数設定
                                                    </button>
                                                    <button  type="button" id="prev_week_copy" class="btn btn-sm btn-primary" v-if="editFlg" @click="copySettingLessonFree()">
                                                        前の週をコピー
                                                    </button>
                                                    <button id="lesson_free_do_setting" type="submit" class="btn btn-sm btn-primary" v-if="editFlg">
                                                        設定値の保存
                                                    </button>
                                                    <button  type="button" id="lesson_free_cancel_setting" class="btn btn-sm btn-default" v-if="editFlg" @click="changeEditFlg()">
                                                        キャンセル
                                                    </button>
                                                </div>
                                                <button class="col-sm-2 btn-link text-right" type="button" @click="handleSelectWeek(1)">次週</button>
                                            </div>
                                        </div>

                                        <div class="table-container" style="overflow-x: auto;">
                                            <table class="table table-bordered table-striped resize text-center" id="table-lesson-status" v-if="flgShowTable" >
                                                <thead class="wrapper">
                                                    <tr>
                                                        <td rowspan="3" style="vertical-align: middle; border-bottom:#000 solid 1px !important;min-width:75px"
                                                            class="end-date-td">時間</td>
                                                        <td  v-for="item in date" :key="item" colspan="6" class="end-date-td">
                                                            {{ item }}
                                                        </td>
                                                        <td class="caculate end-date-td" colspan="4">合計</td>
                                                        <td rowspan="3" style="vertical-align: middle; border-bottom:#000 solid 1px !important;min-width:75px; display:none;"
                                                            class="end-date-td date-ext" >時間</td>
                                                    </tr>
                                                    <tr>
                                                        <template v-for="column in 7">
                                                            <td v-bind:key="column.key" colspan="2">
                                                                固定枠
                                                            </td>
                                                            <td v-bind:key="column.key" colspan="4" class="end-date-td">
                                                                自由枠
                                                            </td>
                                                        </template>
                                                        <td colspan="2" class="caculate">
                                                            固定枠
                                                        </td>
                                                        <td colspan="2" class="caculate end-date-td">
                                                            自由枠
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <template v-for="column in 7">
                                                        <td v-bind:key="column.key">予約数</td>
                                                        <td v-bind:key="column.key">登録数</td>
                                                        <td v-bind:key="column.key">予約数</td>
                                                        <td v-bind:key="column.key">登録数</td>
                                                        <td v-bind:key="column.key">残枠</td>
                                                        <td v-bind:key="column.key" class="end-date-td number-setting">枠数</td>
                                                        </template>
                                                        <td class="caculate">予約数</td>
                                                        <td class="caculate">登録数</td>
                                                        <td class="caculate">予約数</td>
                                                        <td class="caculate end-date-td">登録数</td>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr v-for="item in numRow" :key="item" row-index="item">
                                                        <td style="width:50px;" class="end-date-td time-td">
                                                            <p v-if="dataTime[item-1] !== undefined">{{ dataTime[item-1]['time'] }}</p>
                                                        </td>
                                                        <template v-for="column in 7">
                                                        <td v-bind:key="column.key" class="lesson-status">
                                                            {{ dataTime[item-1][column - 1]['0']['reverse_count_normal'] }}
                                                        </td>
                                                        <td v-bind:key="column.key" class="lesson-status">
                                                            {{ dataTime[item-1][column - 1]['0']['lesson_count_normal'] }}
                                                        </td>
                                                        <td v-bind:key="column.key" class="lesson-status">
                                                            {{ dataTime[item-1][column - 1]['0']['reverse_count_free'] }}
                                                        </td>
                                                        <td v-bind:key="column.key" class="lesson-status free-lesson-registerd">
                                                            {{ dataTime[item-1][column - 1]['0']['lesson_count_free'] }}
                                                        </td>
                                                        <td v-bind:key="column.key" :class="['free-lesson-remain', dataTime[item-1][column - 1]['free_schedule'] >= 0 ? '' : 'td-error']">
                                                            {{ dataTime[item-1][column - 1]['free_schedule'] }}
                                                        </td>

                                                        <td v-bind:key="column.key" class="max-free-lesson end-date-td">
                                                            <span class="max-free-lesson-text" v-if="!editFlg">
                                                                {{ dataTime[item-1][column - 1]['max_free_lesson'] }}
                                                            </span>
                                                            <input style="width: 40px;" type="number" class="max-free-lesson-input spinner" min="0" v-model="dataMaxFreeLesson[item-1][column - 1]['max_free_lesson']" v-if="editFlg" />
                                                        </td>
                                                        </template>
                                                        <td class="caculate">
                                                            {{ dataTime[item-1]['reverse_count_normal_1'] }}
                                                        </td>
                                                        <td class="caculate">
                                                            {{ dataTime[item-1]['lesson_count_normal_2'] }}
                                                        </td>
                                                        <td class="caculate">
                                                            {{ dataTime[item-1]['reverse_count_free_3'] }}
                                                        </td>
                                                        <td class="caculate end-date-td">
                                                            {{ dataTime[item-1]['lesson_count_free_4'] }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="caculate">
                                                        <td class="end-date-td">合計</td>
                                                        <template v-for="column in 7">
                                                            <td v-bind:key="column.key">
                                                                {{ dataTime[column-1]['reverse_count_normal_date_1'] }}
                                                            </td>
                                                            <td v-bind:key="column.key">
                                                                {{ dataTime[column-1]['lesson_count_normal_date_2'] }}
                                                            </td>
                                                            <td v-bind:key="column.key">
                                                                {{ dataTime[column-1]['reverse_count_free_date_3'] }}
                                                            </td>
                                                            <td v-bind:key="column.key">
                                                                {{ dataTime[column-1]['lesson_count_free_date_4'] }}
                                                            </td>
                                                            <td v-bind:key="column.key">-</td>
                                                            <td v-bind:key="column.key" class="end-date-td">-</td>
                                                        </template>
                                                        <td>
                                                            {{ dataTime['reverse_count_normal'] }}
                                                        </td>
                                                        <td>
                                                            {{ dataTime['lesson_count_normal'] }}
                                                        </td>
                                                        <td>
                                                            {{ dataTime['reverse_count_free'] }}
                                                        </td>
                                                        <td class="end-date-td">
                                                            {{ dataTime['lesson_count_free'] }}
                                                        </td>
                                                        <td style="width:50px; display:none;" class="end-date-td date-ext">合計</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <table id="header-fixed" class="table table-bordered table-striped resize text-center"></table>
                                        </div>
                                        <div class="row">
                                            <button class="col-sm-2 btn-link text-left" type="button" @click="handleSelectWeek(0)">先週</button>
                                            <div class="col-sm-8 text-center">
                                                <button type="button" class="btn btn-sm btn-default" @click="selectThisWeek()">今週に戻る</button>
                                            </div>
                                            <button class="col-sm-2 btn-link text-right" type="button" @click="handleSelectWeek(1)">次週</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <loader :flag-show="flagShowLoader"></loader>
        </main>
    </div>
</template>

<script type="text/javascript">
import axios from "axios";
import Loader from "../common/loader.vue";

export default {
    created: function() {
        
    },
    components: {
        Loader,
    },
    data() {
        return {
            flagShowLoader: false,
            dateDefine : [
                { 0 : "日" },
                { 1 : "月" },
                { 2 : "火" },
                { 3 : "水" },
                { 4 : "木" },
                { 5 : "金" },
                { 6 : "土" }
            ],
            date : [],
            startDate : new Date(),
            endDate : new Date(),
            dataTime : [],
            editFlg : false,
            lessonStatus: {
                _token: Laravel.csrfToken,
            },
            dataMaxFreeLesson : [],
            flgShowTable : false
        };
    },
    props: ["urlGetData", "numRow", 'urlLessonInfomationDetailExportCsv', 'urlLessonInfomationStatusExportCsv', 'urlAction', 'urlCopySettingLessonFree', 'adminSystem'],
    mounted() {
        this.getData()
    },
    methods: {
        save() {
            let that = this;
            this.$validator
                .validateAll()
                .then(valid => {
                    if (valid) {
                        that.submit();
                    }
                })
                .catch(function(e) {});
        },
        changeEditFlg() {
            this.editFlg = !this.editFlg
        },
        handleSelectWeek(type) {
            this.editFlg = false;
            if (type == 0) {
                this.startDate = new Date(this.startDate.getFullYear(), this.startDate.getMonth(), this.startDate.getDate() - 7)
            } else {
                this.startDate = new Date(this.startDate.getFullYear(), this.startDate.getMonth(), this.startDate.getDate() + 7)
            }

            this.getData();
        },
        getData() {
            this.flagShowLoader = true;
            let that = this;
            axios
                .post(that.urlGetData, {
                    start_date : this.startDate
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        that.flgShowTable = true
                        that.date = response.data.date
                        that.dataTime = response.data.dataTime
                        this.startDate = new Date(response.data.startDate)
                        this.endDate = new Date(response.data.endDate)
                        that.dataMaxFreeLesson = response.data.dataMaxFreeLesson
                    } else {
                        this.$swal({
                            text: "レッスン状況を登録できません。",
                            icon: "warning",
                            confirmButtonText: "OK"
                        }).then(result => {
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                }); 
        },
        submit(e) {
            let that = this;
            this.$swal({
                text: "自由可能枠数の設定を一括設定しますか？",
                icon: false,
                confirmButtonText: "設定する",
                cancelButtonText: "キャンセル",
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    that.flagShowLoader = true;
                    axios
                        .post(that.urlAction, {
                            data_max_free_lesson : that.dataMaxFreeLesson,
                            start_date : that.startDate
                        })
                        .then(response => {
                            that.flagShowLoader = false;
                            if (response.data.status == "OK") {
                                that.$swal({
                                    text: "自由可能枠数の設定が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(result => {
                                    this.editFlg = false
                                    this.getData()
                                });
                            }
                        })
                        .catch(e => {
                            that.flagShowLoader = false;
                        });
                }
            });
        },
        lessonInfomationDetailExportCsv() {
            this.flagShowLoader = true;
            let that = this;

            axios
            .post(that.urlLessonInfomationDetailExportCsv, {
                lesson_date_from: this.startDate,
                lesson_date_to: this.endDate,
                _token: Laravel.csrfToken,
            })
            .then((res) => {
                this.flagShowLoader = false;

                if (res.data.status == '200') {
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
        lessonInfomationStatusExportCsv() {
            this.flagShowLoader = true;
            let that = this;

            axios
            .post(that.urlLessonInfomationStatusExportCsv, {
                lesson_date_from: this.startDate,
                lesson_date_to: this.endDate,
                _token: Laravel.csrfToken,
            })
            .then((res) => {
                this.flagShowLoader = false;

                if (res.data.status == '200') {
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
        copySettingLessonFree() {
            let that = this;

            this.$swal({
                text: "前の週の自由可能枠数の設定をコピーしますか？",
                icon: false,
                confirmButtonText: "コピーする",
                cancelButtonText: "キャンセル",
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    that.flagShowLoader = true;
                    axios
                        .post(that.urlCopySettingLessonFree, {
                            start_date: this.startDate,
                            _token: Laravel.csrfToken,
                        })
                        .then((response) => {
                            this.flagShowLoader = false;
                            if (response.data.status == "OK") {
                                that.$swal({
                                    text: "前の週の自由可能枠数の設定をコピーしました。",
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(result => {
                                    this.editFlg = false
                                    this.getData()
                                });
                            }
                        })
                        .catch((error) => {
                            this.flagShowLoader = false;
                        });
                }
            });
        },
        selectThisWeek() {
            this.startDate = new Date()
            this.getData()
        }
    }
};
</script>
