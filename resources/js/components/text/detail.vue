<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            テキスト情報表示


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">テキスト情報

                                    <div class="float-right">
                                        <a :href="this.editTextUrl" class="btn btn-primary ">編集</a>
                                    </div>

                                </div>
                                <div class="card-body">


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">表示順:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.lessonText.lesson_text_no}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">テキストURL（生徒用):
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            <a :href="this.lessonText.lesson_text_url" class="text-dark">
                                                {{this.lessonText.lesson_text_url}}

                                            </a>

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">テキストURL（先生用):
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            <a :href="this.lessonText.lesson_text_url_for_teacher" class="text-dark">
                                                {{this.lessonText.lesson_text_url_for_teacher}}

                                            </a>

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">テキスト名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.lessonText.lesson_text_name}}


                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right">説明:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            {{this.lessonText.lesson_text_description}}


                                        </div>
                                    </div>


                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label text-md-right text-dark" >MP3 URL:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">
                                            <a :href="this.lessonText.lesson_text_sound_url" class="text-dark">
                                                {{this.lessonText.lesson_text_sound_url}}

                                            </a>

                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">レッスン一覧
                                    <div class="float-right">
                                        <a href="#" class="btn btn-primary ">
                                            追加
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ol style="margin-left: -30px;list-style-type: none;">
                                        <li v-for="lesson in this.lessonText.lesson">
                                            <div class="row" style="margin: 5px 0px; padding: 5px 10px; border-bottom: 1px ridge;">
                                                <div class="col-md-10 wrap-long-text">{{lesson.lesson_name}}</div>
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
        props: ["listTextUrl", "createUrl", 'lessonText', 'editTextUrl'],
        mounted() { console.log(this.lessonText)},
        methods: {
        },
    }
</script>
