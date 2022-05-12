<template>
    <modal name="select-teacher-lesson-modal"   :pivotY="0.1" :reset="true" :width="1000" :height="auto"  :scrollable="true" :adaptive="true" :clickToClose="false" >
        <div class="card">
            <div class="card-header">
                <h5 class="title-page">レッスン状況詳細</h5>
                <div class="float-right">
                    <button type="button" class="close"  v-on:click="hide" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-container" id="lesson_list">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <td class="column-title" style="width:7%">日付</td>
                            <td class="column-title" style="width:7%">時間</td>
                            <td class="column-title">レッスン名</td>
                            <td class="column-title">テキスト名</td>
                            <td class="column-title">講師名</td>
                            <td class="column-title">生徒番号</td>
                            <td class="column-title">生徒名</td>
                            <td class="column-title">生徒ニックネーム</td>
                            <td class="column-title">生徒スカイプ名</td>
                            <td class="column-title" style="width:12%">予約時間</td>
                            <td class="column-title">自由/固定</td>
                        </thead>
                        <tbody>
                            <tr v-for="(item, key) in lessonList" :key="key" :data-row="key" @click="updateFormDetail(key)">
                                <td>
                                    {{ item.lesson_date }}
                                </td>
                                <td>
                                    {{ item.lesson_time }}
                                </td>
                                <td>
                                    {{ item.lesson_name }}
                                </td>
                                <td>
                                    {{ item.lesson_text_name }}
                                </td>
                                <td>
                                    {{ item.teacher_name }}
                                </td>
                                <td>
                                    {{ item.student_id }}
                                </td>
                                <td>
                                    {{ item.student_name }}
                                </td>
                                <td>
                                    {{ item.student_nickname }}
                                </td>
                                <td>
                                    {{ item.student_skype_name }}
                                </td>
                                <td>
                                    {{ item.student_book_time }}
                                </td>
                                <td>
                                    {{ item.is_free_teacher }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="lesson_detail" class="confirm">
                    <div class="block" style="border: solid 1px #ccc">
                        <div class="row">
                            <div class="col-sm-3 text-right">レッスン日時</div>
                            <div class="col-sm-9 condition-group" id="lesson_date">{{ lessonDate }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-right">レッスン名</div>
                            <div class="col-sm-9 condition-group" id="lesson_name">{{ lessonName }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-right">レッスンテキスト名</div>
                            <div class="col-sm-9 condition-group" id="lesson_text_name">{{ lessonTextName }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-right">予約時間</div>
                            <div class="col-sm-9 condition-group" id="student_book_time">{{ studentBookTime }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-right">評価（生徒⇒先生）</div>
                            <div class="col-sm-9 condition-group" id="teacher_rating">{{ teacherRating }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-right">評価（先生⇒生徒）</div>
                            <div class="col-sm-9 condition-group" id="student_rating">{{ studentRating }}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-right">コメント（生徒⇒先生）</div>
                            <div class="col-sm-9 condition-group" id="comment_from_student">
                                <textarea readonly class="d-readonly textarea-comment" rows="4" :value="commentFromStudent"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-right">コメント（先生⇒生徒）</div>
                            <div class="col-sm-9 condition-group" id="comment_from_teacher">
                                <textarea readonly class="d-readonly textarea-comment" rows="4" :value="commentFromTeacher"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <loader :flag-show="flagShowLoaderModal"></loader>
    </modal>
</template>

<script>
    import Loader from "./../../components/common/loader";

    export default {
        name: "modal-table",
        components: {
            Loader
        },
        data() {
            return {
                auto : 'auto',
                lessonList : [],
                lessonDate : '',
                lessonName : '',
                lessonTextName : '',
                studentBookTime : '',
                teacherRating : '',
                studentRating : '',
                commentFromStudent : '',
                commentFromTeacher : '',
                flagShowLoaderModal : false
            };
        },
        props: [ 'urlLessonStatusDetail', 'lessonTime'],
        mounted() {
        },
        created: function () {
        },
        methods: {
            getData() {
                let that = this;
                that.flagShowLoaderModal = true
                this.lessonList = []
                this.lessonDate = ''
                this.lessonName = ''
                this.lessonTextName = ''
                this.studentBookTime = ''
                this.teacherRating = ''
                this.studentRating = ''
                this.commentFromStudent = ''
                this.commentFromTeacher = ''
                axios.post(that.urlLessonStatusDetail, {
                    lesson_time : that.lessonTime
                })
                .then(function (response) {
                    that.flagShowLoaderModal = false
                    if (response.data.status == 200) {
                        that.lessonList = response.data.lessonStatusDetail
                    } else {
                        that.$swal({
                            title: 'エラーが発生しました',
                            icon: "error",
                            confirmButtonText: "OK",
                        }).then(function (confirm) {});
                    }
                })
                .catch(function (error) {
                    this.hide()
                    that.flagShowLoaderModal = false
                    that.$swal({
                        title: "エラーが発生しました",
                        icon: "error",
                        confirmButtonText: "OK",
                    }).then(function (confirm) {});
                });
            },
            hide () {
                this.$modal.hide('select-teacher-lesson-modal');
            },
            updateFormDetail(key) {
                this.lessonDate = this.lessonList[key].lesson_date;
                this.lessonName = this.lessonList[key].lesson_name;
                this.lessonTextName = this.lessonList[key].lesson_text_name;
                this.studentBookTime = this.lessonList[key].student_book_time;
                this.teacherRating = this.lessonList[key].teacher_rating;
                this.studentRating = this.lessonList[key].student_rating;
                this.commentFromStudent = this.lessonList[key].comment_from_student_to_teacher;
                this.commentFromTeacher = this.lessonList[key].comment_from_teacher_to_student;
            }
        },
        computed: {
            
        },
        watch: {
            lessonTime() {
                if (this.lessonTime) {
                    this.getData()
                }
            }
        }
    }
</script>

<style scoped>

</style>
