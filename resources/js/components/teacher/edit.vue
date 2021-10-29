<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            講師情報編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                                    <div class="card-header">講師情報</div>
                                    <div class="card-body">


                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-md-right" for="displayOrder">表示順:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="displayOrder" type="number" name="displayOrder" @input="changeInput()" style="max-width: 100px" v-model="displayOrder" value="1" onKeyDown="return false" v-validate="'decimal|min_value:1|max_value:1000000000'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("displayOrder") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="teacherName">講師名:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="teacherName" type="text" name="teacherName" @input="changeInput()"  v-model="teacherName"  v-validate="'required|max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("teacherName") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="nickName">ニックネーム:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="nickName" type="text" name="nickName" @input="changeInput()"  v-model="nickName"  v-validate="'required|max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("nickName") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="mail">メールアドレス:
                                                <span class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input class="form-control" id="mail" type="text" name="mail" @input="changeInput()"  v-model="mail"  v-validate="'required|email_format|max:255'" />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("mail") }}
                                                </div>
                                                <div class="input-group is-danger" role="alert" v-if="errorsData.errors">
                                                    {{ errorsData.errors.mail[0]}}
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="timeZone">タイムゾーン:
                                            </label>
                                            <div class="col-md-6">
                                                <select name="timeZone" class="form-control valid" id="timeZone" v-model="timeZone" aria-invalid="false">
                                                    <option value="0" ></option>
                                                    <option v-for="tz in timeZones" :value="tz.id" :selected="(tz.timeZone_id == timeZone) ? true : false">
                                                    {{tz.timezone_name_native}}
                                                    </option>
                                                </select>
                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("timeZone") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" >固定/自由: </label>
                                            <div class="col-md-6">
                                                <div style="margin-top: 5px">
                                                    <label class="radio" for="is-free-teacher-0">
                                                        <input name="isFreeTeacher" id="is-free-teacher-0" value="0" type="radio" v-model="isFreeTeacher" :checked="(isFreeTeacher == 0) ? true : false ">
                                                        固定
                                                    </label>
                                                    &nbsp;
                                                    <label class="radio" for="is-free-teacher-1">
                                                        <input name="isFreeTeacher" value="1" id="is-free-teacher-1"  type="radio" v-model="isFreeTeacher" :checked="(isFreeTeacher == 1) ? true : false ">
                                                        自由
                                                    </label>
                                                    <div class="input-group is-danger" role="alert">
                                                        {{ errors.first("isFreeTeacher") }}
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 text-md-right col-form-label"> 性別: </label>
                                            <div class="col-md-6">
                                                <div style="margin-top: 5px">
                                                    <label class="radio" for="teacher-sex-0">
                                                        <input name="teacherSex" id="teacher-sex-0" value="0" type="radio" v-model="teacherSex" :checked="(teacherSex == 0) ? true : false ">
                                                        女性
                                                    </label>
                                                    &nbsp;
                                                    <label class="radio" for="teacher-sex-0">
                                                        <input name="teacherSex" value="1" id="teacher-sex-1"  type="radio" v-model="teacherSex" :checked="(teacherSex == 1) ? true : false " >
                                                        男性
                                                    </label>
                                                    <div class="input-group is-danger" role="alert">
                                                        {{ errors.first("teacherSex") }}
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right"> 誕生日: </label>

                                            <div class="col-md-6">
                                                <date-picker
                                                        v-model="teacherBirthday"
                                                        :format="'YYYY/MM/DD'"
                                                        type="date"
                                                ></date-picker>


                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("teacherBirthday") }}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="teacherUniversity"> 出身国: </label>

                                            <div class="col-md-6">
                                                <input class="form-control" id="teacherUniversity" type="text" name="teacherUniversity" @input="changeInput()"  v-model="teacherUniversity"  v-validate="'max:255'"  />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("teacherUniversity") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row 9">
                                            <label class="col-md-3 col-form-label text-md-right" for="teacherDepartment"> 居住地: </label>

                                            <div class="col-md-6">
                                                <input class="form-control" id="teacherDepartment" type="text" name="teacherDepartment" @input="changeInput()"  v-model="teacherDepartment"  v-validate="'max:255'"  />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("teacherDepartment") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="teacherHobby"> 英語対応: </label>

                                            <div class="col-md-6">
                                                <input class="form-control" id="teacherHobby" type="text" name="teacherHobby" @input="changeInput()"  v-model="teacherHobby"  v-validate="'max:255'"  />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("teacherHobby") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="teacherIntroduction"> 自己紹介: </label>

                                            <div class="col-md-6">
                                                            <textarea class="form-control" id="teacherIntroduction"  name="teacherIntroduction"
                                                                      @input="changeInput()"  v-model="teacherIntroduction">
                                                            </textarea>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("teacherIntroduction") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="introduceFromAdmin"> 管理者からの紹介: </label>

                                            <div class="col-md-6">
                                                            <textarea class="form-control" id="introduceFromAdmin"  name="teacherIntroduction"
                                                                      @input="changeInput()"  v-model="introduceFromAdmin">
                                                            </textarea>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("introduceFromAdmin") }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="teacherNote"> 管理者メモ: </label>

                                            <div class="col-md-6">
                                                            <textarea class="form-control" id="teacherNote"  name="teacherNote"
                                                                      @input="changeInput()"  v-model="teacherNote">
                                                            </textarea>

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("teacherNote") }}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-md-right" for="photoSavepath"> イメージ画像: </label>

                                            <div class="col-md-6">

                                                <input class="form-control" id="photoSavepath" type="text" name="photoSavepath" @input="changeInput()"  v-model="photoSavepath"  v-validate="'url'"  />

                                                <div class="input-group is-danger" role="alert">
                                                    {{ errors.first("photoSavepath") }}
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
                                                    <a :href="detailTeacherUrl" class="btn btn-default w-100">閉じる</a>
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
        <loader :flag-show="flagShowLoader"></loader>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";

    export default {
        created: function () {
            let messError = {
                custom: {
                    displayOrder : {
                        requirecustom: "表示順を入力してください",
                        min_value: "表示順は1～1000000000 を入力してください",
                        max_value: "表示順は1～1000000000 を入力してください"
                    },
                    teacherName: {
                        required: "講師名を入力してください",
                        max: "名前は255文字以内で入力してください。",
                    },
                    nickName: {
                        required: "ニックネームを入力してください",
                        max: "ニックネームは255文字以内で入力してください。",
                    },
                    mail: {
                        required: "メールアドレスを入力してください",
                        email_format: "メールアドレス形式は正しくありません。",
                        max: "メールアドレスは255文字以内で入力してください。",
                    },
                    teacherUniversity :{
                        max: "出身国は255文字以内で入力してください。",
                    },
                    teacherDepartment :{
                        max: "居住地は255文字以内で入力してください。",
                    },
                    teacherHobby : {
                        max: "英語対応は255文字以内で入力してください。"
                    },
                    photoSavepath : {
                        url: "イメージ画像をURL形で入力してください。"
                    }


                },
            };
            this.$validator.localize("en", messError);
        },
        components: {
            Loader,
        },
        data() {
            return {
                id : this.teacher.teacher_id,
                csrfToken: Laravel.csrfToken,
                teacherName: this.teacher.teacher_name,
                displayOrder: this.teacher.display_order,
                mail: this.teacher.teacher_email,
                nickName: this.teacher.teacher_nickname,
                isFreeTeacher : this.teacher.is_free_teacher,
                flagShowLoader: false,
                messageText: this.message,
                errorsData: {},
                timeZone : this.teacher.timezone_id ,
                teacherSex : this.teacher.teacher_sex,
                teacherBirthday :   this.teacher.teacher_birthday == null ? null : new Date(Date.parse(this.teacher.teacher_birthday)),
                teacherUniversity : this.teacher.teacher_university,
                teacherDepartment : this.teacher.teacher_department,
                teacherHobby : this.teacher.teacher_hobby,
                teacherIntroduction : this.teacher.teacher_introduction,
                introduceFromAdmin : this.teacher.introduce_from_admin,
                teacherNote : this.teacher.teacher_note,
                photoSavepath : this.teacher.photo_savepath

            };
        },
        props: ["listTeacherUrl", "timeZones", "updateUrl", 'teacher', 'deleteAction', 'detailTeacherUrl'],
        mounted() {},
        methods: {
            showAlert() {
                let that = this;
                this.$swal({
                    title: 'この講師を削除しますか？',
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
                                        window.location.href = that.detail;
                                    });
                            })
                            .catch(error => {
                                that.flagShowLoader = false;
                            });
                    }
                });
            },
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("teacherName", this.teacherName);
                formData.append("mail", this.mail);
                formData.append("displayOrder", this.displayOrder);
                formData.append("nickName", this.nickName);
                formData.append("timeZone", this.timeZone);
                formData.append("isFreeTeacher", this.isFreeTeacher);
                formData.append("teacherSex", this.teacherSex);
                formData.append("teacherBirthday", this.teacherBirthday == null ? null : this.teacherBirthday.toISOString());
                formData.append("teacherUniversity", this.teacherUniversity);
                formData.append("teacherDepartment", this.teacherDepartment);
                formData.append("teacherHobby", this.teacherHobby);
                formData.append("teacherIntroduction", this.teacherIntroduction);
                formData.append("introduceFromAdmin", this.introduceFromAdmin);
                formData.append("teacherNote", this.teacherNote);
                formData.append('_method', 'PUT');
                formData.append('id', this.id);
                formData.append('photoSavepath', this.photoSavepath);

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
                                    title: "講師編集が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    that.flagShowLoader = false;
                                });
                                that.flagShowLoader = false;
                                window.location.href = this.detailTeacherUrl;
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
