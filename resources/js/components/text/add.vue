<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            テキスト新規作成
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">テキスト情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="lessonTextUrl">テキストファイル（学習者用） :

                                            </label>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-primary w-100 mr-2"
                                                        v-on:click="show('add-files-modal')">ファイル選択
                                                </button>
                                                <span class="text-nowrap">
                                                    {{studentFileNameAttached}}
                                                </span>
                                                <button type="button" v-on:click="newFile"
                                                        class="btn btn-primary  mr-2">新規ファイル追加
                                                </button>
                                                <input type="file" name="studentNewFile" id="studentNewFile" ref="studentNewFile"
                                                       v-on:change="changeFile" class="hidden">
                                                <span class="text-nowrap">
                                                    {{studentFileName}}

                                                </span>


                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right"
                                                   for="lessonTextUrlForTeacher">テキストファイル（講師用）:

                                            </label>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-primary w-100 mr-2"
                                                        v-on:click="showTeacher('add-files-modal')">ファイル選択
                                                </button>
                                                <span class="text-nowrap">
                                                    {{teacherFileNameAttached}}
                                                </span>
                                                <button type="button" v-on:click="newFileTeacher"
                                                        class="btn btn-primary  mr-2">新規ファイル追加
                                                </button>
                                                <input type="file" name="teacherNewFile" id="teacherNewFile" ref="teacherNewFile"
                                                       v-on:change="changeFileTeacher" class="hidden">
                                                <span class="text-nowrap">
                                                    {{teacherFileName}}

                                                </span>


                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="lessonTextName">テキスト名 :
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="lessonTextName" type="text" name="lessonTextName" @input="changeInput()"  v-model="lessonTextName"  v-validate="'required|max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("lessonTextName") }}
                                                </div>


                                            </div>
                                        </div>




                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="lessonTextDescription"> 説明 : </label>

                                            <div class="col-md-6">
                                                            <textarea class="form-control" id="lessonTextDescription"  name="lessonTextDescription"
                                                                      @input="changeInput()"  v-model="lessonTextDescription">
                                                            </textarea>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("lessonTextDescription") }}
                                                </div>

                                            </div>
                                        </div>



                                        <div class="form-actions text-center">
                                            <div class="line"></div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                    <a :href="listTextUrl" class="btn btn-default w-100">閉じる</a>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <add-files :pageSizeLimit="pageSizeLimit" :url="getFilesUrl">

        </add-files>
        <loader :flag-show="flagShowLoader"></loader>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import AddFiles from "./add-files.vue"


    export default {
        created: function () {
            let messError = {
                custom: {
                    lessonTextNo : {
                        require: "表示順を入力してください",
                        min_value: "表示順は1～1000000000 を入力してください",
                        max_value: "表示順は1～1000000000 を入力してください"
                    },
                    lessonTextUrl : {
                        url: "テキストURL（学習者用）をURL形で入力してください。",
                        max: "テキストURL（学習者用）は255文字以内で入力してください。",
                    },
                    lessonTextUrlForTeacher : {
                        url: "テキストURL（講師用）をURL形で入力してください。",
                        max: "テキストURL（講師用）は255文字以内で入力してください。",
                    },
                    lessonTextName: {
                        required: "テキスト名を入力してください",
                        max: "テキスト名は255文字以内で入力してください。",
                    },
                    lessonTextSoundUrl : {
                        max: "MP3 URLは255文字以内で入力してください。",
                        url: "MP3 URLをURL形で入力してください。",
                    }

                },
            };
            this.$validator.localize("en", messError);
        },
        components: {
            Loader,
            AddFiles
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
                lessonTextNo: 1,
                lessonTextUrl: '',
                lessonTextUrlForTeacher: '',
                lessonTextDescription : '',
                lessonTextName : '',
                lessonTextSoundUrl : '',
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},
                studentFileSelected: null,
                studentFileName: '',
                studentFileNameAttached: '',
                teacherFileSelected: null,
                teacherFileName: '',
                teacherFileNameAttached: '',
                studentFileId: null,
                teacherFileId: null

            };
        },
        props: ["listTextUrl", "createUrl", "getFilesUrl", "pageSizeLimit", "fileType"],
        mounted() {},
        methods: {
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("lessonTextNo", this.lessonTextNo);
                formData.append("lessonTextUrl", this.lessonTextUrl);
                formData.append("lessonTextUrlForTeacher", this.lessonTextUrlForTeacher);
                formData.append("lessonTextName", this.lessonTextName);
                formData.append("lessonTextDescription", this.lessonTextDescription);
                formData.append("lessonTextSoundUrl", this.lessonTextSoundUrl);
                if (this.studentFileSelected)
                    formData.append('studentFileSelected', this.studentFileSelected);
                if (this.studentFileId)
                    formData.append('studentFileId', this.studentFileId);

                if (this.teacherFileSelected)
                    formData.append('teacherFileSelected', this.teacherFileSelected);
                if (this.teacherFileId)
                    formData.append('teacherFileId', this.teacherFileId);
                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.createUrl , formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                this.$swal({
                                    title: "テキスト新規作成が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = baseUrl + '/text/' + res.data.lesson_text_id;
                                });
                                that.flagShowLoader = false;
                            })
                            .catch((err) => {
                                switch (err.response.status) {
                                    case 422:
                                    case 400:
                                        this.errorsData = err.response.data;
                                        that.flagShowLoader = false;
                                        break;
                                    case 500:
                                        this.$swal({
                                            title: "失敗したデータを追加しました",
                                            icon: "error",
                                            confirmButtonText: "OK",
                                        }).then(function (confirm) {});
                                        that.flagShowLoader = false;
                                        break;
                                    default:
                                        break;
                                }
                            });
                    }
                });

            },
            changeInput() {
                this.errorsData = [];
                this.messageText = "";
            },
            show(modalName) {
                this.$modal.show(modalName, {
                    fileType: this.fileType,
                    fileId: this.studentFileId,
                    handlers: {
                        sendFileId: (...args) => {
                            this.studentFileId = args[0].fileId;
                            this.studentFileNameAttached = args[0].selectedFileName;
                            this.studentFileSelected = null;
                            this.studentFileName = null;
                        }
                    }
                });
            },
            newFile() {
                this.$refs.studentNewFile.click();
            },
            changeFile(e) {
                this.studentFileId = null;
                this.studentFileNameAttached = '';
                this.studentFileSelected = e.target.files[0];
                this.studentFileName = e.target.files[0].name;
            },
            showTeacher(modalName) {
                this.$modal.show(modalName, {
                    fileType: this.fileType,
                    fileId: this.teacherFileId,
                    handlers: {
                        sendFileId: (...args) => {
                            this.teacherFileId = args[0].fileId;
                            this.teacherFileNameAttached = args[0].selectedFileName;
                            this.teacherFileSelected = null;
                            this.teacherFileName = null;
                        }
                    }
                });
            },
            newFileTeacher() {
                this.$refs.teacherNewFile.click();
            },
            changeFileTeacher(e) {
                this.teacherFileId = null;
                this.teacherFileNameAttached = '';
                this.teacherFileSelected = e.target.files[0];
                this.teacherFileName = e.target.files[0].name;
            },
        },
    }
</script>
