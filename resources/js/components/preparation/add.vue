<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            予習新規作成

                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="form-horizontal " style="width: 100%" method="POST" ref="registerForm"
                                      @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page">予習情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right">予習動画:

                                            </label>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-primary w-100 mr-2"
                                                        v-on:click="show('add-files-modal')">ファイル選択
                                                </button>
                                                <span class="text-nowrap">
                                                    {{fileNameAttached}}
                                                </span>
                                                <button type="button" v-on:click="newFile"
                                                        class="btn btn-primary  mr-2">新規ファイル追加
                                                </button>
                                                <input type="file" name="newFile" id="newFile" ref="newFile"
                                                       v-on:change="changeFile" class="hidden">
                                                <span class="text-nowrap">
                                                    {{fileName}}

                                                </span>


                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="file_code">メディアコード:
                                                <span v-if="!disabled_file_code" class="glyphicon glyphicon-star position-absolute"
                                                ></span>
                                            </label>
                                            <div class="col-md-3" >
                                                <div class="flex">
                                                    <input type="hidden" 
                                                        name="moment"
                                                        v-model="moment"
                                                    >
                                                    <span style="margin-right: 10px" class="pt-7">{{ moment }}</span>
                                                    <input
                                                        class="form-control"
                                                        name="file_code"
                                                        v-model="file_code"
                                                        :disabled="disabled_file_code"
                                                        v-validate="
                                                            'max:255'
                                                        "
                                                    />
                                                </div>
                                                <div class="input-group is-danger" role="alert" v-if="errors.has('file_code')"
                                                >
                                                    {{ errors.first("file_code") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="preparationName">予習名:
                                                <span class="glyphicon glyphicon-star position-absolute"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="preparationName" type="text"
                                                       name="preparationName" @input="changeInput()"
                                                       v-model="preparationName" v-validate="'required|max:255'"/>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("preparationName") }}
                                                </div>


                                            </div>
                                        </div>


                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right"
                                                   for="preparationDescription"> 説明: </label>

                                            <div class="col-md-6">
                                                            <textarea class="form-control" id="preparationDescription"
                                                                      name="preparationDescription"
                                                                      @input="changeInput()"
                                                                      v-model="preparationDescription">
                                                            </textarea>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("preparationDescription") }}
                                                </div>

                                            </div>
                                        </div>


                                        <div class="form-actions text-center">
                                            <div class="line"></div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                    <a :href="listPreparationUrl" class="btn btn-default w-100">閉じる</a>
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
            <add-files :pageSizeLimit="pageSizeLimit" :url="getFilesUrl">

            </add-files>
        </main>
        <loader :flag-show="flagShowLoader"></loader>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import AddFiles from "./add-files.vue"
    import moment from 'moment'

    export default {
        created: function () {
            let messError = {
                custom: {
                    displayOrder: {
                        require: "表示順を入力してください",
                        min_value: "表示順は1～1000000000 を入力してください",
                        max_value: "表示順は1～1000000000 を入力してください"
                    },
                    preparationName: {
                        required: "予習名を入力してください",
                        max: "予習名は255文字以内で入力してください。",
                    },
                    file_code: {
                        max: "メディアコードは255文字以内で入力してください"
                    },
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
                displayOrder: 1,
                flagShowLoader: false,
                preparationName: '',
                fileSelected: null,
                fileName: '',
                fileNameAttached: '',
                messageText: this.message,
                preparationDescription: '',
                errorsData: {},
                fileId: null,
                moment: 'PR' + moment().format("YMMDD"),
                file_code: '',
                disabled_file_code: true
            };
        },
        props: ["listPreparationUrl", "createUrl", "pageSizeLimit", "getFilesUrl", "fileType"],
        mounted() {
        },
        methods: {
            show(modalName) {
                this.$modal.show(modalName, {
                    fileType: this.fileType,
                    fileId: this.fileId,
                    handlers: {
                        sendFileId: (...args) => {
                            this.fileId = args[0].fileId;
                            this.fileNameAttached = args[0].selectedFileName;
                            this.fileSelected = null;
                            this.fileName = null;
                            this.file_code = args[0].file_code;
                            this.preparationName = args[0].file_display_name;
                            this.preparationDescription = args[0].file_description;
                            this.disabled_file_code = true;
                        }
                    }
                });
            },
            newFile() {
                this.$refs.newFile.click();
            },
            changeFile(e) {
                this.fileId = null;
                this.fileNameAttached = '';
                this.fileSelected = e.target.files[0];
                this.disabled_file_code = true;
                this.fileName = null;
                this.file_code = null;
                if(e.target.files.length != 0) {
                    this.fileName = e.target.files[0].name;
                    this.disabled_file_code = false;
                }
            },
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("displayOrder", this.displayOrder);
                formData.append("preparationName", this.preparationName);
                if (this.preparationDescription)
                    formData.append("preparationDescription", this.preparationDescription);
                if (this.fileSelected) {
                    formData.append("file_code", this.moment + (this.file_code ?? ''));
                    formData.append('fileSelected', this.fileSelected);
                }
                if (this.fileId)
                    formData.append('fileId', this.fileId);

                this.$validator.validateAll().then((valid) => {
                    if (that.fileSelected && (that.file_code == null || that.file_code == '')) {
                        that.errors.add({
                            field: 'file_code',
                            msg: 'メディアコードを選択してください'
                        });
                        return false;
                    }
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.createUrl, formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                var id = res.data.id;
                                this.$swal({
                                    title: "予習新規作成が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = '/preparation/' + id;

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
                                        }).then(function (confirm) {
                                        });
                                        that.flagShowLoader = false;
                                        break;
                                    default:
                                        break;
                                }
                                that.errors.add({
                                    field: 'file_code',
                                    msg: err.response.data.errors.file_code[0]
                                });
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
