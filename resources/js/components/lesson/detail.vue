<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid info-screen">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            レッスン情報表示


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">レッスン情報

                                    <div class="float-right">
                                        <a :href="this.editLessonUrl" class="btn btn-primary ">編集</a>
                                    </div>

                                </div>
                                <div class="card-body">


                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">表示順:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.lesson.display_order}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">レッスンID:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.lesson.lesson_id}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">レッスン名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.lesson.lesson_name}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">テストあり/なし:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.lesson.is_test_lesson ? 'あり': 'なし'}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">サジェスト検索に含めるし:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.lesson.is_show_to_search == true ? 'サジェスト検索に含める' : ''}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">講師プロフィールに表示:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.lesson.is_show_to_teacher_detail == true ? '表示する' : ''}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">説明:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.lesson.lesson_description}}

                                        </div>
                                    </div>




                                </div>

                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">レッスン一覧
                                    <div class="float-right">
                                        <a href="javascript:void(0);" class="btn btn-primary " v-on:click="show">
                                            追加
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="lessonText in this.lesson.lesson_text">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text">{{lessonText.lesson_text_name}}</div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                            :delete-action="getUriDelete(lesson.lesson_id , lessonText.lesson_text_id)"
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
                <modal-table :detailUrl="detailLessonUrl" :url="listTextLessonUrl"  :pageSizeLimit="pageSizeLimit" :id="lesson.id" :register-url="registerUrl">

                </modal-table>
            </div>
        </main>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import DeleteItem from "./../../components/common/delete-item";
    import ModalTable from "../common/modal-table";


    export default {
        created: function () {
        },
        components: {
            Loader,
            DeleteItem,
            ModalTable
        },
        data() {
            return {
                messageConfirm : 'このテキストをレッスンに解除しますか？',
                csrfToken: Laravel.csrfToken,
            };
        },
        props: ["listTextLessonUrl", "createUrl", 'lesson', 'editLessonUrl', 'detailLessonUrl', 'pageSizeLimit', 'registerUrl'],
        mounted() {},
        methods: {
            getUriDelete(id, textId) {
                return  id + '/text/' + textId + '/delete';

            },
            show () {
                this.$modal.show('select-teacher-lesson-modal');
            },
            hide () {
                this.$modal.hide('select-teacher-lesson-modal');
            },
        },
    }
</script>
