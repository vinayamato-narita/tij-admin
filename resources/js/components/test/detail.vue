<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid info-screen">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            テスト情報表示


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">テスト情報

                                    <div class="float-right">
                                        <a :href="this.editTestUrl" class="btn btn-primary ">編集</a>
                                    </div>

                                </div>
                                <div class="card-body">　


                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">テストID:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.test.test_id}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">テスト名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.test.test_name}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">説明:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.test.test_description}}

                                        </div>
                                    </div>

                                    <div class="form-group row " v-if="test.test_type === 2">
                                        <label class="col-md-4 col-form-label text-md-right">制限時間:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.test.execution_time}}

                                        </div>
                                    </div>

                                    <div class="form-group row " v-if="test.test_type === 2">
                                        <label class="col-md-4 col-form-label text-md-right">受講回数:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.test.expire_count}}

                                        </div>
                                    </div>

                                    <div class="form-group row " >
                                        <label class="col-md-4 col-form-label text-md-right">合格点:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.test.passing_point}}

                                        </div>
                                    </div>

                                    <div class="form-group row " >
                                        <label class="col-md-4 col-form-label text-md-right">合計点:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.test.total_point}}

                                        </div>
                                    </div>



                                </div>

                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                            <div class="card-header">
                                設問一覧

                                <div class="float-right">
                                    <a :href="this.addQuestionUrl" class="btn btn-primary ">問題追加</a>
                                    <a :href="this.editTestUrl" class="btn btn-primary ">点数・表示順編集
                                    </a>
                                </div>


                            </div>
                            <div class="card-body">

                                <ol style="margin-left: -30px;list-style-type: none;">
                                    <li v-for="test_question in this.test.test_questions">
                                        <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                            <div class="col-md-10 wrap-long-text"> <a :href="'/lesson/' + lesson.lesson_id" target="_blank" class="wrap-long-text" style="color: inherit">{{test_question.lesson_name}}</a></div>
                                            <div class="col-md-2">
                                                <DeleteItem
                                                        :delete-action="getUriDelete(course.course_id , lesson.lesson_id)"
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
            </div>
        </main>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";


    export default {
        created: function () {
        },
        components: {
            Loader,
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
            };
        },
        props: ['test', 'editTestUrl', 'addQuestionUrl'],
        mounted() {},
        methods: {
        },
    }
</script>
