<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid info-screen">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            コースカテゴリ情報表示



                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">コースカテゴリ情報

                                    <div class="float-right">
                                        <a :href="this.editCategoryUrl" class="btn btn-primary ">編集</a>
                                    </div>

                                </div>
                                <div class="card-body">


                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">カテゴリID:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.category.category_id}}

                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">表示順:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.category.order_num}}

                                        </div>
                                    </div>


                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">カテゴリ名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.category.category_name}}

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
                                                for="text-input"
                                        >カテゴリ名:</label
                                        >
                                        <div class="col-md-3 pd-7" >
                                            {{categoryENName}}
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
                                                for="text-input"
                                        >カテゴリ名:</label
                                        >
                                        <div class="col-md-3 pd-7" >
                                            {{categoryZHName}}
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">コース一覧

                                    <div class="float-right">
                                        <a href="javascript:void(0);" class="btn btn-primary " v-on:click="show">
                                            追加
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="course in this.category.courses">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text">{{course.course_name}}</div>
                                                <div class="col-md-2">
                                                    <DeleteItem
                                                            :delete-action="getUriDelete(category.category_id , course.course_id)"
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
                <category-modal-table :detailUrl="detailCategoryUrl" :url="listCourseUrl"  :pageSizeLimit="pageSizeLimit" :id="category.id" :register-url="registerUrl" :type="type">

                </category-modal-table>
            </div>
        </main>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import DeleteItem from "./../../components/common/delete-item";
    import CategoryModalTable from "../category/category-modal-table";


    export default {
        created: function () {
            if (this.category.category_infos != null) {
                var en = this.category.category_infos.filter(function (e) {
                    return e.lang_type === 'en'
                })[0];
                var zh = this.category.category_infos.filter(function (e) {
                    return e.lang_type === 'zh'
                })[0];
                this.categoryENName = typeof (en) !== 'undefined' ? en.category_name : '';
                this.categoryZHName = typeof (zh) !== 'undefined' ? zh.category_name : '';
            }
        },
        components: {
            Loader,
            DeleteItem,
            CategoryModalTable
        },
        data() {
            return {
                messageConfirm : 'このコースをコースカテゴリに解除しますか？',
                type : 'category',
                csrfToken: Laravel.csrfToken,
                categoryENName : '',
                categoryZHName : ''
            };
        },
        props: ['category', 'editCategoryUrl', 'detailCategoryUrl', 'pageSizeLimit', 'registerUrl', 'listCourseUrl', 'editLangEnUrl', 'editLangZhUrl'],
        mounted() {},
        methods: {
            getUriDelete(id, courseId) {
                return  id + '/course/' + courseId + '/delete';

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
