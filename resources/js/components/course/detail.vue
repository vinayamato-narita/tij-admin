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
                                <div class="card-header">
                                    <h5 class="title-page">コース情報</h5>
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
                                        <label class="col-md-3 col-form-label text-md-right">公開日時:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.course.publish_date_from | formatDateTime}} ～ {{this.course.publish_date_to | formatDateTime}}

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
                                        <label class="col-md-3 col-form-label text-md-right">受講回数 :
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.point_count}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">価格（税抜）:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.amount | formatMoney }}


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
                                        <label class="col-md-3 col-form-label text-md-right">コース概要:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            <nl2br v-if="this.course.course_description" tag="p" :text="this.course.course_description" class-name="mb-0">

                                            </nl2br>
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">法人・個人 :
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.is_for_lms ?  '法人' : '個人'}}

                                        </div>
                                    </div>


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">コースタイプ :
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            <span v-if="this.course.course_type === 0">
                                                プライベートコース

                                            </span>

                                            <span v-if="this.course.course_type === 1">
                                                グループコース

                                            </span>

                                            <span v-if="this.course.course_type === 2">
                                                実力テスト用コース

                                            </span>


                                        </div>
                                    </div>

                                    <div class="form-group row " v-if="this.course.course_type === 0 || this.course.course_type === 2">
                                        <label class="col-md-3 col-form-label text-md-right">有効日数 :
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.expire_day}}

                                        </div>
                                    </div>


                                    <div class="form-group row " v-if="this.course.course_type === 1">
                                        <label class="col-md-3 col-form-label text-md-right">最小開催人数 :
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.min_reserve_count}}

                                        </div>
                                    </div>

                                    <div class="form-group row " v-if="this.course.course_type === 1">
                                        <label class="col-md-3 col-form-label text-md-right">最大申込人数  :
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.max_reserve_count}}

                                        </div>
                                    </div>

                                    <div class="form-group row " v-if="this.course.course_type === 1">
                                        <label class="col-md-3 col-form-label text-md-right">開催決定日時 :
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.decide_date | formatDateTime}}

                                        </div>
                                    </div>

                                    <div class="form-group row " v-if="this.course.course_type === 1">
                                        <label class="col-md-3 col-form-label text-md-right">申込期限 :
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.reserve_end_date | formatDateTime}}

                                        </div>
                                    </div>

                                    <div class="form-group row " v-if="this.course.course_type === 1">
                                        <label class="col-md-3 col-form-label text-md-right">開講日時 :
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.course_start_date | formatDateTime}}

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
                                        >コース名:</label
                                        >
                                        <div class="col-md-3 pd-7" >
                                            {{courseENName}}
                                        </div>


                                    </div>

                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                        >コース概要:</label
                                        >
                                        <div class="col-md-3 pd-7" >
                                            <nl2br v-if="courseENDes" tag="p" :text="courseENDes" class-name="mb-0">

                                            </nl2br>
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
                                        >コース名:</label
                                        >
                                        <div class="col-md-3 pd-7" >
                                            {{courseZHName}}
                                        </div>


                                    </div>

                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                        >コース概要:</label
                                        >
                                        <div class="col-md-3 pd-7" >
                                            <nl2br v-if="courseZHDes" tag="p" :text="courseZHDes" class-name="mb-0">

                                            </nl2br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card" v-if="this.course.course_type === 0 || this.course.course_type === 1">
                                <div class="card-header">
                                    <h5 class="title-page">レッスン一覧</h5>
                                    <div class="float-right">
                                        <div style="min-height: 38px;">
                                            <div class="float-right" >
                                                <a href="javascript:void(0);" class="btn btn-primary " v-on:click="show('select-teacher-lesson-modal')">
                                                    追加
                                                </a>
                                            </div>
                                            <div class="float-right" style="margin-right: 5px">
                                                <a href="javascript:void(0);" class="btn btn-primary " v-on:click="show('drag-teacher-lesson-modal')">
                                                    編集
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="lesson in this.course.lesson">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text"> <a :href="'/lesson/' + lesson.lesson_id" target="_blank" class="wrap-long-text" style="color: inherit">{{lesson.lesson_name}}</a></div>
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
                            <div class="card" v-if="this.course.course_type === 2">
                                <div class="card-header">
                                    <h5 class="title-page">実力テスト</h5>
                                    <div class="float-right">
                                        <div style="min-height: 38px">
                                            <div class="float-right">
                                                <a href="javascript:void(0);" class="btn btn-primary " v-on:click="show('ability-test-modal')">
                                                    追加
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="test in this.course.test_abilities">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text"> <a :href="'/test/' + test.test_id" target="_blank" class="wrap-long-text" style="color: inherit">{{test.test_name}}</a></div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                            :delete-action="getUriTestDelete(course.course_id , test.test_id)"
                                                            :message-confirm="messageConfirmTest"
                                                    >
                                                    </DeleteItem>
                                                </div>


                                            </div>
                                        </li>
                                    </ol>

                                </div>

                            </div>
                            <div class="card" v-if="this.course.course_type === 1">
                                <div class="card-header">
                                    <h5 class="title-page">コース修了テスト</h5>
                                    <div class="float-right">
                                        <div style="min-height: 38px">
                                            <div class="float-right">
                                                <a href="javascript:void(0);" class="btn btn-primary " v-on:click="show('course-end-test-modal')">
                                                    追加
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="test in this.course.test_course_ends">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text"> <a :href="'/test/' + test.test_id" target="_blank" class="wrap-long-text" style="color: inherit">{{test.test_name}}</a></div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                            :delete-action="getUriTestDelete(course.course_id , test.test_id)"
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
                                    <h5 class="title-page">キャンペーン</h5>
                                    <div class="float-right">
                                        <div style="min-height: 38px">
                                            <div class="float-right">
                                                <a :href="addCampaignUrl" class="btn btn-primary ">
                                                    追加
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="campaign in this.course.campaigns">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text"> {{ campaign.campaign_start_time | formatDateTime}} - {{ campaign.campaign_end_time | formatDateTime}}</div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                            :delete-action="getUriCampaignDelete(course.course_id , campaign.course_campaign_id)"
                                                            :message-confirm="messageConfirmCampaign"
                                                    >
                                                    </DeleteItem>
                                                </div>


                                            </div>
                                        </li>
                                    </ol>

                                </div>

                            </div>
                            <div class="card" v-if="this.course.course_type === 1">
                                <div class="card-header">
                                    <h5 class="title-page">引き継ぎメモ</h5>
                                    <div class="float-right">
                                        <div style="min-height: 38px">
                                            <div class="float-right">
                                                <a href="javascript:void(0);" class="btn btn-primary " v-on:click="show('course-group-memo')">
                                                    追加
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <pre style="font-size: 11px;z-index: ">{{ courseGroupMemo.memo }}</pre>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <course-add-lesson  :detailUrl="detailCourseUrl" :url="listLessonUrl"  :pageSizeLimit="pageSizeLimit" :id="course.course_id" :register-url="registerUrl">

                </course-add-lesson>
                <dragable-item :detailUrl="detailCourseUrl" :listLessonAttachUrl="listLessonAttachUrl"   :id="course.course_id" :register-url="registerUrl" :listLessonAttachUpdateUrl="listLessonAttachUpdateUrl">

                </dragable-item>
                <ability-test :detailUrl="detailCourseUrl" :pageSizeLimit="pageSizeLimit" :id="course.course_id" :url="listTestAbilityUrl" :register-url="registerAbilityTestUrl">

                </ability-test>
                <course-end-test :detailUrl="detailCourseUrl" :pageSizeLimit="pageSizeLimit" :id="course.course_id" :url="listTestCourseEndUrl" :register-url="registerCourseEndTestUrl">

                </course-end-test>
                <insert-video :id="this.course.course_id" :register-url="registerVideoUrl">

                </insert-video>
                <course-group-memo  
                    :url-update-group-memo="urlUpdateGroupMemo" 
                    :detailUrl="detailCourseUrl"
                    :course-group-memo="courseGroupMemo">
                </course-group-memo>
            </div>
        </main>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import CourseAddLesson from "../course/course-add-lesson";
    import InsertVideo from "../course/insert-video";
    import DragableItem from "../course/dragable-item";
    import DeleteItem from "./../../components/common/delete-item";
    import AbilityTest from "./../../components/course/ability-test";
    import CourseEndTest from "./../../components/course/course-end-test";
    import CourseGroupMemo from "./../../components/course/course-group-memo";

    export default {
        created: function () {
            if (this.course.course_infos != null) {
                var en = this.course.course_infos.filter(function (e) {
                    return e.lang_type === 'en'
                })[0];
                var zh = this.course.course_infos.filter(function (e) {
                    return e.lang_type === 'zh'
                })[0];
                this.courseENName = typeof (en) !== 'undefined' ? en.course_name : '';
                this.courseENDes = typeof (en) !== 'undefined' ? en.course_description : '';
                this.courseZHName = typeof (zh) !== 'undefined' ? zh.course_name : '';
                this.courseZHDes = typeof (zh) !== 'undefined' ? zh.course_description : '';
            }
        },
        components: {
            Loader,
            CourseAddLesson,
            DeleteItem,
            InsertVideo,
            DragableItem,
            AbilityTest,
            CourseEndTest,
            CourseGroupMemo
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
                messageConfirm : 'このレッスンをコースに解除しますか？',
                messageConfirmTest : 'このテストをコースに解除しますか？',
                messageConfirmVideo: 'この動画を削除しますか？',
                messageConfirmCampaign : 'このキャンペーンを削除しますか？',
                courseENName : '',
                courseENDes : '',
                courseZHName : '',
                courseZHDes : ''
            };
        },
        props: ["listCourseUrl", "createUrl", 'course', 'editCourseUrl', 'detailCourseUrl', 'pageSizeLimit', 'registerUrl', 'listLessonUrl',
            'registerVideoUrl', 'courseVideo', 'editLangEnUrl', 'editLangZhUrl', 'listLessonAttachUrl', 'listLessonAttachUpdateUrl', 'listTestAbilityUrl', 'registerAbilityTestUrl', 'listTestCourseEndUrl', 'registerCourseEndTestUrl',
            'addCampaignUrl', 'courseGroupMemo', 'urlUpdateGroupMemo'
        ],
        mounted() {},
        methods: {
            getUriDelete(id, lessonId) {
                return '/course/'+ id + '/lesson/' + lessonId + '/delete';

            },
            getUriTestDelete(id, testId) {
                return '/course/'+ id + '/test/' + testId + '/delete';

            },
            getUriCampaignDelete(courseId, campaignId) {
                return '/course/' + courseId +'/campaign/destroy/'+ campaignId

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
