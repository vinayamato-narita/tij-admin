<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            受験済実力テスト情報表示


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">受験済実力テスト情報


                                </div>
                                <div class="card-body">


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">学習者ID:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ testResult.student_id }}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">実力テスト名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ testResult.test.test_name }}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">点数:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{ testResult.result_total_score === null ? 0 : testResult.result_total_score}} / {{ testResult.total_score}}

                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="card">
                                <div class="card-header">評価
                                    <div class="float-right">
                                        <a v-if="disableComment" disabled="" :href="abilityTestResultEditUrl" class="btn btn-primary  disabled ">評価開始</a>

                                        <a v-else :href="abilityTestResultEditUrl" class="btn btn-primary   ">評価開始</a>
                                    </div>


                                </div>
                                <div class="card-body">


                                    <div class="form-group row " v-for="comment in comments">
                                        <label class="col-md-3 col-form-label text-md-right">{{comment.title}}:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            <nl2br :text="comment.comment_desc" tag="p" v-if="comment.comment_desc">

                                            </nl2br>

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
        props: ['testResult', 'comments', 'abilityTestResultEditUrl', 'disableComment'],
        mounted() {},
        methods: {
        },
    }
</script>
