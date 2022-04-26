<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid info-screen">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            グループコース申込情報表示

                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">グループコース申込情報
                                    <div class="float-right">
                                        <a :href="listGroupLessonStudentUrl" class="btn btn-primary ">学習者一覧</a>
                                    </div>

                                </div>
                                <div class="card-body">


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">コース名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ this.course.course_name}}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">法人/個人:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ this.course.is_for_lms == 0 ? '個人' : '法人' }}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">最小開催人数:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ this.course.min_reserve_count }}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">最大申込人数:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ this.course.max_reserve_count }}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">申込人数:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ this.course.point_subscription_histories_count }}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">開催決定日時:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.course.decide_date | formatDateCourse}}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">申込期限:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.course.reserve_end_date | formatDateCourse}}
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">ステータス:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ this.course.group_lesson }}
                                        </div>
                                    </div>

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

    export default {
        created: function () {
        },
        components: {},
        data() {
            return {
                showModal: false,
                csrfToken: Laravel.csrfToken,
            };
        },
        props: ["listGroupLessonReserveUrl", 'course', 'listGroupLessonStudentUrl'],
        mounted() {

        },
        methods: {},
    }
</script>
