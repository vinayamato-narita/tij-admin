<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            実力テスト評価


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">実力テスト情報
                                    <div class="float-right">
                                        <a :href="answerDetailUrl" target="_blank" class="btn btn-primary ">学習者の回答</a>
                                        <a :href="groupLessonGuideUrl" target="_blank" class="btn btn-primary ">マニュアル</a>

                                    </div>


                                </div>
                                <div class="card-body">
                                    <div class="alert alert-secondary font-weight-bold text-center" v-if="disableComment">
                                        他の方が評価実施中のため、評価入力できません
                                    </div>
                                    <div class="">
                                        <table class="table table-responsive-sm table-striped border">
                                            <thead>
                                            <tr>
                                                <th class="text-left min-width-150">科目</th>
                                                <th class="text-left min-width-150">大問</th>
                                                <th   class="text-left min-width-150">問題数</th>
                                                <th   class="text-left min-width-150">得点</th>
                                                <th class="text-left min-width-150">　配点</th>
                                                <th class="text-left min-width-150">合格者平均
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <template v-for="analyticItemArr in analyticList">
                                                <tr v-for="(analyticItem, index) in analyticItemArr ">
                                                    <td  :rowspan="analyticItemArr.length"  class="text-start tit" v-if="index === 0">{{ analyticItem.parent_category_name}}</td>
                                                    <td  class="text-left" style="border-left: #d8dbe0 1px solid">{{ analyticItem.category_name}}</td>
                                                    <td  class="text-left" > {{ analyticItem.num_sub_question }}</td>
                                                    <td  class="text-left"> {{ analyticItem.exam_score }}</td>
                                                    <td  class="text-left"> {{ analyticItem.score }}</td>
                                                    <td  class="text-left">{{ analyticItem.top_score_avg }}</td>
                                                </tr>
                                            </template>

                                            </tbody>
                                        </table>
                                    </div>


                                </div>

                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-secondary font-weight-bold text-center" v-if="!disableComment">
                                        3時間以内に評価登録を完了しないと、入力内容が失われます。ご注意ください。                                    </div>
                                    <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                        <div class="form-group row" v-for="(comment, index) in commentsModels">
                                            <label class="col-md-3 col-form-label text-md-right" :for="index">{{comment.title}} :

                                            </label>
                                            <div class="col-md-6">
                                                <textarea :disabled="disableComment" class="form-control"  rows="5" :id="index"  type="text" :name="comment.input_name"  v-validate="'required'" v-model="comment.comment_desc"  >

                                                </textarea>
                                                <div class="input-group is-danger" style="color: red" role="alert">
                                                    {{ errors.first(comment.input_name) }}
                                                </div>



                                            </div>
                                        </div>
                                        <div class="form-actions text-center">
                                            <div class="line"></div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                    <a :href="detailUrl" class="btn btn-default w-100">閉じる</a>
                                                </div>
                                            </div>


                                        </div>
                                    </form>




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

<script>
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
                commentsModels: this.comments,
                flagShowLoader: false,

            };
        },
        created(){
            let messError = {
                custom: {
                },
            };
            this.commentsModels.forEach(function (ele) {
                messError.custom[ele.input_name] = {
                    required :  ele.title + "の評価を入力してください",

                }
            });

            this.$validator.localize("en", messError);

        },
        props: ['testResult', 'analyticList', 'testComment', 'disableComment', 'comments', 'updateUrl', 'detailUrl', 'answerDetailUrl', 'groupLessonGuideUrl'],
        mounted() {},
        methods: {
            register() {
                let that = this;
                let formData = new FormData();
                this.commentsModels.forEach(function (comment) {
                    formData.append(comment.input_name, comment.comment_desc);
                });

                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        this.$swal({
                            title: "登録した内容が学習者に送信されます。<br>よろしいですか。",
                            icon: "question",
                            confirmButtonText: "OK",
                            showCancelButton : true,
                            cancelButtonText : "キャンセル"
                        }).then(function (confirm) {

                            if (confirm.isConfirmed) {
                                that.flagShowLoader = true;
                                axios
                                    .post(that.updateUrl, formData, {
                                        header: {
                                            "Content-Type": "multipart/form-data",
                                        },
                                    })
                                    .then((res) => {
                                        that.$swal({
                                            title: "実力テスト評価が完了しました。",
                                            icon: "success",
                                            confirmButtonText: "OK",
                                        }).then(function (confirm) {
                                            that.flagShowLoader = false;
                                            window.location.href = that.detailUrl;

                                        });
                                        that.flagShowLoader = false;
                                    })
                                    .catch((err) => {

                                        switch (err.response.status) {
                                            case 400:
                                                var message = err.response.data.message;
                                                that.$swal({
                                                    title: message,
                                                    icon: "error",
                                                    customClass: 'text-nowrap',
                                                    confirmButtonText: "OK",
                                                }).then(function (confirm) {
                                                });

                                                that.flagShowLoader = false;
                                                break;
                                            case 500:
                                                that.$swal({
                                                    title: "失敗したデータを評価しました",
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



                        }).abort(function () {
                            that.flagShowLoader = false;
                        });
/*                        that.flagShowLoader = true;
                        axios
                            .post(that.updateUrl , formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                this.$swal({
                                    title: "実力テスト評価が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                    window.location.href = that.detailUrl;
                                });
                                that.flagShowLoader = false;
                            })
                            .catch((err) => {
                                switch (err.response.status) {
                                    case 400:
                                        var  message = err.response.data.message;
                                        this.$swal({
                                            title: message,
                                            icon: "error",
                                            customClass: 'text-nowrap',
                                            confirmButtonText: "OK",
                                        }).then(function (confirm) {});

                                        that.flagShowLoader = false;
                                        break;
                                    case 500:
                                        this.$swal({
                                            title: "失敗したデータを評価しました",
                                            icon: "error",
                                            confirmButtonText: "OK",
                                        }).then(function (confirm) {});

                                        that.flagShowLoader = false;
                                        break;
                                    default:
                                        break;
                                }
                            });*/
                    }
                });

            },

        },
    }
</script>
