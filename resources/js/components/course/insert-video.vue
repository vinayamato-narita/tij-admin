<template>
    <modal name="insert-video-modal"   :pivotY="0.1" :reset="true" :width="600" :height="auto"  :scrollable="true" :adaptive="true" :clickToClose="false" >
        <div class="card">
            <div class="card-header">動画登録

                <div class="float-right">
                    <button type="button" class="close"  v-on:click="hide" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">

                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right" for="videoName">動画名:
                                <span class="glyphicon glyphicon-star"
                                ></span>
                            </label>
                            <div class="col-md-6">
                                <input class="form-control" id="videoName" type="text" name="videoName" @input="changeInput()"  v-model="videoName"  v-validate="'required|max:255'" />

                                <div class="input-group is-danger" role="alert">
                                    {{ errors.first("videoName") }}
                                </div>

                            </div>

                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right" for="imageUrl">サムネイルURL:
                                <span class="glyphicon glyphicon-star"
                                ></span>
                            </label>

                            <div class="col-md-6">
                                <input class="form-control" id="imageUrl" type="text" name="imageUrl" @input="changeInput()"  v-model="imageUrl"   v-validate="'required|max:255|url'" />

                                <div class="input-group is-danger" role="alert">
                                    {{ errors.first("imageUrl") }}
                                </div>

                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right" for="videoUrl">URL :
                                <span class="glyphicon glyphicon-star"
                                ></span>
                            </label>

                            <div class="col-md-6">
                                <input class="form-control" id="videoUrl" type="text" name="videoUrl" @input="changeInput()"  v-model="videoUrl"   v-validate="'required|max:255|url'" />

                                <div class="input-group is-danger" role="alert">
                                    {{ errors.first("videoUrl") }}
                                </div>

                            </div>
                        </div>


                        <div class="form-actions text-center">
                            <div class="line"></div>
                            <div class="form-group">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary w-100 mr-2" >登録</button>
                                    <a v-on:click="hide" class="btn btn-default w-100">閉じる</a></div>
                            </div>
                        </div>

                    </div>
                </form>




                </div>



            </div>




    </modal>

</template>

<script>
    import Loader from "./../../components/common/loader";

    export default {
        name: "modal-table",
        components: {
            Loader
        },
        data() {
            return {
                auto : 'auto',
                videoName : '',
                imageUrl: '',
                videoUrl : '',
                errorsData : [],
                messageText :"",

            };
        },
        props: [ 'registerUrl', 'detailUrl', 'id'],
        mounted() {
        },
        created: function () {
            let messError = {
                custom: {
                    videoName : {
                        required: "動画名を入力してください",
                        max: "動画名は255文字以内で入力してください。",
                        url: "動画名をURL形で入力してください。",
                    },
                    imageUrl: {
                        required: "サムネイルURLを入力してください",
                        max: "サムネイルURLは255文字以内で入力してください。",
                        url: "サムネイルURLをURL形で入力してください。",
                    },
                    videoUrl: {
                        required: "URLを入力してください",
                        max: "URLは255文字以内で入力してください。",
                        url: "URLをURL形で入力してください。",
                    },

                },
            };
            this.$validator.localize("en", messError);


        },
        methods: {
            hide () {
                this.$modal.hide('insert-video-modal');
            },
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("videoName", this.videoName);
                formData.append("imageUrl", this.imageUrl);
                formData.append("videoUrl", this.videoUrl);

                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.registerUrl , formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                window.location.reload();
                            })
                            .catch((err) => {
                                switch (err.response.status) {
                                    case 422:
                                    case 400:
                                        this.errorsData = err.response.data;
                                        window.location.reload();
                                        break;
                                    case 500:
                                        window.location.reload();
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

<style scoped>

</style>
