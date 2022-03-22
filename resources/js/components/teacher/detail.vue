<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid info-screen">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            講師情報表示

                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">講師情報</h5>
                                    <div class="float-right">
                                        <a href="#" class="btn btn-primary ">
                                            パスワード設定・変更
                                        </a>
                                        <a :href="this.editTeacherUrl" class="btn btn-primary ">編集</a>
                                    </div>

                                </div>
                                <div class="card-body">


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">表示順:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.display_order}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">講師名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_name}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">ニックネーム:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_nickname}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">メールアドレス:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_email}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">タイムゾーン:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2" v-if="this.teacher.time_zone">
                                            {{this.teacher.time_zone.timezone_name_native}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">固定/自由:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.is_free_teacher === '0' ? '固定' : '自由'}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">性別:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_sex === '0' ? '女性' : '男性'}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">誕生日:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_birthday}}

                                        </div>
                                    </div>


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">出身国:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_university}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">居住地:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_department}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">英語対応:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_hobby}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">自己紹介:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_introduction}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">管理者からの紹介:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.introduce_from_admin}}

                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">管理者メモ:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_note}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">イメージ画像::
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            <a :href="this.teacher.photo_savepath" class="text-dark">
                                                {{this.teacher.photo_savepath}}

                                            </a>

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">個人ミーティングID:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.zoom_personal_meeting_id}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">パスワード:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.zoom_password}}

                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">英語版</h5>
                                    <div class="float-right">
                                        <a :href="urlTeacherEn" class="btn btn-primary ">編集</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">講師名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherEnInfo, "teacher_name") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">ニックネーム:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherEnInfo, "teacher_nickname") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">出身国:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherEnInfo, "teacher_university") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">居住地:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherEnInfo, "teacher_department") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">自己紹介:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherEnInfo, "teacher_introduction") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">管理者からの紹介:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherEnInfo, "introduce_from_admin") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">中国語版</h5>
                                    <div class="float-right">
                                        <a :href="urlTeacherZh" class="btn btn-primary ">編集</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">講師名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherZhInfo, "teacher_name") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">ニックネーム:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherZhInfo, "teacher_nickname") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">出身国:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherZhInfo, "teacher_university") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">居住地:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherZhInfo, "teacher_department") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">自己紹介:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherZhInfo, "teacher_introduction") }}
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">管理者からの紹介:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ getTeacherContent(teacherZhInfo, "introduce_from_admin") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">レッスン一覧</h5>
                                    <div class="float-right">
                                        <a href="javascript:void(0);" v-on:click="show" class="btn btn-primary ">
                                            追加
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="lesson in this.teacher.lesson">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text">{{lesson.lesson_name}}</div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                            :delete-action="getUriDelete(teacher.teacher_id , lesson.lesson_id)"
                                                            :message-confirm="messageConfirm"
                                                    >
                                                    </DeleteItem>
                                                </div>


                                            </div>
                                        </li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <teacher-lesson 
                    :detailUrl="detailTeacherUrl" 
                    :url="lessonListUrl"  
                    :pageSizeLimit="pageSizeLimit" 
                    :id="teacher.id" 
                    :register-url="registerUrl">
                    
                </teacher-lesson>
            </div>
        </main>
    </div>


</template>

<script>
    import axios from 'axios';
    import PageSize from "./../../components/common/page-size";
    import InputSearch from "./../../components/common/input-search";
    import Loader from "./../../components/common/loader";
    import TeacherLesson from "../teacher/teacher-lesson";
    import DeleteItem from "./../../components/common/delete-item";

    export default {
        created: function () {
        },
        components: {
            TeacherLesson,
            Loader,
            PageSize,
            InputSearch,
            DeleteItem
        },
        data() {
            return {
                type: 'teacher',
                showModal: false,
                csrfToken: Laravel.csrfToken,
                messageConfirm : 'このレッスンを解除しますか？',
            };
        },
        props: ["listTeacherUrl", "createUrl", 'teacher', 'editTeacherUrl', 'pageSizeLimit', 'lessonListUrl', 'dataQuery', 'registerUrl','detailTeacherUrl', 'teacherEnInfo', 'teacherZhInfo','urlTeacherEn', 'urlTeacherZh'],
        mounted() {

        },
        methods: {
            show () {

                this.$modal.show('select-teacher-lesson-modal');
            },
            hide () {
                this.$modal.hide('select-teacher-lesson-modal');
            },
            getUriDelete(id, lessonId) {
                 return  id + '/lesson/' + lessonId + '/delete';

            },
            getTeacherContent(teacher, field) {
                if (teacher == null) {
                    return "";
                }
                return teacher[field];
            }
        },
    }
</script>
