<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            復習編集

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
                                        <h5 class="title-page">復習情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="displayOrder">復習ID:

                                            </label>
                                            <div class="col-md-6 text-md-left p-2">
                                                {{this.review.review_id}}

                                            </div>
                                        </div>


                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right">復習動画:

                                            </label>
                                            <div class="col-md-9">
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

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="file_code">メディアコード:
                                                <span v-if="!disabled_file_code" class="glyphicon glyphicon-star position-absolute"
                                                ></span>
                                            </label>
                                            <div class="col-md-3" >
                                                <div class="flex">
                                                    <span style="margin-right: 10px" class="pt-7" v-if="!disabled_file_code">{{ pre_code }}</span>
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
                                            <label class="col-md-3 col-form-label text-md-right" for="preparationName">復習名:
                                                <span class="glyphicon glyphicon-star position-absolute"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="reviewName" type="text"
                                                       name="reviewName" @input="changeInput()"
                                                       v-model="reviewName" v-validate="'required|max:255'"/>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("reviewName") }}
                                                </div>


                                            </div>
                                        </div>


                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right"
                                                   for="reviewDescription"> 説明: </label>

                                            <div class="col-md-6">
                                                            <textarea class="form-control" id="reviewDescription"
                                                                      name="reviewDescription"
                                                                      rows="5"
                                                                      @input="changeInput()"
                                                                      v-model="reviewDescription">
                                                            </textarea>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("reviewDescription") }}
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
                                                    <a :href="detailReviewUrl" class="btn btn-default w-100">閉じる</a>
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
                    reviewName: {
                        required: "復習名を入力してください",
                        max: "復習名は255文字以内で入力してください。",
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
                displayOrder: this.review.display_order,
                flagShowLoader: false,
                reviewName: this.review.review_name,
                fileSelected : null,
                fileName: '',
                messageText: this.message,
                reviewDescription: this.review.review_description,
                errorsData: {},
                fileNameAttached : this.review.file === null ? '' : this.review.file.file_name_original,
                fileId :  this.review.file === null ? '' : this.review.file.file_id,
                pre_code: 'RE' + moment().format("YMMDD"),
                file_code: this.review.file === null ? '' : this.review.file.file_code,
                disabled_file_code: true
            };
        },
        props: ["detailReviewUrl", "updateUrl", 'review', 'deleteAction', 'listReviewUrl', 'pageSizeLimit', 'getFilesUrl', 'fileType'],
        mounted() {
        },
        methods: {
            show (modalName) {
                this.$modal.show(modalName ,{
                    fileType : this.fileType,
                    fileId: this.fileId,
                    reviewId : this.review.review_id,
                    handlers: {
                        sendFileId: (...args) => {
                            this.fileId = args[0].fileId;
                            this.fileNameAttached = args[0].selectedFileName;
                            this.fileSelected = null;
                            this.fileName = null;
                            this.file_code = args[0].file_code;
                            this.disabled_file_code = true;

                            if(!(this.reviewName != "")) {
                                this.reviewName = args[0].file_display_name;
                            }
                            if(!(this.reviewDescription != "")) {
                                this.reviewDescription = args[0].file_description;
                            }
                        }
                    }});
            },
            showAlert() {
                let that = this;
                this.$swal({
                    title: 'この復習を削除しますか？',
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
                                        window.location.href = that.listReviewUrl;
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
                this.disabled_file_code = true;
                this.fileName = null;
                this.file_code = null;
                this.errors.remove('file_code');
                if(e.target.files.length != 0) {
                    this.fileName = e.target.files[0].name;
                    this.disabled_file_code = false;
                }
            },
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("displayOrder", this.displayOrder);
                formData.append("reviewName", this.reviewName);
                if (this.reviewDescription)
                    formData.append("reviewDescription", this.reviewDescription);
                if (this.fileSelected){
                    formData.append("file_code", this.pre_code + (this.file_code != null && this.file_code != '' ? this.file_code.trim() : ''));
                    formData.append('fileSelected', this.fileSelected);
                }
                if (this.fileId)
                    formData.append('fileId', this.fileId);
                formData.append('_method', 'PUT');
                formData.append('id', this.review.review_id);

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
                                    window.location.href = that.detailReviewUrl;
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
