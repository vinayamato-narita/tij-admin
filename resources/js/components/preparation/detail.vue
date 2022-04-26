<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid info-screen">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            予習情報表示


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">予習情報</h5>

                                    <div class="float-right">
                                        <a :href="this.editPreparationUrl" class="btn btn-primary ">編集</a>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">予習動画:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.preparation.file == null ? '' : this.preparation.file.file_name_original}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">予習名:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2">

                                            {{this.preparation.preparation_name}}

                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <label class="col-md-4 col-form-label text-md-right">説明:
                                        </label>
                                        <div class="col-md-6 text-md-left p-2 downline">

                                         <p> {{this.preparation.preparation_description}}</p>

                                        </div>
                                    </div>



                                </div>

                            </div>
                        </div>
                        <div class="col-md-5">
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
                type : 'lesson',
                csrfToken: Laravel.csrfToken,
            };
        },
        props: ['preparation', 'editPreparationUrl'],
        mounted() {},
        methods: {
            show () {
                this.$modal.show('select-teacher-lesson-modal');
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
