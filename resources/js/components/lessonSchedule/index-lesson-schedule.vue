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
                                        <h2 class="title-table" style="margin-top: 0px;">レッスンスケジュール</h2>
                                        <div class="clearfix wrap-border">
                                            <div class="col-main-table">
                                                <ul class="nav-table schedule-nav-tbl">
                                                    <li class="nav-table-item nav-table-item-lesson-schedule">
                                                        <span @click="changeDate(preWeek)">前週</span>
                                                    </li>
                                                    <li class="nav-table-item nav-table-item-lesson-schedule">
                                                        <span @click="changeDate(new Date())">更新</span>
                                                    </li>
                                                    <li class="nav-table-item nav-table-item-lesson-schedule">
                                                        <span @click="changeDate(nextWeek)">次週</span>
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
                                                <h3 class="title-side-table">詳細</h3>
                                                <div class="subtitle-side-table"></div>
                                                <div class="wrapper-btn-bulk">
                                                    <input id="bulk-resistration" @click="bulkResistration()" class="btn active" type="button" value="一括登録">
                                                    <input id="bulk-remove" @click="bulkRemove()" class="btn active" type="reset" value="一括削除">
                                                </div>
                                                <div class="wrapper-lesson-detail fixHeight lesson-detail" style="height : 300px">
                                                    <ul class="lesson-detail">
                                                        <li class="lesson-detail-item" id="lessonDateTime">
                                                            {{ itemSelected['time_format'] }}
                                                        </li>
                                                        <li class="lesson-detail-item" id="lessonName">
                                                            {{ lessonNameSelected }}
                                                        </li>
                                                        <li class="lesson-detail-item" id="lessonTextName">
                                                        </li>
                                                    </ul>
                                                    <ul class="lesson-detail student-detail">
                                                        <li class="lesson-detail-item" id="studentId">{{ studentId }}</li>
                                                        <li class="lesson-detail-item" id="studentNickName">{{ studentNickname }}</li>
                                                        <li class="lesson-detail-item" id="studentName"></li>
                                                        <li class="lesson-detail-item" id="registerDate"></li>
                                                    </ul>
                                                </div>
                                                <div class="wrapper-btn-single">
                                                        <input id="resistration" :class="['btn', submitFlgConds ? 'active' : '']" @click="resistration()" type="button" value="登録">
                                                        <input id="remove" :class="['btn', removeFlgConds ? 'active' : '']" type="reset" @click="remove()" value="レッスン削除">
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
    props: ['urlGetData', 'urlRegisterMultiLesson', 'lessonTiming', 'urlRemoveMultiLesson', 'urlRemoveLesson', 'urlRegisterLesson', 'lessonScheduleInPast', 'lessonScheduleInPresent'],
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
            dataRegisted : [],
            submitFlgConds : false,
            currentIndex : 0,
            removeFlgConds : false,
            itemSelected : [],
            lessonNameSelected : '',
            studentId : '',
            studentNickname : '',
            teacherZoomId: null,
            currentRowIndex : 0
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
                        that.dataRegisted = response.data.dataRegisted
                        that.currentIndex = response.data.currentIndex
                        that.currentRowIndex = response.data.currentRowIndex
                        that.teacherZoomId = response.data.teacherZoomId
                        this.submitFlgConds = false
                        this.removeFlgConds = false
                    } else {
                        this.$swal({
                            text: "レッスンスケジュールを取得できません。",
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
            this.itemSelected = this.dataLessonSchedule[i][j][0]
            this.lessonNameSelected = ''
            this.studentId = ''
            this.studentNickname = ''

            this.lessonNameSelected = this.itemSelected['lesson_name_selected']

            if (this.itemSelected['course_type'] == 0) {
                this.studentId = this.itemSelected['student_id']
                this.studentNickname = this.itemSelected['student_name']
            }

            if (this.dataSelected[i][j]) {
                this.submitFlgConds = false;
                this.removeFlgConds = false;

                if (this.currentIndex > this.lessonScheduleInPresent) {
                    if (!this.dataRegisted[i][j] && (( j == this.currentIndex && i >= this.currentRowIndex) || j > this.currentIndex)) {
                        this.submitFlgConds = true;
                    }
                } else if (!this.dataRegisted[i][j] && this.currentIndex != this.lessonScheduleInPast) {
                    this.submitFlgConds = true;
                }
                
                if (this.dataRegisted[i][j] && this.itemSelected['course_type'] != 1) {
                    this.removeFlgConds = true;
                }
            } else {
                this.itemSelected = []
                this.submitFlgConds = false
                this.removeFlgConds = false
                this.lessonNameSelected = ''
                // this.checkConditionSubmit()
                // this.checkConditionRemove()
            }
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
                    if (this.dataSelected[i][j] == true ) {
                        this.removeFlgConds = true;
                    }
                }
            }
        },
        bulkResistration() {
            let dataBulkResistration = new Array()
            let idx = 0
            let that = this
            let submitBulkRegistionFlg = true;
            let errorMessage = "";

            if (this.currentIndex == this.lessonScheduleInPast) {
                submitBulkRegistionFlg = false;
                errorMessage = "登録可能な日時を選択してください"
            } else {
                for (let i = 0; i < this.numRow; i++ ) {
                    for (let j = 0; j < 7; j++) {
                        if (this.dataSelected[i][j] == true) {
                            if (!((j == this.currentIndex && i >= this.currentRowIndex) || j > this.currentIndex)) {
                                submitBulkRegistionFlg = false;
                                errorMessage = "登録可能な日時を選択してください"
                                break;
                            } else if(this.dataRegisted[i][j] || (this.dataLessonSchedule[i][j]['0']['course_type'] != 'undefined' && this.dataLessonSchedule[i][j]['0']['course_type'] == 1)) {
                                submitBulkRegistionFlg = false;
                                errorMessage = "グループレッスンのスケジュールを登録・削除できません。"
                                break;
                            } else {
                                dataBulkResistration[idx] = new Object()
                                dataBulkResistration[idx].lesson_schedule_id = this.dataLessonSchedule[i][j][0]['lesson_schedule_id']
                                dataBulkResistration[idx].lesson_name = this.dataLessonSchedule[i][j][0]['lesson_name']
                                dataBulkResistration[idx].lesson_type_id = this.dataLessonSchedule[i][j][0]['lesson_type_id']
                                dataBulkResistration[idx].start_time = this.dataLessonSchedule[i][j][0]['start_time']
                                idx++
                            }
                        }
                    }
                    if (!submitBulkRegistionFlg) {
                        break;
                    }
                }
            }

            if (!submitBulkRegistionFlg) {
                    this.$swal({
                        text: errorMessage,
                        icon: "error",
                        cancelButtonText: "閉じる",
                        showCancelButton : true,
                        showConfirmButton : false,
                    }).then(result => {
                    });
            } else {
                this.flagShowLoader = true;

                axios
                    .post(that.urlRegisterMultiLesson, {
                        data_bulk_resistration : dataBulkResistration,
                        teacher_id : this.teacherId,
                        lesson_timing : this.lessonTiming,
                        teacher_zoom_id : this.teacherZoomId
                    })
                    .then(response => {
                        that.flagShowLoader = false;
                        if (response.data.status == "OK") {
                            this.getData()
                        } else {
                            this.$swal({
                                text: "レッスンスケジュールを登録できません。",
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
        },
        bulkRemove() {
            // if (!this.removeFlgConds) {
            //     this.$swal({
            //         text: "レッスンを登録しますか？",
            //         icon: "error",
            //         confirmButtonText: "OK",
            //         showCancelButton : true,
            //         cancelButtonText : "閉じる"
            //     }).then(result => {
            //     });
            // }
            let dataBulkResistration = new Array()
            let idx = 0
            let that = this
            let submitBulkRegistionFlg = true;
            let errorMessage = "";

            for (let i = 0; i < this.numRow; i++ ) {
                for (let j = 0; j < 7; j++) {
                    if(this.dataSelected[i][j] && this.dataLessonSchedule[i][j][0]['course_type'] == 1) {
                        submitBulkRegistionFlg = false;
                        errorMessage = "グループレッスンのスケジュールを登録・削除できません。"
                        break;
                    } else if (!this.dataRegisted[i][j] && this.dataSelected[i][j]) {
                        submitBulkRegistionFlg = false;
                        errorMessage = "削除可能な日時を選択してください"
                        break;
                    } else if(this.dataRegisted[i][j] && this.dataSelected[i][j]){
                        dataBulkResistration[idx] = this.dataLessonSchedule[i][j][0]['lesson_schedule_id']
                        idx++
                    }
                }
            }
            // console.log(submitBulkRegistionFlg);
            // console.log(dataBulkResistration);
            
            // if (submitBulkRegistionFlg && dataBulkResistration.length == 0) {
            //     console.log('xxx');
            //     submitBulkRegistionFlg = false;
            //     errorMessage = "削除可能な日時を選択してください"
            // }

            if (!submitBulkRegistionFlg) {
                    this.$swal({
                        text: errorMessage,
                        icon: "error",
                        cancelButtonText: "閉じる",
                        showCancelButton : true,
                        showConfirmButton : false,
                    }).then(result => {
                    });
            } else {
                this.flagShowLoader = true;

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
                                text: "レッスンスケジュールを削除できません。",
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
        },
        remove() {
            // if (!this.removeFlgConds) {
            //     this.$swal({
            //         text: "レッスンを登録しますか？",
            //         icon: "error",
            //         confirmButtonText: "OK",
            //         showCancelButton : true,
            //         cancelButtonText : "閉じる"
            //     }).then(result => {
            //     });
            // }
            this.flagShowLoader = true;
            let that = this

            axios
                .post(that.urlRemoveLesson, {
                    lesson_schedule_id : this.itemSelected['lesson_schedule_id']
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.getData()
                    } else {
                        this.$swal({
                            text: "レッスンスケジュールを削除できません。",
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
                return false;
            }

            let that = this
            this.flagShowLoader = true;

            axios
                .post(that.urlRegisterLesson, {
                    lesson_schedule_id : this.itemSelected['lesson_schedule_id'],
                    teacher_id : this.teacherId,
                    start_time : this.itemSelected['start_time'],
                    lesson_timing : this.lessonTiming,
                    teacher_zoom_id : this.teacherZoomId
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.getData()
                    } else {
                        this.$swal({
                            text: "レッスンスケジュールを登録できません。",
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
