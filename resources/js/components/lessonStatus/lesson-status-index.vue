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
                                    <div class="well">
                                        <div class="form-inline">
                                            <div class="input text">
                                                <label for="lesson_date_from">レッスン日&nbsp;</label>
                                                <date-picker :format="'YYYY/MM/DD'" type="date" readonly="readonly" name="lesson_date_from" id="lesson_date_from">
                                                </date-picker>
                                            </div>
                                            <div class="form-group">
                                                <div class="input text"><label for="lesson_date_to">&nbsp;～&nbsp;</label>
                                                    <date-picker :format="'YYYY/MM/DD'" type="date" readonly="readonly" name="lesson_date_to" id="lesson_date_to">
                                                    </date-picker>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary" formaction="lessonStatus/lessoninfomationstatusexportcsv" id="lesson_detail_status_btn">集計CSV出力</button>
                                                <button type="submit" class="btn btn-sm btn-primary" id="lesson_detail_btn">明細CSV出力</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="table-container">
                                        <div class="row">
                                            <a href="" class="col-sm-2 text-left">先週</a>
                                            <div class="col-sm-8 text-center">
                                                <a href="" class="col-sm-2 text-left">今週に戻る</a>
                                                <button id="lesson_free_setting" class="btn btn-sm btn-primary">
                                                    自由枠数設定
                                                </button>
                                                <button id="prev_week_copy" class="btn btn-sm btn-primary">
                                                    前の週をコピー
                                                </button>
                                                <button id="lesson_free_do_setting" class="btn btn-sm btn-primary">
                                                    設定値の保存
                                                </button>
                                                <button id="lesson_free_cancel_setting" class="btn btn-sm btn-default">
                                                    キャンセル
                                                </button>
                                            </div>
                                            <a href="" class="col-sm-2 text-right">先週</a>
                                        </div>
                                    </div>

                                    <div class="table-container">
                                        <table class="table table-bordered table-striped resize text-center" id="table-lesson-status">
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
                                                <tr v-for="item in numRow -1" :key="item" row-index="item">
                                                    <td style="width:50px;" class="end-date-td time-td">
                                                        {{ dataTime[item] }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table id="header-fixed" class="table table-bordered table-striped resize text-center"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script type="text/javascript">
import axios from "axios";
import Loader from "../common/loader.vue";

export default {
    created: function() {
        let messError = {
            custom: {
                required: "お問い合わせ件名を入力してください",
                max: "お問い合わせ件名は255文字以内で入力してください",
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
        };
    },
    props: ["urlGetData", "numRow", "dataTime"],
    mounted() {
        this.getData()
    },
    methods: {
        getData() {
            let that = this;
            axios
                .post(that.urlGetData, {
                    start_date : this.startDate
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        that.date = response.data.date
                    } else {
                        this.$swal({
                            text: "問い合わせ件の作成が失敗しました。再度お願いいたします",
                            icon: "warning",
                            confirmButtonText: "OK"
                        }).then(result => {
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        }
    }
};
</script>
