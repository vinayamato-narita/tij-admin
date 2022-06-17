<template>
    <section>
    <modal name="edit-schedule-modal" :pivotY="0.1" :reset="true" :width="'70%'" height="auto" :adaptive="false" :scrollable="true" :clickToClose="false" shiftX="0.2" :shiftY="0.3" classes="" @opened="opened" @before-open="beforeOpen">
        <div class="card">
            <div class="card-header"> {{ selectedEvent != null ? "スケジュール編集" : "スケジュール新規登録" }}
                <div class="float-right">
                    <button type="button" class="close"  v-on:click="hide" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >開始日時：
                            </label>
                            <div class="col-md-3">
                                <date-picker
                                    v-model="startDate"
                                    value-type="format"
                                    format="YYYY/MM/DD"
                                    :disabled-date="notBeforeDateNow"
                                    :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)"
                                ></date-picker>
                            </div>
                            <div class="col-md-3">
                                <date-picker
                                  v-model="startTime"
                                  :minute-step="30"
                                  format="HH:mm"
                                  value-type="format"
                                  type="time"
                                  placeholder="HH:mm"
                                  :disabled-time="notBeforeTimeNow"
                                  :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)"
                                ></date-picker>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >終了日時：
                            </label>
                            <div class="col-md-6">
                              {{ endDateTime }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >コース名：
                            </label>
                            <div class="col-md-6">
                                <select
                                    class="form-control"
                                    name="selectedCourse"
                                    v-validate="{ is_not: 0 }"
                                    v-model="selectedCourse"
                                    @change="onChangeCourse($event)"
                                    :disabled="selectedEvent != null"
                                >
                                    <option value="0">選択されていません</option>
                                    <option v-for="option in courseData" v-bind:value="option.course_id">
                                      {{ option.course_name }}
                                    </option>
                                </select>
                                <div v-if="initFlag" class="input-group is-danger error" role="alert">
                                    {{ errors.first("selectedCourse") }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >レッスン名：
                            </label>
                            <div class="col-md-6">
                                <select
                                    class="form-control"
                                    name="selectedLesson"
                                    v-validate="{ is_not: 0 }"
                                    v-model="selectedLesson"
                                    @change="onChangeLesson($event)"
                                    :disabled="selectedEvent != null"
                                >
                                    <option value="0">選択されていません</option>
                                    <option v-for="option in lessonData" v-bind:value="option.lesson_id">
                                      {{ option.lesson_name }}
                                    </option>
                                </select>
                                <div v-if="initFlag" class="input-group is-danger error" role="alert">
                                    {{ errors.first("selectedLesson") }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >講師名：
                            </label>
                            <div class="col-md-6">
                                <select
                                    class="form-control"
                                    name="selectedTeacher"
                                    v-model="selectedTeacher"
                                    v-validate="{ is_not: 0 }"
                                >
                                    <option value="0">選択されていません</option>
                                    <option v-for="option in teacherData" v-bind:value="option.teacher_id">
                                      {{ option.teacher_name }}
                                    </option>
                                </select>
                                <div v-if="initFlag" class="input-group is-danger error" role="alert">
                                    {{ errors.first("selectedTeacher") }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >Zoom連携：
                            </label>
                            <div class="col-md-6 d-flex justify-content-between align-items-center flex-wrap">
                                <label class="switch mb-0">
                                    <input type="checkbox" v-model="linkZoomScheduleFlag" :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)">
                                    <span class="slider round"></span>
                                </label>
                                <!-- <a v-if="linkZoomScheduleFlag" @click="showZoomSetting = !showZoomSetting" href="javascript:;" class="btn-link-zoom">Zoom設定詳細表示</a> -->
                            </div>
                        </div>
                        <div class="form-group row" v-if="!linkZoomScheduleFlag">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >スケジューラリンク：
                            </label>
                            <div class="col-md-6">
                                <input
                                    class="form-control"
                                    type="text"
                                    name="zoomUrl"
                                    v-model="zoomUrl"
                                    v-validate="{ required: !linkZoomScheduleFlag }"
                                    :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)"
                                >
                                <div v-if="initFlag" class="input-group is-danger error" role="alert">
                                    {{ errors.first("zoomUrl") }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row hidden" v-if="linkZoomScheduleFlag && showZoomSetting">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >Zoomアカウント：
                            </label>
                            <div class="col-md-6">
                                <select
                                    class="form-control"
                                    name="selectedZoomAccount"
                                    v-model="zoomAccountId"
                                    :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)"
                                >
                                    <option value=""></option>
                                    <option v-for="item in zoomAccounts" :key="item.zoom_account_id" :value="item.zoom_account_id">{{item.zoom_account_name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row hidden" v-if="linkZoomScheduleFlag && showZoomSetting">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >会議開始前参加：
                            </label>
                            <div class="col-md-6 col-form-label">
                                <div class="form-check form-check-inline mr-1">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    value="0"
                                    name="joinBeforeHost"
                                    id="inline-radio1"
                                    v-validate="'required'"
                                    v-model="joinBeforeHost"
                                    :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)"
                                />
                                <label class="form-check-label" for="inline-radio1"
                                    >無効</label
                                >
                                </div>
                                <div class="form-check form-check-inline mr-1">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    value="1"
                                    name="joinBeforeHost"
                                    id="inline-radio2"
                                    v-validate="'required'"
                                    v-model="joinBeforeHost"
                                    :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)"
                                />
                                <label class="form-check-label" for="inline-radio2"
                                    >有効</label
                                >
                                </div>
                                <div class="input-group is-danger" role="alert">
                                {{ errors.first("joinBeforeHost") }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row hidden" v-if="linkZoomScheduleFlag && showZoomSetting">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="text-input"
                                >待機室：
                            </label>
                            <div class="col-md-6 col-form-label">
                                <div class="form-check form-check-inline mr-1">
                                <input
                                    class="form-check-input"
                                    id="inline-radio3"
                                    type="radio"
                                    value="0"
                                    name="waitingRoom"
                                    v-validate="'required'"
                                    v-model="waitingRoom"
                                    :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)"
                                />
                                <label class="form-check-label" for="inline-radio3"
                                    >無効</label
                                >
                                </div>
                                <div class="form-check form-check-inline mr-1">
                                <input
                                    class="form-check-input"
                                    id="inline-radio4"
                                    type="radio"
                                    value="1"
                                    name="waitingRoom"
                                    v-validate="'required'"
                                    v-model="waitingRoom"
                                    :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)"
                                />
                                <label class="form-check-label" for="inline-radio4"
                                    >有効</label
                                >
                                </div>
                                <div class="input-group is-danger" role="alert">
                                {{ errors.first("waitingRoom") }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row hidden" v-if="linkZoomScheduleFlag && showZoomSetting">
                            <label
                                class="col-md-3 col-form-label text-md-right"
                                for="autoRecording"
                                >録画方法: <span class="glyphicon glyphicon-star"></span
                            ></label>
                            <div class="col-md-6 col-form-label">
                                <select
                                    class="form-control"
                                    id="autoRecording"
                                    name="autoRecording"
                                    v-validate="'required'"
                                    v-model="autoRecording"
                                    :disabled="selectedEvent != null && boughtCourse.includes(selectedEvent.course_id)"
                                >
                                <option value="0">ローカル</option>
                                <option value="1">クラウド</option>
                                <option value="2">無効</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-center">
                    <div class="line"></div>
                    <div class="form-group">
                        <div class="text-center">
                            <button type="submit" class="btn btn-default mr-2" v-if="selectedEvent != null" v-on:click="deleteSchedule">削除</button>
                            <button type="submit" class="btn btn-primary mr-2" v-on:click="registerSchedule">登録</button>
                            <a v-on:click="hide" class="btn btn-default">閉じる</a></div>
                    </div>
                </div>
            </div>
        </div>
    </modal>
    <loader :flag-show="flagShowLoader"></loader>
    </section>
</template>

<script>
import axios from 'axios';
import Loader from "./../common/loader";
import moment from "moment";
import japan from 'vee-validate/dist/locale/ja';

export default {
    name: "edit-schedule-modal",
    components: {
        Loader
    },
    data() {
        return {
            baseUrl: Laravel.baseUrl,
            flagShowLoader: false,
            initFlag: false,
            startDate: "",
            startTime: "",
            startDateTime: null,
            endDateTime: null,
            selectedCourse: 0,
            selectedLesson: 0,
            selectedTeacher: 0,
            courseData: [],
            lessonData: [],
            teacherData: [],
            linkZoomScheduleFlag: true,
            zoomUrl: "",
            joinBeforeHost: 1,
            waitingRoom: 0,
            autoRecording: 1,
            showZoomSetting: false,
            zoomAccounts: [],
            zoomAccountId: '',
            zoomSetting: {}
        };
    },
    props: [ 'event', 'selectedTime', 'selectedEvent', 'boughtCourse' ],
    mounted() {

    },
    created: function () {
        let messError = {
            custom : {
                selectedCourse: {
                    is_not : "コース名を選択してください。"
                },
                selectedLesson: {
                    is_not : "レッスン名を選択してください。"
                },
                selectedTeacher: {
                    is_not : "講師名を選択してください。"
                },
                zoomUrl: {
                    required : "スケジューラリンクを入力してください"
                },
            }
        };
        this.$validator.localize('ja', messError);
        this.getDataZoom();
    },
    methods: {
        hide () {
            this.$modal.hide('edit-schedule-modal');
        },
        beforeOpen () {

        },
        deleteSchedule () {
            let that = this;
            that.flagShowLoader = true;
            axios.post(that.baseUrl + '/groupSchedule/deleteSchedule', {
                selectedEvent : that.selectedEvent
            })
            .then(function (response) {
                console.log(response)
                that.flagShowLoader = false;
                that.hide()
                if (response.data.status == 200) {
                    that.$swal({
                        text: "スケジュール削除が完了しました。",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(result => {
                        window.location.reload()
                    });
                } else {
                    that.$swal({
                        title: response.data.error_message,
                        icon: "error",
                        confirmButtonText: "OK",
                    }).then(function (confirm) {});
                }
            })
            .catch(function (error) {
                that.flagShowLoader = false;
                that.hide()
                that.$swal({
                    title: "スケジュール削除が失敗しました。",
                    icon: "error",
                    confirmButtonText: "OK",
                }).then(function (confirm) {});
            });
        },
        registerSchedule () {
            let that = this;
            this.$validator.validateAll().then((valid) => {
                if (valid) {
                    that.flagShowLoader = true;
                    axios.post(that.baseUrl + '/groupSchedule/registerSchedule', {
                        startDateTime : that.startDateTime,
                        endDateTime : that.endDateTime,
                        selectedCourse : that.selectedCourse,
                        selectedLesson : that.selectedLesson,
                        selectedTeacher : that.selectedTeacher,
                        selectedEvent : that.selectedEvent,
                        zoomAccountId: that.zoomAccountId,
                        linkZoomScheduleFlag: that.linkZoomScheduleFlag,
                        joinBeforeHost: that.joinBeforeHost,
                        waitingRoom: that.waitingRoom,
                        autoRecording: that.autoRecording,
                        zoomUrl: that.zoomUrl
                    })
                    .then(function (response) {
                        console.log(response)
                        that.flagShowLoader = false;
                        that.hide()
                        if (response.data.status == 200) {
                            that.$swal({
                                text: "スケジュール登録が完了しました。",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(result => {
                                var url_string = window.location.href
                                var url = new URL(url_string);
                                if (url.searchParams.has('selectedDate')) {
                                    url.searchParams.set('selectedDate', that.startDateTime)
                                } else {
                                    url.searchParams.append('selectedDate', that.startDateTime)
                                }
                                url.search = url.searchParams;
                                url        = url.toString();
                                window.location.href = url;
                            });
                        } else {
                            that.$swal({
                                title: response.data.error_message,
                                icon: "error",
                                confirmButtonText: "OK",
                            }).then(function (confirm) {});
                        }
                    })
                    .catch(function (error) {
                        that.flagShowLoader = false;
                        that.hide()
                        that.$swal({
                            title: "スケジュール登録が失敗しました。",
                            icon: "error",
                            confirmButtonText: "OK",
                        }).then(function (confirm) {});
                    });
                } else {
                    console.log(that.errors)
                }
            });
        },
        opened () {
            console.log(this.selectedEvent)
            this.initFlag = false
            this.selectedCourse = this.selectedEvent != null ? this.selectedEvent.course_id : 0
            this.selectedLesson = this.selectedEvent != null ? this.selectedEvent.lesson_id : 0
            this.selectedTeacher = this.selectedEvent != null ? this.selectedEvent.teacher_id : 0
            if (this.selectedEvent != null && this.selectedEvent.join_before_host != undefined) {
                this.joinBeforeHost = this.selectedEvent.join_before_host
            } else {
                this.joinBeforeHost = this.zoomSetting != null ? this.zoomSetting.join_before_host : 1
            }
            if (this.selectedEvent != null && this.selectedEvent.waiting_room != undefined) {
                this.waitingRoom = this.selectedEvent.waiting_room
            } else {
                this.waitingRoom = this.zoomSetting != null ? this.zoomSetting.waiting_room : 0
            }
            if (this.selectedEvent != null && this.selectedEvent.auto_recording != undefined) {
                this.autoRecording = this.selectedEvent.auto_recording
            } else {
                this.autoRecording = this.zoomSetting != null ? this.zoomSetting.auto_recording : 1
            }
            this.linkZoomScheduleFlag = this.selectedEvent != null ? (this.selectedEvent.link_zoom_schedule_flag == 1 ? true : false) : true
            this.zoomAccountId = this.selectedEvent != null ? this.selectedEvent.zoom_account_id : ''
            this.zoomUrl = this.selectedEvent != null ? this.selectedEvent.zoom_url : ''
            this.showZoomSetting = this.selectedEvent != null ? (this.selectedEvent.link_zoom_schedule_flag == 1 ? true : false) : false

            var dateTime = new Date(new Date().setHours(new Date(new Date().toLocaleString("ja-JP", { timeZone: 'Asia/Tokyo' })).getHours() + 1))

            if (this.selectedTime > new Date(new Date().toLocaleString("ja-JP", { timeZone: 'Asia/Tokyo' }))) {
                dateTime = this.selectedTime
            }

            var m = dateTime.getMinutes().toString().padStart(2, '0') >= 30 ? ':30' : ':00'
            this.startDate = dateTime.getFullYear().toString() + '/' + (dateTime.getMonth() + 1).toString().padStart(2, '0') + '/' + dateTime.getDate().toString().padStart(2, '0')
            this.startTime = dateTime.getHours().toString().padStart(2, '0') + m
        },
        notBeforeDateNow (date) {
          var hours = this.startTime != "" ? this.startTime.slice(0, 2) : 23
          var minute = this.startTime != "" ? this.startTime.slice(2, 2) : 59
            return date.setHours(hours, minute, 0, 0) < new Date(new Date().toLocaleString("ja-JP", { timeZone: 'Asia/Tokyo' }));
        },
        notBeforeTimeNow (date) {
            return new Date(this.startDate).setHours(date.getHours(), date.getMinutes(), 0, 0) <= new Date(new Date().toLocaleString("ja-JP", { timeZone: 'Asia/Tokyo' }));
        },
        changeDateTime (date) {
            this.startDateTime = this.startDate + " " + this.startTime
            this.endDateTime = moment(this.startDateTime).add(50, 'm').format("YYYY/MM/DD HH:mm")
            this.getData()
        },
        onChangeCourse (event) {
            console.log(event.target.value)
            console.log(this.courseData[event.target.value])
            this.teacherData = []
            this.lessonData = []
            if (this.courseData.length != 0 && this.courseData[event.target.value] != undefined) {
                this.lessonData = this.courseData[event.target.value]['lesson']
            }
        },
        onChangeLesson (event) {
            if (this.lessonData.length != 0 && this.lessonData[event.target.value] != undefined) {
                this.teacherData = this.lessonData[event.target.value]['teacher']
            } else {
                this.teacherData = []
            }
        },
        getData () {
            this.courseData = []
            this.teacherData = []
            this.lessonData = []
            let that = this;
            axios.get(that.baseUrl + '/groupSchedule/getData', {
                params: {
                    time : that.startDateTime == null ? that.selectedTime : that.startDateTime,
                }
            })
            .then(function (response) {
                console.log(response)
                that.courseData = response.data.data

                if (that.courseData.length != 0 && that.courseData[that.selectedCourse] != undefined) {
                    that.lessonData = that.courseData[that.selectedCourse]['lesson']
                }

                if (that.lessonData.length != 0 && that.lessonData[that.selectedLesson] != undefined) {
                    that.teacherData = that.lessonData[that.selectedLesson]['teacher']
                }

                that.errors.clear()
                that.initFlag = true
            })
            .catch(function (error) {
            });
        },
        getDataZoom () {
             let that = this;
            axios.get(that.baseUrl + '/groupSchedule/getZoom')
            .then(function (response) {
                console.log(response.data);
                that.zoomAccounts = response.data.zoomAccountList;
                that.zoomSetting = response.data.zoomSetting;
            })
        },
    },
    watch: {
        startDate (value) {
            this.changeDateTime()
        },
        startTime (value) {
            this.changeDateTime()
        },
    },
}
</script>
