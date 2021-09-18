<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
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
                                <div class="card-header">講師情報
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
                                            {{this.teacher.is_free_teacher ? '固定' : '自由'}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">性別:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.teacher.teacher_sex ? '女性' : '男性'}}

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
                                            {{this.teacher.photoSavepath}}

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">レッスン一覧
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
                                                <a href="javascript:void(0)" class="text-dark pull-right text-left btn-default btn-item-dropdown btn-sm btn-delete-teacher-leson" onclick="return false" data-toggle="tooltip" data-placement="top" data-teacher-lesson-id="19863" style="padding: 0 0" data-original-title="" title="">
                                                    <i class="fa fa-trash" aria-hidden="true"></i> 削除
                                                </a>
                                            </div>
                                        </li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <modal-table :url="lessonListUrl" :data-query="dataQuery" :pageSizeLimit="pageSizeLimit" :id="teacher.id">

                </modal-table>
            </div>
        </main>
    </div>


</template>

<script>
    import axios from 'axios';
    import PageSize from "./../../components/common/page-size";
    import InputSearch from "./../../components/common/input-search";
    import Loader from "./../../components/common/loader";
    import ModalTable from "../common/modal-table";

    export default {
        created: function () {
        },
        components: {
            ModalTable,
            Loader,
            PageSize,
            InputSearch
        },
        data() {
            return {
                showModal: false,
                csrfToken: Laravel.csrfToken,
                pageLimit : 20,
        };
        },
        props: ["listTeacherUrl", "createUrl", 'teacher', 'editTeacherUrl', 'pageSizeLimit', 'lessonListUrl', 'dataQuery'],
        mounted() {

        },
        methods: {
            onChangePageSize(event) {
                console.log(event)
/*                let pathname = window.location.pathname;
                let search = window.location.search;
                if (search.indexOf("limit=") >= 0) {
                    search = search.replace(
                        /limit=([0-9]*)/gi,
                        "limit=" + event.target.value
                    );
                } else {
                    if (search == "") {
                        search = search + "?limit=" + event.target.value;
                    } else {
                        search = search + "&limit=" + event.target.value;
                    }
                }
                window.location = window.location.origin + pathname + search;*/
            },

            show () {

                this.$modal.show('select-teacher-lesson-modal');
            },
            hide () {
                this.$modal.hide('select-teacher-lesson-modal');
            }
        },
    }
</script>
