<template>
<div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            レッスン新規作成

                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="row row-content">
                                    <div class="col-md-2" style="background-color: #f6f6f6;padding: 0px; width:10.666667%">
                                            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 search-teacher-m">
                                                <input type="text" v-model="searchInput" class="form-control" placeholder="講師名・講師ニックネーム" id="teacher_name" />
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 search-teacher-m" style="float: right; margin-top : 5px; margin-bottom : 5px">
                                                <button type="submit" class="btn btn-sm btn-primary pull-right" @click="getData()">
                                                    検索
                                                </button>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 teacher-menu-r" style="overflow-y:auto;max-height: 640px;">
                                                <div class="teacher-top"></div>
                                                <p style="overflow: hidden; text-overflow: ellipsis" v-for="item, key in teacherList" :key="key" :class="['teacher-list', (item.teacher_id == teacherId) ? 'currentTeacher' : '']" @click="changeTeacherId(item.teacher_id)">
                                                    {{ item.teacher_name }} ( {{ item.teacher_nickname }} )
                                                </p>
                                            </div>
                                    </div>

                                    <div class="col-md-10 schedule-content" style="width:89.333333%" >
                                        <div class="indexz-show" v-if="teacherId == null"></div>
                                        <h2 class="title-table" style="margin-top: 0px;">LESSON SCHEDULE</h2>
                                        <div class="clearfix wrap-border">
                                            <div class="col-main-table">
                                                <ul class="nav-table schedule-nav-tbl">
                                                    <li class="nav-table-item nav-table-item-lesson-schedule">
                                                        <span @click="changeDate(preWeek)">Last Week</span>
                                                    </li>
                                                    <li class="nav-table-item nav-table-item-lesson-schedule">
                                                        <span @click="changeDate(new Date())">Refresh</span>
                                                    </li>
                                                    <li class="nav-table-item nav-table-item-lesson-schedule">
                                                        <span @click="changeDate(nextWeek)">Next Week</span>
                                                    </li>
                                                </ul>
                                                <table class="head-table table-schedule table-basic head-table-cell-select top-table">
                                                    <tbody>
                                                        <tr>
                                                            <td >Time</td>
                                                            <td v-for="item, key in header" :key="key" :class="['ui-selectee', key == 5 ? 'sat' : '', key == 6 ? 'sun' : '']" >
                                                                {{ item }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="scrollbar-inner scroll-table-schedule" style="position: relative">
                                                    <div class="scrollbar-inner scroll-content scroll-scrolly_visible" style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 600px;">
                                                        <div class="scroll-wrapper-schedule">
                                                            <table class="body-table table-bordered table-basic hiddeTextTable table-schedule table-cell-select bottom-table" v-if="flgShowTable">
                                                                <tbody>
                                                                    <tr v-for="column, key in numRow" :key="key" class="item-row" row-index="column" id="id_cell-column">
                                                                        <td scope="lessonCell" style = "border-left : none !important">
                                                                            {{ dataLessonSchedule[key].time }}
                                                                        </td>

                                                                        <td v-for="item, index in 7" :key="index" :class="['lessonCell', (dataLessonSchedule[key][index][0]['lesson_type_id'] == 1  && dataLessonSchedule[key][index][0]['lesson_id'] > 0) ? 'colorRegister' : '', dataSelected[key][index] ? 'ui-selectee ui-selected' : '']" @click="changeSelected(key, index)">
                                                                            {{ dataLessonSchedule[key][index][0]['lesson_name'] }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col-main-table -->
                                            <div class="col-side-table">
                                                <h3 class="title-side-table">Detail</h3>
                                                <div class="subtitle-side-table"></div>
                                                <div class="wrapper-btn-bulk">
                                                    <input id="bulk-resistration" @click="bulkResistration()" class="btn active" type="button" value="一括登録">
                                                    <input id="bulk-remove" @click="bulkRemove()" class="btn active" type="reset" value="一括削除">
                                                </div>
                                                <div class="wrapper-lesson-detail fixHeight lesson-detail" style="height : 300px">
                                                    <ul class="lesson-detail">
                                                        <li class="lesson-detail-item" id="lessonDateTime">
                                                        </li>
                                                        <li class="lesson-detail-item" id="lessonName">
                                                        </li>
                                                        <li class="lesson-detail-item" id="lessonTextName">
                                                        </li>
                                                    </ul>
                                                    <ul class="lesson-detail student-detail">
                                                        <li class="lesson-detail-item" id="studentId"></li>
                                                        <li class="lesson-detail-item" id="studentNickName"></li>
                                                        <li class="lesson-detail-item" id="studentName"></li>
                                                        <li class="lesson-detail-item" id="registerDate"></li>
                                                    </ul>
                                                </div>
                                                <div class="wrapper-btn-single">
                                                        <input id="resistration" :class="['btn', submitFlgConds ? 'active' : '']" type="button" value="登録">
                                                        <input id="remove" class="btn" type="reset" value="レッスン削除">
                                                        <input id="cancel_lesson" class="btn" type="reset" value="レッスンキャンセル">
                                                </div>
                                                <!-- /.wrapper-lesson-detail -->
                                            </div>
                                        </div>
                                        <!-- /.clearfix; -->
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

<script  type="text/javascript">
import axios from "axios";
import Loader from "../common/loader.vue";

export default {
    components: {
        Loader,
    },
    props: ['urlGetData', 'urlRegisterMultiLesson', 'lessonTiming', 'urlRemoveMultiLesson'],
    mounted() {
        this.getData()
    },
    data() {
        return {
            flagShowLoader : false,
            searchInput : "",
            teacherList : [],
            startDate : new Date(),
            lessonList : [],
            dataLessonSchedule : [],
            lessonTextList : [],
            header : [],
            nextLessonTime : null,
            numRow : null,
            teacherId : null,
            preWeek : new Date(),
            nextWeek : new Date(),
            flgShowTable : false,
            dataSelected : [],
            kkk : 7,
            submitFlgConds : false,
            currentIndex : 0,
            removeFlgConds : false
        }
    },
    methods :{
        getData() {
            this.flagShowLoader = true;
            let that = this;
            axios
                .post(that.urlGetData, {
                    week : this.startDate,
                    search_input : this.searchInput,
                    teacher_id : this.teacherId
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        that.flgShowTable = true
                        that.header = response.data.header
                        that.nextWeek = response.data.nextWeek
                        that.preWeek = response.data.preWeek
                        that.teacherList = response.data.teacherList
                        that.dataLessonSchedule = response.data.dataLessonSchedule
                        that.numRow = response.data.numRow
                        that.dataSelected = response.data.dataSelected
                        that.currentIndex = response.data.currentIndex
                        this.submitFlgConds = false
                        this.removeFlgConds = false
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
        },
        changeTeacherId(teacherId) {
            this.teacherId = teacherId
            this.getData()
        },
        changeDate(week) {
            this.startDate = week
            this.getData()
        },
        changeSelected(i,j) {
            this.dataSelected[i][j] = !this.dataSelected[i][j]
            this.$set(this.dataSelected[i], j, this.dataSelected[i][j])
            this.checkConditionSubmit()
            this.checkConditionRemove()
        },
        checkConditionSubmit() {
            this.submitFlgConds = false;
            for (let i = 0; i < this.numRow; i++ ) {
                for (let j = this.currentIndex; j < 7; j++) {
                    if (this.dataSelected[i][j] == true) {
                        this.submitFlgConds = true;
                    }
                }
            }
        },
        checkConditionRemove() {
            this.removeFlgConds = false;
            for (let i = 0; i < this.numRow; i++ ) {
                for (let j = 0; j < 7; j++) {
                    if (this.dataSelected[i][j] == true) {
                        this.removeFlgConds = true;
                    }
                }
            }
        },
        bulkResistration() {
            if (!this.submitFlgConds) {
                this.$swal({
                    text: "レッスンを登録しますか？",
                    icon: "error",
                    confirmButtonText: "OK",
                    showCancelButton : true,
                    cancelButtonText : "閉じる"
                }).then(result => {
                });
            }

            let dataBulkResistration = new Array()
            let idx = 0
            let that = this

            for (let i = 0; i < this.numRow; i++ ) {
                for (let j = this.currentIndex; j < 7; j++) {
                    if (this.dataSelected[i][j] == true) {
                        dataBulkResistration[idx] = new Object()
                        dataBulkResistration[idx].lesson_schedule_id = this.dataLessonSchedule[i][j][0]['lesson_schedule_id']
                        dataBulkResistration[idx].lesson_name = this.dataLessonSchedule[i][j][0]['lesson_name']
                        dataBulkResistration[idx].lesson_type_id = this.dataLessonSchedule[i][j][0]['lesson_type_id']
                        dataBulkResistration[idx].start_time = this.dataLessonSchedule[i][j][0]['start_time']
                        idx++
                    }
                }
            }
            
            axios
                .post(that.urlRegisterMultiLesson, {
                    data_bulk_resistration : dataBulkResistration,
                    teacher_id : this.teacherId,
                    lesson_timing : this.lessonTiming
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.getData()
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
        },
        bulkRemove() {
            if (!this.removeFlgConds) {
                this.$swal({
                    text: "レッスンを登録しますか？",
                    icon: "error",
                    confirmButtonText: "OK",
                    showCancelButton : true,
                    cancelButtonText : "閉じる"
                }).then(result => {
                });
            }

            let dataBulkResistration = new Array()
            let idx = 0
            let that = this

            for (let i = 0; i < this.numRow; i++ ) {
                for (let j = this.currentIndex; j < 7; j++) {
                    if (this.dataSelected[i][j] == true) {
                        dataBulkResistration[idx] = this.dataLessonSchedule[i][j][0]['lesson_schedule_id']
                        idx++
                    }
                }
            }
            
            axios
                .post(that.urlRemoveMultiLesson, {
                    lesson_schedule_ids : dataBulkResistration,
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.getData()
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
        },
        resistration() {
            if (!this.submitFlgConds) {
                this.$swal({
                    text: "レッスンを登録しますか？",
                    icon: "error",
                    confirmButtonText: "OK",
                    showCancelButton : true,
                    cancelButtonText : "閉じる"
                }).then(result => {
                });
            }

            axios
                .post(that.urlRegisterMultiLesson, {
                    data_bulk_resistration : dataBulkResistration,
                    teacher_id : this.teacherId,
                    lesson_timing : this.lessonTiming
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.getData()
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
