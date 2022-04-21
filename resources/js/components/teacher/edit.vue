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
                                <form
                                    class="form-horizontal "
                                    style="width: 100%"
                                    method="POST"
                                    @submit.prevent="register"
                                    autocomplete="off"
                                >
                                    <div class="card-header">講師情報</div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="displayOrder"
                                                >表示順:
                                                <span
                                                    class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="displayOrder"
                                                    type="number"
                                                    name="displayOrder"
                                                    @input="changeInput()"
                                                    style="max-width: 100px"
                                                    v-model="displayOrder"
                                                    value="1"
                                                    v-validate="
                                                        'decimal|min_value:1|max_value:1000000000'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "displayOrder"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                         <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="teacherCode"
                                                >講師コード:
                                                <span
                                                    class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="teacherCode"
                                                    type="text"
                                                    name="teacherCode"
                                                    @input="changeInput()"
                                                    v-model="teacherCode"
                                                    v-validate="
                                                        'required|max:10'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "teacherCode"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="teacherName"
                                                >講師名:
                                                <span
                                                    class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="teacherName"
                                                    type="text"
                                                    name="teacherName"
                                                    @input="changeInput()"
                                                    v-model="teacherName"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "teacherName"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="nickName"
                                                >ニックネーム:
                                                <span
                                                    class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="nickName"
                                                    type="text"
                                                    name="nickName"
                                                    @input="changeInput()"
                                                    v-model="nickName"
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first("nickName")
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="mail"
                                                >メールアドレス:
                                                <span
                                                    class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>
                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="mail"
                                                    type="text"
                                                    name="mail"
                                                    @input="changeInput()"
                                                    v-model="mail"
                                                    v-validate="
                                                        'required|email_format|max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{ errors.first("mail") }}
                                                </div>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                    v-if="errorsData.errors"
                                                >
                                                    {{
                                                        errorsData.errors
                                                            .mail[0]
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="timeZone"
                                                >タイムゾーン:
                                            </label>
                                            <div class="col-md-6">
                                                <select
                                                    name="timeZone"
                                                    class="form-control valid"
                                                    id="timeZone"
                                                    v-model="timeZone"
                                                    aria-invalid="false"
                                                >
                                                    <option value="0"></option>
                                                    <option
                                                        v-for="tz in timeZones"
                                                        :value="tz.timezone_id"
                                                        :selected="
                                                            tz.timeZone_id ==
                                                            timeZone
                                                                ? true
                                                                : false
                                                        "
                                                    >
                                                        {{
                                                            tz.timezone_name_native
                                                        }}
                                                    </option>
                                                </select>
                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first("timeZone")
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                >固定/自由:
                                            </label>
                                            <div class="col-md-6">
                                                <div style="margin-top: 5px">
                                                    <label
                                                        class="radio"
                                                        for="is-free-teacher-0"
                                                    >
                                                        <input
                                                            name="isFreeTeacher"
                                                            id="is-free-teacher-0"
                                                            value="0"
                                                            type="radio"
                                                            v-model="
                                                                isFreeTeacher
                                                            "
                                                            :checked="
                                                                isFreeTeacher ==
                                                                0
                                                                    ? true
                                                                    : false
                                                            "
                                                        />
                                                        固定
                                                    </label>
                                                    &nbsp;
                                                    <label
                                                        class="radio"
                                                        for="is-free-teacher-1"
                                                    >
                                                        <input
                                                            name="isFreeTeacher"
                                                            value="1"
                                                            id="is-free-teacher-1"
                                                            type="radio"
                                                            v-model="
                                                                isFreeTeacher
                                                            "
                                                            :checked="
                                                                isFreeTeacher ==
                                                                1
                                                                    ? true
                                                                    : false
                                                            "
                                                        />
                                                        自由
                                                    </label>
                                                    <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                    >
                                                        {{
                                                            errors.first(
                                                                "isFreeTeacher"
                                                            )
                                                        }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 text-md-right col-form-label"
                                            >
                                                性別:
                                            </label>
                                            <div class="col-md-6">
                                                <div style="margin-top: 5px">
                                                    <label
                                                        class="radio"
                                                        for="teacher-sex-0"
                                                    >
                                                        <input
                                                            name="teacherSex"
                                                            id="teacher-sex-0"
                                                            value="0"
                                                            type="radio"
                                                            v-model="teacherSex"
                                                            :checked="
                                                                teacherSex == 0
                                                                    ? true
                                                                    : false
                                                            "
                                                        />
                                                        女性
                                                    </label>
                                                    &nbsp;
                                                    <label
                                                        class="radio"
                                                        for="teacher-sex-0"
                                                    >
                                                        <input
                                                            name="teacherSex"
                                                            value="1"
                                                            id="teacher-sex-1"
                                                            type="radio"
                                                            v-model="teacherSex"
                                                            :checked="
                                                                teacherSex == 1
                                                                    ? true
                                                                    : false
                                                            "
                                                        />
                                                        男性
                                                    </label>
                                                    <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                    >
                                                        {{
                                                            errors.first(
                                                                "teacherSex"
                                                            )
                                                        }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                            >
                                                誕生日:
                                            </label>

                                            <div class="col-md-6">
                                                <date-picker
                                                    v-model="teacherBirthday"
                                                    :format="'YYYY/MM/DD'"
                                                    type="date"
                                                ></date-picker>

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "teacherBirthday"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="teacherUniversity"
                                            >
                                                出身国:
                                            </label>

                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="teacherUniversity"
                                                    type="text"
                                                    name="teacherUniversity"
                                                    @input="changeInput()"
                                                    v-model="teacherUniversity"
                                                    v-validate="'max:255'"
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "teacherUniversity"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row 9">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="teacherDepartment"
                                            >
                                                居住地:
                                            </label>

                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="teacherDepartment"
                                                    type="text"
                                                    name="teacherDepartment"
                                                    @input="changeInput()"
                                                    v-model="teacherDepartment"
                                                    v-validate="'max:255'"
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "teacherDepartment"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="teacherHobby"
                                            >
                                                英語対応:
                                            </label>

                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="teacherHobby"
                                                    type="text"
                                                    name="teacherHobby"
                                                    @input="changeInput()"
                                                    v-model="teacherHobby"
                                                    v-validate="'max:255'"
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "teacherHobby"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="teacherIntroduction"
                                            >
                                                自己紹介:
                                            </label>

                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    id="teacherIntroduction"
                                                    name="teacherIntroduction"
                                                    @input="changeInput()"
                                                    v-model="
                                                        teacherIntroduction
                                                    "
                                                >
                                                </textarea>

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "teacherIntroduction"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="introduceFromAdmin"
                                            >
                                                管理者からの紹介:
                                            </label>

                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    id="introduceFromAdmin"
                                                    name="teacherIntroduction"
                                                    @input="changeInput()"
                                                    v-model="introduceFromAdmin"
                                                >
                                                </textarea>

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "introduceFromAdmin"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="teacherNote"
                                            >
                                                管理者メモ:
                                            </label>

                                            <div class="col-md-6">
                                                <textarea
                                                    class="form-control"
                                                    id="teacherNote"
                                                    name="teacherNote"
                                                    @input="changeInput()"
                                                    v-model="teacherNote"
                                                >
                                                </textarea>

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "teacherNote"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="zoomPersonalMeetingId"
                                            >
                                                固定Zoomミーティング:
                                                <span
                                                    class="glyphicon glyphicon-star"
                                                ></span>
                                            </label>

                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="zoomPersonalMeetingId"
                                                    type="text"
                                                    name="zoomPersonalMeetingId"
                                                    @input="changeInput()"
                                                    v-model="
                                                        zoomPersonalMeetingId
                                                    "
                                                    v-validate="
                                                        'required|max:255'
                                                    "
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "zoomPersonalMeetingId"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="zoomPassword"
                                            >
                                                Zoomパスコード:
                                            </label>

                                            <div class="col-md-6">
                                                <input
                                                    class="form-control"
                                                    id="zoomPassword"
                                                    type="text"
                                                    name="zoomPassword"
                                                    @input="changeInput()"
                                                    v-model="zoomPassword"
                                                    v-validate="'max:50'"
                                                />

                                                <div
                                                    class="input-group is-danger"
                                                    role="alert"
                                                >
                                                    {{
                                                        errors.first(
                                                            "zoomPassword"
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                            >
                                                特徴:
                                            </label>
                                            <div class="col-md-6">
                                                <div
                                                    class="d-flex flex-wrap"
                                                    style="margin-top: 5px"
                                                >
                                                    <label
                                                        class="form-checkbox mr-2"
                                                        ><input
                                                            type="checkbox"
                                                            v-model="
                                                                teacherFeature1
                                                            "
                                                            name="teacherFeature1"
                                                        />&nbsp;英語が話せる日本人講師</label
                                                    >
                                                    <label
                                                        class="form-checkbox mr-2"
                                                        ><input
                                                            type="checkbox"
                                                            v-model="
                                                                teacherFeature2
                                                            "
                                                            name="teacherFeature2"
                                                        />&nbsp;子供向け</label
                                                    >
                                                    <label
                                                        class="form-checkbox mr-2"
                                                        ><input
                                                            type="checkbox"
                                                            v-model="
                                                                teacherFeature3
                                                            "
                                                            name="teacherFeature3"
                                                        />&nbsp;講師歴3年以上</label
                                                    >
                                                    <label class="form-checkbox"
                                                        ><input
                                                            type="checkbox"
                                                            v-model="
                                                                teacherFeature4
                                                            "
                                                            name="teacherFeature4"
                                                        />&nbsp;日本語能力試験対策</label
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="studentNewFile"
                                            >
                                                プロフィール画像:
                                            </label>

                                            <div class="col-md-6">
                                                <button
                                                    type="button"
                                                    v-on:click="newFile"
                                                    class="btn btn-primary  mr-2"
                                                >
                                                    新規ファイル追加
                                                </button>
                                                <input
                                                    type="file"
                                                    name="studentNewFile"
                                                    id="studentNewFile"
                                                    ref="studentNewFile"
                                                    v-on:change="changeFile"
                                                    class="hidden"
                                                />
                                                <span class="text-nowrap">
                                                    {{ teacherFileName }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="videoNewFile"
                                            >
                                                動画ファイルの登録:
                                            </label>

                                            <div class="col-md-6">
                                                <button
                                                    type="button"
                                                    v-on:click="newVideo"
                                                    class="btn btn-primary  mr-2"
                                                >
                                                    新規ファイル追加
                                                </button>
                                                <input
                                                    type="file"
                                                    name="studentNewFile"
                                                    id="videoNewFile"
                                                    ref="videoNewFile"
                                                    v-on:change="changeVideo"
                                                    class="hidden"
                                                    accept="video/mp4,video/x-m4v,video/*"
                                                />
                                                <span class="text-nowrap">
                                                    {{ teacherVideoName }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-actions text-center">
                                            <div class="line"></div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <button
                                                        type="submit"
                                                        class="btn btn-primary w-100 mr-2"
                                                    >
                                                        登録
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger w-100 mr-2"
                                                        v-on:click="showAlert"
                                                    >
                                                        削除
                                                    </button>
                                                    <a
                                                        :href="detailTeacherUrl"
                                                        class="btn btn-default w-100"
                                                        >閉じる</a
                                                    >
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
import axios from "axios";
import Loader from "./../../components/common/loader";

export default {
    created: function() {
        let messError = {
            custom: {
                displayOrder: {
                    requirecustom: "表示順を入力してください",
                    min_value: "表示順は1～1000000000 を入力してください",
                    max_value: "表示順は1～1000000000 を入力してください"
                },
                teacherName: {
                    required: "講師名を入力してください",
                    max: "名前は255文字以内で入力してください。"
                },
                 teacherCode: {
                    required: "インストラクターコードを入力してください。",
                    max: "名前は10文字以内で入力してください。"
                },
                nickName: {
                    required: "ニックネームを入力してください",
                    max: "ニックネームは255文字以内で入力してください。"
                },
                mail: {
                    required: "メールアドレスを入力してください",
                    email_format: "メールアドレス形式は正しくありません。",
                    max: "メールアドレスは255文字以内で入力してください。"
                },
                teacherUniversity: {
                    max: "出身国は255文字以内で入力してください。"
                },
                teacherDepartment: {
                    max: "居住地は255文字以内で入力してください。"
                },
                teacherHobby: {
                    max: "英語対応は255文字以内で入力してください。"
                },
                zoomPersonalMeetingId: {
                    required: "固定Zoomミーティングを入力してください",
                    max: "固定Zoomミーティングは255文字以内で入力してください。"
                },
                zoomPassword: {
                    max: "パスワードは50文字以内で入力してください。"
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
            id: this.teacher.teacher_id,
            csrfToken: Laravel.csrfToken,
            teacherName: this.teacher.teacher_name,
            teacherCode:this.teacher.teacher_code,
            displayOrder: this.teacher.display_order,
            mail: this.teacher.teacher_email,
            nickName: this.teacher.teacher_nickname,
            isFreeTeacher: this.teacher.is_free_teacher,
            flagShowLoader: false,
            messageText: this.message,
            errorsData: {},
            timeZone: this.teacher.timezone_id,
            teacherSex: this.teacher.teacher_sex,
            teacherBirthday:
                this.teacher.teacher_birthday == null
                    ? null
                    : new Date(Date.parse(this.teacher.teacher_birthday)),
            teacherUniversity: this.teacher.teacher_university,
            teacherDepartment: this.teacher.teacher_department,
            teacherHobby: this.teacher.teacher_hobby,
            teacherIntroduction: this.teacher.teacher_introduction,
            introduceFromAdmin: this.teacher.introduce_from_admin ?? "",
            teacherNote: this.teacher.teacher_note,
            zoomPersonalMeetingId: this.teacher.zoom_personal_meeting_id,
            zoomPassword: this.teacher.zoom_password ?? "",
            teacherFeature1: this.teacher.teacher_feature1 == 1 ? true : false,
            teacherFeature2: this.teacher.teacher_feature2 == 1 ? true : false,
            teacherFeature3: this.teacher.teacher_feature3 == 1 ? true : false,
            teacherFeature4: this.teacher.teacher_feature4 == 1 ? true : false,
            teacherFileSelected: null,
            teacherFileName:
                this.teacher.teacherFileName == null
                    ? ""
                    : this.teacher.teacherFileName,
            teacherFileNameAttached: "",
            teacherVideoSelected:null,
            teacherVideoName: "",
        };
    },
    props: [
        "listTeacherUrl",
        "timeZones",
        "updateUrl",
        "teacher",
        "deleteAction",
        "detailTeacherUrl"
    ],
    mounted() {},
    methods: {
        showAlert() {
            let that = this;
            this.$swal({
                title: "この講師を削除しますか？",
                icon: "warning",
                confirmButtonText: "削除する",
                cancelButtonText: "閉じる",
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    that.flagShowLoader = true;
                    $(".loading-div").removeClass("hidden");
                    axios
                        .delete(that.deleteAction, {
                            _token: Laravel.csrfToken
                        })
                        .then(function(response) {
                            that.flagShowLoader = false;
                            that.$swal({
                                title: response.data.message,
                                icon: "success",
                                confirmButtonText: "閉じる"
                            }).then(function() {
                                window.location.href = that.listTeacherUrl;
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
            formData.append("teacherCode", this.teacherCode);
            formData.append("mail", this.mail);
            formData.append("displayOrder", this.displayOrder);
            formData.append("nickName", this.nickName);
            formData.append("timeZone", this.timeZone);
            formData.append("isFreeTeacher", this.isFreeTeacher);
            formData.append("teacherSex", this.teacherSex);
            formData.append(
                "teacherBirthday",
                this.teacherBirthday == null
                    ? null
                    : this.teacherBirthday.toISOString()
            );
            formData.append("teacherUniversity", this.teacherUniversity);
            formData.append("teacherDepartment", this.teacherDepartment);
            formData.append("teacherHobby", this.teacherHobby);
            formData.append("teacherIntroduction", this.teacherIntroduction);
            formData.append("introduceFromAdmin", this.introduceFromAdmin);
            formData.append("teacherNote", this.teacherNote);
            formData.append("_method", "PUT");
            formData.append("id", this.id);
            formData.append(
                "zoomPersonalMeetingId",
                this.zoomPersonalMeetingId
            );
            formData.append("zoomPassword", this.zoomPassword);
            formData.append("teacherFeature1", this.teacherFeature1 ? 1 : 0);
            formData.append("teacherFeature2", this.teacherFeature2 ? 1 : 0);
            formData.append("teacherFeature3", this.teacherFeature3 ? 1 : 0);
            formData.append("teacherFeature4", this.teacherFeature4 ? 1 : 0);
            if (this.teacherFileSelected)
                formData.append(
                    "teacherFileSelected",
                    this.teacherFileSelected
                );
            if (this.teacherVideoSelected)
                formData.append(
                    "teacherVideoSelected",
                    this.teacherVideoSelected
                );
            console.log(formData.get("teacherFileSelected"))
            this.$validator.validateAll().then(valid => {
                if (valid) {
                    that.flagShowLoader = true;
                    axios
                        .post(that.updateUrl, formData, {
                            header: {
                                "Content-Type": "multipart/form-data"
                            }
                        })
                        .then(res => {
                            this.$swal({
                                title: "講師編集が完了しました。",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(function(confirm) {
                                that.flagShowLoader = false;
                                window.location.href = that.detailTeacherUrl;
                            });
                            that.flagShowLoader = false;
                        })
                        .catch(err => {
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
                                        confirmButtonText: "OK"
                                    }).then(function(confirm) {});
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
        newFile() {
            this.$refs.studentNewFile.click();
        },
         newVideo() {
            this.$refs.videoNewFile.click();
        },
          changeVideo(e) {
            this.studentFileId = null;
            this.teacherFileNameAttached = "";
            this.teacherVideoSelected = e.target.files[0];
            this.teacherVideoName = e.target.files[0].name;
        },
        changeFile(e) {
            this.studentFileId = null;
            this.teacherFileNameAttached = "";
            this.teacherFileSelected = e.target.files[0];
            this.teacherFileName = e.target.files[0].name;
        },
        show(modalName) {
            this.$modal.show(modalName, {
                fileType: this.fileType,
                fileId: this.studentFileId,
                handlers: {
                    sendFileId: (...args) => {
                        this.studentFileId = args[0].fileId;
                        this.teacherFileNameAttached = args[0].selectedFileName;
                        this.teacherFileSelected = null;
                        this.teacherFileName = null;
                    }
                }
            });
        }
    }
};
</script>
