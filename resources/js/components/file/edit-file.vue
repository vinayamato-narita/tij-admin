<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            メディア編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form
                                    class="basic-form"
                                    @submit.prevent="save"
                                    autocomplete="off"
                                    enctype="multipart/form-data"
                                >
                                    <div class="card-header">
                                        <h5 class="title-page">メディア情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >ID</span
                                            ></label>
                                            <div class="col-md-3 pt-7">
                                                {{ file_id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >メディアコード<span
                                                    class="glyphicon glyphicon-star"
                                                ></span
                                            ></label>
                                            <div class="col-md-3">
                                                <div class="flex">
                                                    <span style="margin-right: 10px" class="pt-7">{{ pre_code }}</span>
                                                    <input
                                                        class="form-control"
                                                        name="file_code"
                                                        v-model="file_code"
                                                        v-validate="
                                                            'required|max:255'
                                                        "
                                                    />
                                                </div>
                                                
                                                <div class="input-group is-danger" role="alert" v-if="errors.has('file_code')"
                                                >
                                                    {{ errors.first("file_code") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >ダウンロードURL</label>
                                            <div class="col-md-9 pt-7">
                                                <a :href="file_path" target="_blank">{{ file_name_original }}</a>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >メディアファイル</label>
                                            <div class="col-md-9 flex">
                                                <button type="button" v-on:click="newFile"
                                                        class="btn btn-primary  mr-2">新規ファイル選択
                                                </button>
                                                <span class="pt-7">{{ file_original_name }}</span>
                                                <input type="file" name="file_attach" ref="newFile"
                                                    v-on:change="changeFile" class="hidden"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >メディア名<span
                                                    class="glyphicon glyphicon-star"
                                                ></span
                                            ></label>
                                            <div class="col-md-3">
                                                <input
                                                    class="form-control"
                                                    name="file_display_name"
                                                    v-model="file_display_name"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />
                                                <div class="input-group is-danger" role="alert" v-if="errors.has('file_display_name')"
                                                >
                                                    {{ errors.first("file_display_name") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >説明</label>
                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    rows="5"
                                                    name="file_description"
                                                    v-model="file_description"
                                                    v-validate="
                                                        'max:20000'
                                                    "
                                                ></textarea>

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errors.has('file_description')"
                                                >
                                                    {{ errors.first("file_description") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button
                                                    type="submit"
                                                    class="btn btn-primary w-100 mr-2"
                                                >
                                                    登録
                                                </button>
                                                <a
                                                    :href="urlFileList"
                                                    class="btn btn-default w-100"
                                                    >閉じる</a
                                                >
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
        <loader :flag-show="flagShowLoader"></loader>
    </div>
</template>

<script type="text/javascript">
import axios from "axios";
import Loader from "./../common/loader.vue";
import moment from 'moment'

export default {
    created: function() {
        let messError = {
            custom: {
                file_code: {
                    required: "メディアコードを選択してください",
                    max: "メディアコードは255文字以内で入力してください",
                    unique_custom: "メディアコードが存在されています。"
                },
                file_display_name: {
                    required: "メディア名を入力してください",
                    max: "メディア名は255文字以内で入力してください"
                },
                file_description: {
                    max: "説明は20000文字以内で入力してください"
                }
            }
        };
        this.$validator.localize("en", messError);
    },
    components: {
        Loader
    },
    data() {
        return {
            flagShowLoader: false,
            file_id: this.fileInfo.file_id,
            file_path: this.fileInfo.file_path,
            pre_code: this.fileInfo.pre_code,
            file_code: this.fileInfo.file_code,
            file_display_name: this.fileInfo.file_display_name,
            file_description: this.fileInfo.file_description,
            file_name_original: this.fileInfo.file_name_original,
            file_original_name: ''
        };
    },
    props: ["urlAction", "urlFileList", "fileInfo"],
    mounted() {},
    methods: {
        newFile() {
            this.$refs.newFile.click();
        },
        changeFile(e) {
            if(e.target.files.length != 0) {
                let fileName = e.target.files[0].name;
                this.file_original_name = e.target.files[0].name;
                this.file_attach = e.target.files[0];
                if(!(this.file_display_name != "")) {
                    this.file_display_name = fileName.split('.').slice(0, -1).join('.');
                }
            }else {
                this.file_original_name = '';
            }
        },
        save() {
            let that = this;
            this.$validator
                .validateAll()
                .then(valid => {
                    if (valid) {
                        that.flagShowLoader = true;
                        that.submit();
                    }
                })
                .catch(function(e) {});
        },
        submit(e) {
            let that = this;
            let formData = new FormData();
            formData.append("_token", Laravel.csrfToken);
            formData.append("file_id", this.file_id);
            formData.append("file_code", this.pre_code + (this.file_code != null && this.file_code != '' ? this.file_code.trim() : ''));
            formData.append("file_display_name", this.file_display_name);
            formData.append("file_description", this.file_description);
            if (this.file_attach) {
                formData.append('file_attach', this.file_attach);
            }
            
            axios
                .post(that.urlAction, formData)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "メディア編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlFileList;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                    that.errors.add({
                        field: 'file_code',
                        msg: e.response.data.errors.file_code[0]
                    });
                });
        }
    }
};
</script>
