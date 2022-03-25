<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            テキスト編集

                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">テキスト情報
                                    </div>
                                    <div class="card-body">


                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="lessonTextNo">表示順:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="lessonTextNo" type="number" name="lessonTextNo" @input="changeInput()" style="max-width: 100px" v-model="lessonTextNo" value="1" v-validate="'min_value:1|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("lessonTextNo") }}
                                                </div>

                                            </div>
                                        </div>

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
                                        <div class="form-group row " style="margin-top: -15px">
                                            <div class="col-md-3">

                                            </div>
                                            <div class="col-md-6 input-group is-danger" role="alert">
                                                {{ errors.first("lessonTextUrl") }}
                                            </div>
                                        </div>


                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="lessonTextUrlForTeacher">テキストファイル（講師用）:

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
                                        <div class="form-group row " style="margin-top: -15px">
                                            <div class="col-md-3">

                                            </div>
                                            <div class="col-md-6 input-group is-danger" role="alert">
                                                {{ errors.first("lessonTextUrlForTeacher") }}
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
                                                    <button type="button" class="btn btn-danger w-100 mr-2" v-on:click="showAlert" >削除
                                                    </button>
                                                    <a :href="detailTextUrl" class="btn btn-default w-100">閉じる</a>
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
                        url: "テキストURL（生徒用）をURL形で入力してください。",
                        max: "テキストURL（生徒用）は255文字以内で入力してください。",
                    },
                    lessonTextUrlForTeacher : {
                        url: "テキストURL（先生用）をURL形で入力してください。",
                        max: "テキストURL（先生用）は255文字以内で入力してください。",
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
                id : this.lessonText.id,
                csrfToken: Laravel.csrfToken,
                lessonTextNo: this.lessonText.lesson_text_no,
                lessonTextUrl: this.lessonText.lesson_text_url,
                lessonTextUrlForTeacher: this.lessonText.lesson_text_url_for_teacher,
                lessonTextDescription : this.lessonText.lesson_text_description ?? '',
                lessonTextName : this.lessonText.lesson_text_name,
                lessonTextSoundUrl : this.lessonText.lesson_text_sound_url,
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},
                studentFileSelected: null,
                studentFileName: '',
                studentFileNameAttached: this.lessonText.student_file === null ? '' : this.lessonText.student_file.file_name_original,
                teacherFileSelected: null,
                teacherFileName: '',
                teacherFileNameAttached: this.lessonText.teacher_file === null ? '' : this.lessonText.teacher_file.file_name_original,
                studentFileId: this.lessonText.lesson_text_student_file_id ?? null,
                teacherFileId: this.lessonText.lesson_text_teacher_file_id ?? null

            };
        },
        props: ["listTextUrl", "updateUrl", 'lessonText', 'deleteAction', 'detailTextUrl', "getFilesUrl", "pageSizeLimit", "fileType"],
        mounted() {},
        methods: {
            checkLink(url) {
                if (url != null) {
                    window.open(url, '_blank');
                }
            },
            showAlert() {
                let that = this;
                this.$swal({
                    title: 'このテキストを削除しますか？',
                    icon: "warning",
                    confirmButtonText: "削除する",
                    cancelButtonText: "閉じる",
                    showCancelButton: true
                }).then(result => {
                    if (result.value) {
                        that.flagShowLoader = true;
                        $('.loading-div').removeClass('hidden');
                        axios
                            .delete(that.deleteAction, {
                                _token: Laravel.csrfToken
                            })
                            .then(function (response) {
                                that.flagShowLoader = false;
                                that
                                    .$swal({
                                        title: response.data.message,
                                        icon: "success",
                                        confirmButtonText: "閉じる"
                                    })
                                    .then(function () {
                                        window.location.href = that.listTextUrl;
                                    });
                            })
                            .catch(error => {
                                that.flagShowLoader = false;
                            });
                    }
                });
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
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("lessonTextNo", this.lessonTextNo);
                formData.append("lessonTextUrl", this.lessonTextUrl ?? '');
                formData.append("lessonTextUrlForTeacher", this.lessonTextUrlForTeacher ?? '');
                formData.append("lessonTextName", this.lessonTextName);
                formData.append("lessonTextDescription", this.lessonTextDescription ?? '');
                formData.append("lessonTextSoundUrl", this.lessonTextSoundUrl ?? '');
                formData.append('_method', 'PUT');
                formData.append('id', this.id);
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
                            .post(that.updateUrl , formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                this.$swal({
                                    title: "テキスト編集が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = that.detailTextUrl;
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
                                            title: "失敗したデータを編集しました",
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
        },
    }
</script>
