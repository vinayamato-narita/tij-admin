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
                                <div class="card-header">
                                    <h5 class="title-page">レッスン情報</h5>
                                    <div class="float-right">
                                        <a :href="this.editLessonUrl" class="btn btn-primary ">編集</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">レッスンID:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.lesson.lesson_id}}
                                        </div>
                                    </div> -->

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">レッスン名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.lesson.lesson_name}}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">レッスンコード:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.lesson.lesson_code}}
                                        </div>
                                    </div>

                                    <!-- <div class="form-group row ">
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
                                    </div> -->

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">説明:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2 downline">
                                           <p> {{this.lesson.lesson_description}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">英語版</h5>
                                    <a :href="editLangEnUrl" class="btn btn-primary">編集</a>
                                </div>

                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                        >レッスン名:</label
                                        >
                                        <div class="col-md-3 pd-7" >
                                            {{lessonENName}}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                        >レッスン概要:</label
                                        >
                                        <div class="col-md-3 pd-7 downline" >
                                           <p> {{ lessonENDes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">中国語版</h5>
                                    <a :href="editLangZhUrl" class="btn btn-primary">編集</a>
                                </div>

                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                        >レッスン名:</label
                                        >
                                        <div class="col-md-3 pd-7" >
                                            {{lessonZHName}}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-md-3 col-form-label text-md-right"
                                        >レッスン概要:</label
                                        >
                                        <div class="col-md-3 pd-7 downline" >
                                            <p>{{ lessonZHDes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">予習</h5>
                                    <div class="float-right">
                                        <a href="javascript:void(0);" :class="['btn', 'btn-primary', this.lesson.preparations.length === 0 ? '' : 'disabled']" v-on:click="show('add-preparation-modal')" >
                                            追加
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="preparation in this.lesson.preparations">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text"><a :href="'/preparation/' + preparation.preparation_id" target="_blank" class="wrap-long-text" style="color: inherit">{{preparation.preparation_name}}</a></div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                        :delete-action="getUriPreparationDelete(lesson.lesson_id , preparation.preparation_id)"
                                                        :message-confirm="messageConfirmPreparation"
                                                    >
                                                    </DeleteItem>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">復習</h5>
                                    <div class="float-right">
                                        <a href="javascript:void(0);" :class="['btn', 'btn-primary', this.lesson.reviews.length === 0 ? '' : 'disabled']" v-on:click="show('add-review-modal')" >
                                            追加
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="review in this.lesson.reviews">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text"><a :href="'/review/' + review.review_id" target="_blank" class="wrap-long-text" style="color: inherit">{{review.review_name}}</a></div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                        :delete-action="getUriReviewDelete(lesson.lesson_id , review.review_id)"
                                                        :message-confirm="messageConfirmReview"
                                                    >
                                                    </DeleteItem>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">確認テスト</h5>
                                    <div class="float-right">
                                        <a href="javascript:void(0);" :class="['btn', 'btn-primary', this.lesson.confirm_test.length === 0 ? '' : 'disabled']" v-on:click="show('add-confirm-test-modal')" >
                                            追加
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="confirmTest in this.lesson.confirm_test">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text"><a :href="'/test/' + confirmTest.test_id" target="_blank" class="wrap-long-text" style="color: inherit">{{confirmTest.test_name}}</a></div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                        :delete-action="getUriConfirmTestDelete(lesson.lesson_id , confirmTest.test_id)"
                                                        :message-confirm="messageConfirmTest"
                                                    >
                                                    </DeleteItem>
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">テキスト一覧</h5>
                                    <div class="float-right">
                                        <a href="javascript:void(0);" :class="['btn', 'btn-primary', this.lesson.lesson_text.length === 0 ? '' : 'disabled'] " v-on:click="show('add-text-modal')">
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
                <add-text :detailUrl="detailLessonUrl" :url="listTextLessonUrl"  :pageSizeLimit="pageSizeLimit" :id="lesson.id" :register-url="registerUrl" :type="type">

                </add-text>
                <add-preparation :detailUrl="detailLessonUrl" :pageSizeLimit="pageSizeLimit" :id="lesson.lesson_id" :url="listPreparationUrl" :register-url="registerPreparationUrl">

                </add-preparation>

                <add-review :detailUrl="detailLessonUrl" :pageSizeLimit="pageSizeLimit" :id="lesson.lesson_id" :url="listReviewUrl" :register-url="registerReviewUrl">

                </add-review>
                <add-confirm-test :detailUrl="detailLessonUrl" :pageSizeLimit="pageSizeLimit" :id="lesson.lesson_id" :url="listConfirmTestUrl" :register-url="registerConfirmTestUrl">

                </add-confirm-test>
            </div>
        </main>
    </div>
</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import DeleteItem from "./../../components/common/delete-item";
    import ModalTable from "../common/modal-table";
    import  AddPreparation from "../lesson/add-preparation.vue"
    import  AddReview from "../lesson/add-review.vue"
    import  AddConfirmTest from "./add-confirm-test.vue"
    import  AddText from "./add-text"

    export default {
        created: function () {
            if (this.lesson.lesson_infos != null) {
                var en = this.lesson.lesson_infos.filter(function (e) {
                    return e.lang_type === 'en'
                })[0];
                var zh = this.lesson.lesson_infos.filter(function (e) {
                    return e.lang_type === 'zh'
                })[0];
                this.lessonENName = typeof (en) !== 'undefined' ? en.lesson_name : '';
                this.lessonENDes = typeof (en) !== 'undefined' ? en.lesson_description : '';
                this.lessonZHName = typeof (zh) !== 'undefined' ? zh.lesson_name : '';
                this.lessonZHDes = typeof (zh) !== 'undefined' ? zh.lesson_description : '';
            }
        },
        components: {
            Loader,
            DeleteItem,
            ModalTable,
            AddPreparation,
            AddReview,
            AddConfirmTest,
            AddText
        },
        data() {
            return {
                messageConfirm : 'このテキストをレッスンに解除しますか？',
                messageConfirmPreparation : 'この予習をレッスンに解除しますか？',
                messageConfirmReview : 'この復習をレッスンに解除しますか？',
                messageConfirmTest : 'このテストをレッスンに解除しますか？',
                type : 'lesson',
                csrfToken: Laravel.csrfToken,
                lessonENName : '',
                lessonENDes : '',
                lessonZHName : '',
                lessonZHDes : ''
            };
        },
        props: ["listTextLessonUrl", "createUrl", 'lesson', 'editLessonUrl', 'detailLessonUrl', 'pageSizeLimit', 'registerUrl', 'listPreparationUrl', 'registerPreparationUrl',
            'listReviewUrl', 'registerReviewUrl', 'editLangEnUrl', 'editLangZhUrl', 'listConfirmTestUrl', 'registerConfirmTestUrl'],
        mounted() {},
        methods: {
            getUriDelete(id, textId) {
                return  id + '/text/' + textId + '/delete';

            },
            getUriPreparationDelete(id, preparationId) {
                return  id + '/preparation/' + preparationId + '/delete';
            },
            getUriReviewDelete(id, reviewId) {
                return  id + '/review/' + reviewId + '/delete';
            },
            getUriConfirmTestDelete(id, testId) {
                return  id + '/confirmTest/' + testId + '/delete';
            },
            show ($modalName) {
                this.$modal.show($modalName);
            },
            hide () {
                this.$modal.hide('select-teacher-lesson-modal');
            },
        },
    }
</script>
<style scoped>
.downline{
white-space: pre-line;
}
</style>
