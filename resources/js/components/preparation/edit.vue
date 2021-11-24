<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            予習編集

                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="form-horizontal " style="width: 100%" method="POST" ref="registerForm"
                                      @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">予習情報
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="displayOrder">表示順:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="displayOrder" type="number"
                                                       name="displayOrder" @input="changeInput()"
                                                       style="max-width: 100px" v-model="displayOrder" value="1"
                                                       onKeyDown="return false"
                                                       v-validate="'min_value:1|max_value:1000000000'"/>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("displayOrder") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="displayOrder">予習ID:

                                            </label>
                                            <div class="col-md-6 text-md-left p-2">
                                                {{this.preparation.preparation_id}}

                                            </div>
                                        </div>


                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right">予習動画:

                                            </label>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-primary w-100 mr-2" v-on:click="show('add-files-modal')">ファイル選択</button>
                                                <span class="text-nowrap">
                                                    {{fileNameAttached}}
                                                </span>
                                                <button type="button" v-on:click="newFile" class="btn btn-primary  mr-2">新規ファイル追加</button>
                                                <input type="file" name="newFile" id="newFile" ref="newFile" v-on:change="changeFile" class="hidden">
                                                <span class="text-nowrap">
                                                    {{fileName}}

                                                </span>


                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="preparationName">予習名:
                                                <span class="glyphicon glyphicon-star"
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
                                                    <button type="button" class="btn btn-danger w-100 mr-2" v-on:click="showAlert" >削除
                                                    </button>
                                                    <a :href="detailPreparationUrl" class="btn btn-default w-100">閉じる</a>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <add-files :pageSizeLimit="pageSizeLimit" :url="getFilesUrl">

                </add-files>
            </div>
        </main>
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
                    displayOrder: {
                        require: "表示順を入力してください",
                        min_value: "表示順は1～1000000000 を入力してください",
                        max_value: "表示順は1～1000000000 を入力してください"
                    },
                    preparationName: {
                        required: "予習名を入力してください",
                        max: "予習名は255文字以内で入力してください。",
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
                preparationName: this.preparation.preparation_name,
                fileSelected : null,
                fileName: '',
                messageText: this.message,
                preparationDescription: this.preparation.preparation_description,
                errorsData: {},
                fileNameAttached : this.preparation.file === null ? '' : this.preparation.file.file_name_original,
                fileId :  this.preparation.file === null ? '' : this.preparation.file.file_id
            };
        },
        props: ["detailPreparationUrl", "updateUrl", 'preparation', 'deleteAction', 'listPreparationUrl', 'pageSizeLimit', 'getFilesUrl', 'fileType'],
        mounted() {
        },
        methods: {
            show (modalName) {
                this.$modal.show(modalName ,{
                    fileType : this.fileType,
                    fileId: this.fileId,
                    preparationId : this.preparation.preparation_id,
                    handlers: {
                        sendFileId: (...args) => {
                            this.fileId = args[0].fileId;
                            this.fileNameAttached = args[0].selectedFileName;
                            this.fileSelected = null;
                            this.fileName = null;
                        }
                    }});
            },
            showAlert() {
                let that = this;
                this.$swal({
                    title: 'この予習を削除しますか？',
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
                                        window.location.href = that.listPreparationUrl;
                                    });
                            })
                            .catch(error => {
                                that.flagShowLoader = false;
                            });
                    }
                });
            },
            newFile() {
                this.$refs.newFile.click();
            },
            changeFile(e) {
                this.fileId = null;
                this.fileNameAttached = '';
                this.fileSelected =  e.target.files[0];
                this.fileName = e.target.files[0].name;
            },
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("displayOrder", this.displayOrder);
                formData.append("preparationName", this.preparationName);
                if (this.preparationDescription)
                    formData.append("preparationDescription", this.preparationDescription);
                if (this.fileSelected)
                    formData.append('fileSelected', this.fileSelected);
                if (this.fileId)
                    formData.append('fileId', this.fileId);
                formData.append('_method', 'PUT');
                formData.append('id', this.preparation.preparation_id);


                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.updateUrl, formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                this.$swal({
                                    title: "予習編集が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                });
                                that.flagShowLoader = false;
                                window.location.href = this.listPreparationUrl;
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
                                        }).then(function (confirm) {
                                        });
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
