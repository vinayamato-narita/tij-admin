<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            コース情報表示



                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">コース情報

                                    <div class="float-right">
                                        <a :href="this.editCourseUrl" class="btn btn-primary ">編集</a>
                                    </div>

                                </div>
                                <div class="card-body">


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">コースID:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.course.course_id}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">生徒に公開する:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.course.is_show ? '公開' : '非公開'}}

                                        </div>
                                    </div>


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">タグ:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            <div v-for="tag in this.course.tags">
                                                {{tag.tag_name}}

                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">表示順:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.display_order}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">コース名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.course_name}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">短縮名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.course_name_short}}

                                        </div>
                                    </div>



                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">付与チケット数:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.point_count}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">チケット有効日数:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.point_expire_day}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">レッスン予約可能最大数:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.max_reserve_count}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">価格（税抜）:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.amount}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">商品コード:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.paypal_item_number}}

                                        </div>
                                    </div>



                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">キャンペーンコース:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.is_campain ? 'キャンペーンコースに登録する' : ''}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">キャンペーンコード:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.campaign_code }}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">コース概要:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.course_description }}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">レッスン時間制限:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.is_schedule_limit ?  '制限あり' : '制限なし'}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">レッスン予約制限:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.reserve_start}}分前～{{this.course.reserve_end}}分前

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">キャンセル時間制限:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.cancel_end}}分前まで

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">LMS表示用:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.is_for_lms ? 'LMS表示用' : ''}}

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">レッスン一覧
                                    <div class="float-right">
                                        <div style="min-height: 38px">
                                            <div class="float-right">
                                                <a href="javascript:void(0);" class="btn btn-primary " v-on:click="show('select-teacher-lesson-modal')">
                                                    追加
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="lesson in this.course.lesson">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text">{{lesson.lesson_name}}</div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                            :delete-action="getUriDelete(course.course_id , lesson.id)"
                                                            :message-confirm="messageConfirm"
                                                    >
                                                    </DeleteItem>
                                                </div>


                                            </div>
                                        </li>
                                    </ol>

                                </div>

                            </div>
                            <div class="card">
                                <div class="card-header">動画一覧
                                    <div class="float-right">
                                        <div style="min-height: 38px">
                                            <div class="float-right">
                                                <a href="javascript:void(0);" class="btn btn-primary " v-on:click="show('insert-video-modal')">
                                                    追加
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="video in this.courseVideo">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text">{{video.video_name}}</div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                            :delete-action="getUriDeleteVideo(course.course_id , video.id)"
                                                            :message-confirm="messageConfirmVideo"
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
                <modal-table  :detailUrl="detailCourseUrl" :url="listLessonUrl"  :pageSizeLimit="pageSizeLimit" :id="course.course_id" :register-url="registerUrl" :type="type">

                </modal-table>
                <insert-video :id="this.course.course_id" :register-url="registerVideoUrl">

                </insert-video>
            </div>
        </main>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import ModalTable from "../common/modal-table";
    import InsertVideo from "../course/insert-video";
    import DeleteItem from "./../../components/common/delete-item";



    export default {
        created: function () {
        },
        components: {
            Loader,
            ModalTable,
            DeleteItem,
            InsertVideo
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
                type : 'course',
                messageConfirm : 'このレッスンをコースに解除しますか？',
                messageConfirmVideo: 'この動画を削除しますか？'

            };
        },
        props: ["listCourseUrl", "createUrl", 'course', 'editCourseUrl', 'detailCourseUrl', 'pageSizeLimit', 'registerUrl', 'listLessonUrl', 'registerVideoUrl', 'courseVideo'],
        mounted() {},
        methods: {
            getUriDelete(id, lessonId) {
                return '/course/'+ id + '/lesson/' + lessonId + '/delete';

            },
            getUriDeleteVideo(id, videoId) {
                return '/course/'+ id + '/video/' + videoId + '/delete';

            },
            show (modalName) {
                this.$modal.show(modalName);
            },
            hide () {
                this.$modal.hide('select-teacher-lesson-modal');
            },
        },
    }
</script>
