<template>
    <section>
    <modal name="edit-schedule-modal" :pivotY="0.1" :reset="true" :width="'70%'" height="auto" :adaptive="false" :scrollable="true" :clickToClose="false" shiftX="0.2" :shiftY="0.3" classes="" @opened="opened" @before-open="beforeOpen">
        <div class="card">
            <div class="card-header"> スケジュール新規登録
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
                    </div>
                </div>
                <div class="form-actions text-center">
                    <div class="line"></div>
                    <div class="form-group">
                        <div class="text-center">
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
        };
    },
    props: [ 'event', 'selectedTime', 'selectedEvent' ],
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
            }
        };
        this.$validator.localize('ja', messError);
    },
    methods: {
        hide () {
            this.$modal.hide('edit-schedule-modal');
        },
        beforeOpen () {

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

            var dateTime = new Date(new Date().setHours(new Date().getHours() + 1))

            if (this.selectedTime > new Date()) {
                dateTime = this.selectedTime
            }

            this.startDate = dateTime.getFullYear().toString() + '/' + (dateTime.getMonth() + 1).toString().padStart(2, '0') + '/' + dateTime.getDate().toString().padStart(2, '0')
            this.startTime = dateTime.getHours().toString().padStart(2, '0') + ':' + dateTime.getMinutes().toString().padStart(2, '0')
        },
        notBeforeDateNow (date) {
          var hours = this.startTime != "" ? this.startTime.slice(0, 2) : 23
          var minute = this.startTime != "" ? this.startTime.slice(2, 2) : 59
            return date.setHours(hours, minute, 0, 0) < new Date();
        },
        notBeforeTimeNow (date) {
            return new Date(this.startDate).setHours(date.getHours(), date.getMinutes(), 0, 0) <= Date.now();
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
        }
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
