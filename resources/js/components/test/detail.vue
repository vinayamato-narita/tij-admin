<template xmlns="http://www.w3.org/1999/html">
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
                                <div class="card-header">
                                    <h5 class="title-page">テスト情報</h5>
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
                                        <label class="col-md-4 col-form-label text-md-right">テスト種別:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.test.type_name}}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">説明:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.test.test_description}}
                                        </div>
                                    </div>

                                    <div class="form-group row " v-if="test.test_type === 2 || test.test_type === 1">
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

                                    <div class="form-group row " v-if="test.test_type === 1 || test.test_type === 2">
                                        <label class="col-md-4 col-form-label text-md-right">合格点:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.test.passing_score}}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">合計点:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.test.total_score}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">設問一覧</h5>
                                    <div class="float-right">
                                        <a :href="this.addQuestionUrl" class="btn btn-primary ">問題追加</a>
                                        <button  class="btn btn-primary " v-on:click="show('drag-question-modal')">点数・表示順編集
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="test_question in this.test.test_questions">
                                            <div class="row"
                                                 style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-4 wrap-long-text"><span class="wrap-long-text"
                                                                                            style="color: inherit"> {{test_question.navigation}}</span>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="wrap-long-text"
                                                          style="color: inherit">小計{{test_question.total_score}}点</span>
                                                </div>

                                                <div class="col-md-2">
                                                    <a :href="getEditQuestionUrl(test.test_id, test_question.test_question_id)" class="btn btn-primary text-nowrap">問題編集
                                                    </a>
                                                </div>
                                                <div class="col-md-1">

                                                </div>

                                                <div class="col-md-2 ">
                                                    <button v-if="!isHasTestResult" v-on:click="showAlert(getDeleteQuestionUrl(test.test_id, test_question.test_question_id))" class="btn btn-danger text-nowrap">削除
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;" v-for="test_sub_question in test_question.test_sub_questions">
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-5 wrap-long-text">
                                                    <span class="wrap-long-text"
                                                        style="color: inherit">設問{{ test_sub_question.display_order++ }}　{{test_sub_question.sub_question_content | truncate(30, '...')}}</span>
                                                </div>
                                                <div class="col-md-5">
                                                    <span class="wrap-long-text"
                                                          style="color: inherit">{{test_sub_question.score}} 点
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>

                            <div class="card" v-if="test.test_type === 1 || test.test_type === 2">
                                <div class="card-header">
                                    <h5 class="title-page">コース一覧</h5>
                                    <div class="float-right">
                                        <div style="min-height: 38px">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="course in this.test.courses">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text"> <a :href="'/course/' + course.course_id" target="_blank" class="wrap-long-text" style="color: inherit">{{course.course_name}}</a></div>

                                                <div class="col-md-2">
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class="card" v-if="test.test_type === 0">
                                <div class="card-header">
                                    <h5 class="title-page">レッスン一覧</h5>
                                    <div class="float-right">
                                        <div style="min-height: 38px">

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="lesson in this.test.lessons">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text"> <a :href="'/lesson/' + lesson.lesson_id" target="_blank" class="wrap-long-text" style="color: inherit">{{lesson.lesson_name}}</a></div>

                                                <div class="col-md-2">
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
            <dragable-item :detailUrl="detailTestUrl" :listQuestionAttachUrl="listQuestionAttachUrl"   :id="test.testId"  :listQuestionAttachUpdateUrl="listQuestionAttachUpdateUrl">
            </dragable-item>
        </main>
    </div>
</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import DragableItem from "./dragable-item";

    export default {
        created: function () {
        },
        components: {
            Loader,
            DragableItem
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
            };
        },
        props: ['test', 'editTestUrl', 'addQuestionUrl', 'detailTestUrl', 'listQuestionAttachUrl', 'listQuestionAttachUpdateUrl', 'isHasTestResult'],
        mounted() {
        },
        methods: {
            getEditQuestionUrl(id, testQuestionId){
                return '/test/' + id +'/edit_question/' + testQuestionId;
            },
            getDeleteQuestionUrl(id, testQuestionId){
                return '/test/' + id +'/delete_question/' + testQuestionId;
            },
            showAlert(deleteAction) {
                let that = this;
                this.$swal({
                    title: 'この大問を削除しますか？',
                    icon: "warning",
                    confirmButtonText: "削除する",
                    cancelButtonText: "閉じる",
                    showCancelButton: true
                }).then(result => {
                    if (result.value) {
                        that.flagShowLoader = true;
                        $('.loading-div').removeClass('hidden');
                        axios
                            .delete(deleteAction, {
                                _token: Laravel.csrfToken
                            })
                            .then(function (response) {
                                that.flagShowLoader = false;
                                that
                                    .$swal({
                                        title: response.data.message,
                                        icon: "success",
                                        confirmButtonText: "閉じる"
                                    })
                                    .then(function (confirmed) {
                                        if (confirmed.isConfirmed)
                                            window.location.reload();

                                    });
                            })
                            .catch(error => {
                                that.flagShowLoader = false;
                            });
                    }
                });
            },
            show(modalName){
                this.$modal.show(modalName);
            }


        },
    }
</script>
