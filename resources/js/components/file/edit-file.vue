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
                        <div class="col-md-7">
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
                                            <div class="col-md-8 pt-7">
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
                                            <div class="col-md-8">
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
                                            <div class="col-md-8 pt-7">
                                                <a :href="file_path" target="_blank">{{ file_name_original }}</a>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >アップロード方法<span
                                                    class="glyphicon glyphicon-star"
                                                ></span
                                            ></label>
                                            <div class="col-md-5" >
                                                 <select
                                                    class="form-control"
                                                    name="option_upload_file"
                                                    v-model="
                                                        option_upload_file
                                                    "
                                                    v-validate="'required'"
                                                >
                                                    <option :value="key" v-for="(value, key) in optionUploadFile">
                                                        {{ value }}</option
                                                    >
                                                </select>                                               
                                            </div>
                                        </div>
                                        <div class="form-group row" v-if="option_upload_file == 0">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >メディアファイル</label>
                                            <div class="col-md-9">
                                                <div class="flex">
                                                    <button type="button" v-on:click="newFile"
                                                        class="btn btn-primary  mr-2">新規ファイル追加
                                                    </button>
                                                    <span class="pt-7">{{ file_original_name }}</span>
                                                    <input type="file" name="file_attach" ref="newFile"
                                                        v-on:change="changeFile" class="hidden"
                                                        v-validate="
                                                            'max_sz_50'
                                                        "
                                                    />
                                                </div>
                                                
                                                <div class="input-group is-danger" role="alert" v-if="errors.has('file_attach')"
                                                >
                                                    {{ errors.first("file_attach") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" v-if="option_upload_file == 1">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                                >メディアURL</label>
                                            <div class="col-md-8">
                                                <input
                                                    class="form-control"
                                                    name="url_file_path"
                                                    v-model="url_file_path"
                                                    v-validate="
                                                        'max:255|format_url'
                                                    "
                                                />
                                                <div class="input-group is-danger" role="alert" v-if="errors.has('url_file_path')"
                                                >
                                                    {{ errors.first("url_file_path") }}
                                                </div>
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
                                            <div class="col-md-8">
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
                                            <div class="col-md-8">
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

                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">メディア一覧</h5>
                                </div>
                                <div class="card-body pt-0" v-if="fileInfo.mediaList.length > 0">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>種別</th>
                                                <th>メディア概要</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="file in fileInfo.mediaList">
                                                <td>{{ file.media_type }}</td>
                                                <td><a :href="file.href" target="_blank">{{ file.media_name }}</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-body" v-if="fileInfo.mediaList.length == 0">
                                    <data-empty></data-empty> 
                                </div>
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
import DataEmpty from "./../common/data-empty.vue";
import moment from 'moment'

export default {
    created: function() {
        let messError = {
            custom: {
                file_code: {
                    required: "メディアコードを入力してください",
                    max: "メディアコードは255文字以内で入力してください",
                    unique_custom: "メディアコードが存在されています。"
                },
                file_display_name: {
                    required: "メディア名を入力してください",
                    max: "メディア名は255文字以内で入力してください"
                },
                file_description: {
                    max: "説明は20000文字以内で入力してください"
                },
                url_file_path: {
                    required: "メディアURLを入力してください",
                    format_url: "AzureStorage内に保存されるファイルのURLを指定してください。"
                },
                file_attach: {
                    max_sz_50 : "メディアファイルを50MBを超えた為、アップロードできません。"
                },
            }
        };
        this.$validator.localize("en", messError);

        this.$validator.extend("max_sz_50", {
            validate(value, args) {
                if (value[0] && value[0].size > (50 * 1024 * 1024))
                    return {valid : false};
                return { valid : true};
            }
        })
        let that = this;
        this.$validator.extend("format_url", {
            validate(value, args) {
                if (!value.includes(that.fileBaseMedia)) {
                    return {valid : false};
                }
                let arrUrl = value.split(that.fileBaseMedia);
                if(arrUrl[0].length != 0 || arrUrl[1].length == 0) {
                    return {valid : false};
                }
                return { valid : true};
            }
        })
    },
    components: {
        Loader, DataEmpty
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
            file_original_name: '',
            optionUploadFile: this.fileInfo.optionUploadFile,
            fileBaseMedia: this.fileInfo.fileBaseMedia,
            option_upload_file: 0,
            url_file_path: ''
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
            formData.append("option_upload_file", this.option_upload_file);
            formData.append("url_file_path", this.url_file_path);
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
                    if(e.response.data.errors.file_code) {
                        that.errors.add({
                            field: 'file_code',
                            msg: e.response.data.errors.file_code[0]
                        });
                    }
                    if(e.response.data.errors.url_file_path) {
                         that.errors.add({
                            field: 'url_file_path',
                            msg: e.response.data.errors.url_file_path[0]
                        });
                    }
                });
        }
    }
};
</script>

<style scoped>
    .pt-0 {
        padding-top: 0;
    }
    th {
        border-top: none;
    }
</style>

