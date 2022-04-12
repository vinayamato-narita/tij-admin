<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            セットコース情報表示




                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">セットコース情報


                                    <div class="float-right">
                                        <a :href="this.editCourseUrl" class="btn btn-primary ">編集</a>
                                    </div>

                                </div>
                                <div class="card-body">


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">セットコースID:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.course.course_id}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">学習者に公開する:
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
                                        <label class="col-md-3 col-form-label text-md-right">セット短縮名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.course_name}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">セット短縮名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.course.course_name_short}}

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
                                <div class="card-header">コース一覧

                                    <div class="float-right">
                                        <div style="min-height: 38px">
                                            <div class="float-right">

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="child in this.course.child_course">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-1 wrap-long-text">{{child.course_id}}</div>
                                                <div class="col-md-9 wrap-long-text">{{child.course_name}}</div>
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
        </main>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import ModalTable from "../common/modal-table";
    import DeleteItem from "./../../components/common/delete-item";



    export default {
        created: function () {
        },
        components: {
            Loader,
            ModalTable,
            DeleteItem
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,

            };
        },
        props: ["listCourseUrl", 'course', 'editCourseUrl',],
        mounted() { console.log(this.lessonText)},
        methods: {
        },
    }
</script>
