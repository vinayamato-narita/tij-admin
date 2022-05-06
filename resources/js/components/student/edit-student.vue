<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            学習者情報編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save('form1')"  data-vv-scope="form1">
                                    <div class="card-header">
                                        <h5 class="title-page">学習者詳細</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label style="margin-right: 20px">基本情報</label>
                                                <a data-toggle="modal" data-target="#student_change_password" href="" class="student-change-password" style="text-decoration: underline">+パスワード変更</a>
                                            </div>
                                            
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >学習者番号</label
                                                    >
                                                    <div class="col-md-9 pt-7">
                                                        {{ studentInfoEx.student_id }}
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >登録状態<span class="glyphicon glyphicon-star"
                                                            ></span
                                                        ></label
                                                    >
                                                    <div class="col-md-9">
                                                        <select
                                                            class="form-control"
                                                            name="is_tmp_entry"
                                                            v-model="studentInfoEx.is_tmp_entry"
                                                            v-validate="'required'"
                                                        >
                                                            <option :value="key" v-for="(value, key) in studentInfoEx.student_entry_types">
                                                                {{ value }}</option
                                                            >
                                                        </select>

                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.is_tmp_entry')"
                                                        >
                                                            {{ errors.first("form1.is_tmp_entry") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >名前<span class="glyphicon glyphicon-star"
                                                            ></span
                                                        ></label
                                                    >
                                                    <div class="col-md-4" style="width: 37%; flex: auto; max-width: unset;">
                                                        <input
                                                            placeholder="姓"
                                                            class="form-control"
                                                            name="student_first_name"
                                                            v-model="studentInfoEx.student_first_name"
                                                            v-validate="
                                                                'required|max:100'
                                                            "
                                                        />
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.student_first_name')"
                                                        >
                                                            {{ errors.first("form1.student_first_name") }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="width: 38%; flex: auto; max-width: unset;">
                                                        <input
                                                            placeholder="名"
                                                            class="form-control"
                                                            name="student_last_name"
                                                            v-model="studentInfoEx.student_last_name"
                                                            v-validate="
                                                                'required|max:100'
                                                            "
                                                        />
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.student_last_name')"
                                                        >
                                                            {{ errors.first("form1.student_last_name") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >フリガナ</label
                                                    >
                                                    <div class="col-md-4" style="width: 37%; flex: auto; max-width: unset;">
                                                        <input
                                                            placeholder="セイ"
                                                            class="form-control"
                                                            name="student_first_name_kata"
                                                            v-model="studentInfoEx.student_first_name_kata"
                                                            v-validate="
                                                                'isKana|max:100'
                                                            "
                                                        />
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.student_first_name_kata')"
                                                        >
                                                            {{ errors.first("form1.student_first_name_kata") }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="width: 38%; flex: auto; max-width: unset;">
                                                        <input
                                                            placeholder="メイ"
                                                            class="form-control"
                                                            name="student_last_name_kata"
                                                            v-model="studentInfoEx.student_last_name_kata"
                                                            v-validate="
                                                                'isKana|max:100'
                                                            "
                                                        />
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.student_last_name_kata')"
                                                        >
                                                            {{ errors.first("form1.student_last_name_kata") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >ニックネーム</label
                                                    >
                                                    <div class="col-md-9">
                                                        <input
                                                            class="form-control"
                                                            name="student_nickname"
                                                            v-model="studentInfoEx.student_nickname"
                                                            v-validate="
                                                                'max:16'
                                                            "
                                                        />
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.student_nickname')"
                                                        >
                                                            {{ errors.first("form1.student_nickname") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >メールアドレス<span class="glyphicon glyphicon-star"
                                                            ></span
                                                        ></label
                                                    >
                                                    <div class="col-md-9">
                                                        <input
                                                            class="form-control"
                                                            name="student_email"
                                                            v-model="studentInfoEx.student_email"
                                                            v-validate="
                                                                'required|email_format|max:255'
                                                            "
                                                        />
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.student_email')"
                                                        >
                                                            {{ errors.first("form1.student_email") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row" v-if="studentInfoEx.is_lms_user != studentInfoExlms_user">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >法人名</label
                                                    >
                                                    <div class="col-md-9">
                                                        <input
                                                            class="form-control"
                                                            name="company_name"
                                                            v-model="studentInfoEx.company_name"
                                                            v-validate="
                                                                'max:255'
                                                            "
                                                        />
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.company_name')"
                                                        >
                                                            {{ errors.first("form1.company_name") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >自己紹介</label
                                                    >
                                                    <div class="col-md-9">
                                                        <textarea
                                                            class="form-control"
                                                            rows = "5"
                                                            name="student_introduction"
                                                            v-model="studentInfoEx.student_introduction"
                                                            v-validate="
                                                                'max:20000'
                                                            "
                                                        ></textarea>
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.student_introduction')"
                                                        >
                                                            {{ errors.first("form1.student_introduction") }}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >電話番号</label
                                                    >
                                                    <div class="col-md-9">
                                                        <input
                                                            class="form-control"
                                                            name="student_home_tel"
                                                            v-model="studentInfoEx.student_home_tel"
                                                            v-validate="
                                                                'isTelephone|max:20'
                                                            "
                                                        />
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.student_home_tel')"
                                                        >
                                                            {{ errors.first("form1.student_home_tel") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >国</label
                                                    >
                                                    <div class="col-md-9">
                                                        <select
                                                            class="form-control"
                                                            name="lang_type"
                                                            v-model="studentInfoEx.country_id"
                                                        >   
                                                            <option :value="key" v-for="(value, key) in studentInfoEx.countries">
                                                                {{ value }}</option
                                                            >
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >市</label
                                                    >
                                                    <div class="col-md-9">
                                                        <input
                                                            class="form-control"
                                                            name="city"
                                                            v-model="studentInfoEx.city"
                                                            v-validate="
                                                                'max:100'
                                                            "
                                                        />
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.city')"
                                                        >
                                                            {{ errors.first("form1.city") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >表示言語</label
                                                    >
                                                    <div class="col-md-9">
                                                        <select
                                                            class="form-control"
                                                            name="lang_type"
                                                            v-model="studentInfoEx.lang_type"
                                                        >   
                                                            <option :value="key" v-for="(value, key) in studentInfoEx.lang_types">
                                                                {{ value }}</option
                                                            >
                                                        </select>

                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.lang_type')"
                                                        >
                                                            {{ errors.first("form1.lang_type") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >個人／法人</label
                                                    >
                                                    <div class="col-md-9 pt-7">
                                                        <label style="margin-right: 20px"><input
                                                            type="radio"
                                                            name="is_lms_user"
                                                            v-model="studentInfoEx.is_lms_user"
                                                            value="0"
                                                        /> 個人</label>
                                                        <label><input
                                                            type="radio"
                                                            name="is_lms_user"
                                                            v-model="studentInfoEx.is_lms_user"
                                                            value="1"
                                                        /> 法人</label>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >督促メール</label
                                                    >
                                                    <div class="col-md-9 pt-7">
                                                        <label style="margin-right: 20px"><input
                                                            type="radio"
                                                            name="is_sending_dm"
                                                            v-model="studentInfoEx.is_sending_dm"
                                                            value="1"
                                                        /> 送付</label>
                                                        <label><input
                                                            type="radio"
                                                            name="is_sending_dm"
                                                            v-model="studentInfoEx.is_sending_dm"
                                                            value="0"
                                                        /> 送付しない</label>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >DM</label
                                                    >
                                                    <div class="col-md-9 pt-7">
                                                        <label style="margin-right: 20px"><input
                                                            type="radio"
                                                            name="direct_mail_flag"
                                                            v-model="studentInfoEx.direct_mail_flag"
                                                            value="1"
                                                        /> 可</label>
                                                        <label><input
                                                            type="radio"
                                                            name="direct_mail_flag"
                                                            v-model="studentInfoEx.direct_mail_flag"
                                                            value="0"
                                                        /> 不可</label>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >居住地</label
                                                    >
                                                    <div class="col-md-9 pt-7">
                                                        <label style="margin-right: 20px"><input
                                                            type="radio"
                                                            name="in_japan_flag"
                                                            v-model="studentInfoEx.in_japan_flag"
                                                            value="1"
                                                        /> 日本</label>
                                                        <label><input
                                                            type="radio"
                                                            name="in_japan_flag"
                                                            v-model="studentInfoEx.in_japan_flag"
                                                            value="0"
                                                        /> 海外</label>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >タイムゾーン</label
                                                    >
                                                    <div class="col-md-9">
                                                        <select
                                                            class="form-control"
                                                            name="timezone_id"
                                                            v-model="studentInfoEx.timezone_id"
                                                        >   
                                                            <option :value="key" v-for="(value, key) in studentInfoEx.time_zones">
                                                                {{ value }}</option
                                                            >
                                                        </select>

                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.timezone_id')"
                                                        >
                                                            {{ errors.first("form1.timezone_id") }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label
                                                        class="col-md-3 col-form-label text-md-right"
                                                        for="text-input"
                                                        >連絡事項</label
                                                    >
                                                    <div class="col-md-9">
                                                        <textarea
                                                            class="form-control"
                                                            rows = "5"
                                                            name="student_comment_text"
                                                            v-model="studentInfoEx.student_comment_text"
                                                            v-validate="
                                                                'max:20000'
                                                            "
                                                        ></textarea>
                                                        <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('form1.student_comment_text')"
                                                        >
                                                            {{ errors.first("form1.student_comment_text") }}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div v-if="studentInfoEx.is_lms_user == studentInfoEx.lms_user">
                                            <div class="form-group row">
                                                <div class="col-md-12" style="border-bottom: #d8dbe0 1px solid; border-top: #d8dbe0 1px solid;">
                                                    <label style="margin-left: 20px; margin-top: 10px">企業情報</label>
                                                </div>
                                            </div>
                                            <div class="form-group row" v-for="lmsProject in studentInfoEx.lms_project_students">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-3 col-form-label text-md-right"
                                                            for="text-input"
                                                            >請求先</label
                                                        >
                                                        <div class="col-md-9 pt-7">
                                                            {{ lmsProject.corporation_flag == 0 ? "個人請求" : "法人請求" }}
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-3 col-form-label text-md-right"
                                                            for="text-input"
                                                            >継続</label
                                                        >
                                                        <div class="col-md-9 pt-7">
                                                            {{ lmsProject.buy_course_continue == 0 ? "なし" : "あり" }}
                                                        </div>
                                                    </div>
                                                
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-3 col-form-label text-md-right"
                                                            for="text-input"
                                                            >購入</label
                                                        >
                                                        <div class="col-md-9 pt-7">
                                                            <label style="margin-right: 20px"><input
                                                                type="radio"
                                                                name="buy_course_flag"
                                                                v-model="lmsProject.buy_course_flag"
                                                                value="0"
                                                            /> 不可</label>
                                                            <label><input
                                                                type="radio"
                                                                name="buy_course_flag"
                                                                v-model="lmsProject.buy_course_flag"
                                                                value="1"
                                                            /> 可</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-3 col-form-label text-md-right"
                                                            for="text-input"
                                                            >部署名</label
                                                        >
                                                        <div class="col-md-6 pt-7">
                                                            <input
                                                                class="form-control"
                                                                name="department_name"
                                                                v-model="lmsProject.department_name"
                                                                v-validate="
                                                                    'max:100'
                                                                "
                                                            />
                                                            <div
                                                                class="input-group is-danger"
                                                                role="alert"
                                                                v-if="errors.has('form1.department_name')"
                                                            >
                                                                {{ errors.first("form1.department_name") }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-3 col-form-label text-md-right"
                                                            for="text-input"
                                                            >社員番号</label
                                                        >
                                                        <div class="col-md-6 pt-7">
                                                            <input
                                                                class="form-control"
                                                                name="employee_number"
                                                                v-model="lmsProject.employee_number"
                                                                v-validate="
                                                                    'max:100'
                                                                "
                                                            />
                                                            <div
                                                                class="input-group is-danger"
                                                                role="alert"
                                                                v-if="errors.has('form1.employee_number')"
                                                            >
                                                                {{ errors.first("form1.employee_number") }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label
                                                            class="col-md-3 col-form-label text-md-right"
                                                            for="text-input"
                                                            >所属番号</label
                                                        >
                                                        <div class="col-md-6 pt-7">
                                                            <input
                                                                class="form-control"
                                                                name="department_number"
                                                                v-model="lmsProject.department_number"
                                                                v-validate="
                                                                    'max:100'
                                                                "
                                                            />
                                                            <div
                                                                class="input-group is-danger"
                                                                role="alert"
                                                                v-if="errors.has('form1.department_number')"
                                                            >
                                                                {{ errors.first("form1.department_number") }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        

                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center display-flex">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <btn-delete :delete-action="deleteAction"
                                                            :message-confirm="messageConfirm" 
                                                            :url-redirect="urlStudentList"
                                                            ></btn-delete>
                                                <a :href="urlStudentList" class="btn btn-default w-100">閉じる</a>
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

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="student_change_password" data-backdrop="static">
            <div class="modal-dialog modal-sm changePassStudentModal">
                <div class="modal-content">
                    <div class="modal-header" style="display: block;">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" ref="closeModel">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            パスワード変更
                        </h4>
                    </div>
                    <form class="basic-form" @submit.prevent="saveChange('form2')"  data-vv-scope="form2">
                        <div class="modal-body" id="lesson_list_modal">
                            <div class="tableContainer">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-md-right" for="password">
                                        新しいパスワード <span class="required glyphicon glyphicon-star"></span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input
                                            type = "password"
                                            class="form-control"
                                            name="password"
                                            v-model="password"
                                            ref="password"
                                            v-validate="
                                                'required|password_rule|min:8|max:16'
                                            "
                                        />
                                        <div
                                            class="input-group is-danger"
                                            role="alert"
                                            v-if="errors.has('form2.password')"
                                        >
                                            {{ errors.first("form2.password") }}
                                        </div>                                  
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-md-right" for="password_confirm">
                                        確認 <span class="required glyphicon glyphicon-star"></span></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input
                                            type = "password"
                                            class="form-control"
                                            name="password_confirm"
                                            v-model="password_confirm"
                                            v-validate="
                                                'required|confirmed:password'
                                            "
                                        />
                                        <div
                                            class="input-group is-danger"
                                            role="alert"
                                            v-if="errors.has('form2.password_confirm')"
                                        >
                                            {{ errors.first("form2.password_confirm") }}
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center; display: block;">
                            <button type="submit" class="btn btn-success w-100 save-teacher-lesson">
                                変更する</button>
                            <button type="button" data-dismiss="modal" class="btn btn-default w-100">
                                キャンセル</button>
                        </div>
                    </form>

                    <div class="form-group text-center">
                        <div class="col-sm-offset-2 col-md-offset-2">
                             ※パスワード変更を実施した場合、学習者宛にリマインドメールが送付されます
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import axios from "axios";
import Loader from "./../common/loader.vue";
import BtnDelete from "./../common/btn-delete.vue";

export default {
    created: function() {
        let messError = {
            custom: {
                student_first_name: {
                    required: "姓を入力してください",
                    max: "姓は100文字以内で入力してください",
                },
                student_last_name: {
                    required: "名を入力してください",
                    max: "名は100文字以内で入力してください",
                },
                student_first_name_kata: {
                    isKana: "カタカナ又はローマ字で入力してください",
                    max: "セイは100文字以内で入力してください",
                },
                student_last_name_kata: {
                    isKana: "カタカナ又はローマ字で入力してください",
                    max: "メイは100文字以内で入力してください",
                },
                student_nickname: {
                    login_id: "ニックネームは正しく入力してください",
                    max: "ニックネームは16文字以内で入力してください",
                },
                student_email: {
                    required: "メールアドレスを入力してください",
                    email_format: "メールアドレスを正確に入力してください",
                    max: "メールアドレスは255文字以内で入力してください",
                },
                company_name: {
                    max: "法人名は255文字以内で入力してください",
                },
                student_introduction: {
                    max: "自己紹介は20000文字以内で入力してください",
                },
                student_home_tel: {
                    max: "電話番号は20文字以内で入力してください",
                    isTelephone: "電話番号は半角数字とハイフンのみ入力してください",
                },
                postcode: {
                    max: "郵便番号は10文字以内で入力してください",
                    postcode: "郵便番号を正確に入力してください",
                },
                student_address: {
                    max: "住所１は50文字以内で入力してください",
                },
                student_address1: {
                    max: "住所２は50文字以内で入力してください",
                },
                student_address2: {
                    max: "住所３は50文字以内で入力してください",
                },
                student_address3: {
                    max: "住所４は50文字以内で入力してください",
                },
                student_comment_text: {
                    max: "連絡事項は20000文字以内で入力してください",
                },
                password: {
                    required: "パスワードを入力してください",
                    min: "パスワードは8文字以上で入力してください",
                    max: "パスワードは16文字以内で入力してください",
                    password_rule: "パスワードに使用できるのは、a～z、A～Z、0～9 の英数字と、記号です",
                },
                password_confirm: {
                    required: "パスワード（確認）を入力してください",
                    confirmed: "パスワードが一致しません。もう一度入力してください",
                },
                department_name: {
                    max: '部署名は100文字以内で入力してください'
                },
                employee_number: {
                    max: '社員番号は100文字以内で入力してください'
                },
                department_number: {
                    max: '所属番号は100文字以内で入力してください'
                },
                city: {
                    max: '市は100文字以内で入力してください'
                },
            }
        };
        this.$validator.localize("en", messError);
    },
    components: {
        Loader,
        BtnDelete
    },
    data() {
        return {
            flagShowLoader: false,
            studentInfoEx: this.studentInfo,
            password: "",
            password_confirm: "",
        };
    },
    props: ["urlAction", "urlStudentList", "studentInfo", 'deleteAction', 'messageConfirm', 'urlUpdatePassword'],
    mounted() {},
    methods: {
        save(a) {
            let that = this;
            this.$validator
                .validateAll(a)
                .then(valid => {
                    if (valid || (this.errors.items.length == 2 && this.errors.has('password') && this.errors.has('password_confirm'))) {
                        that.flagShowLoader = true;
                        that.submit();
                    }
                })
                .catch(function(e) {});
        },
        submit(e) {
            let that = this;
            axios
                .put(that.urlAction, that.studentInfoEx)
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "学習者情報編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location = this.urlStudentList;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
        saveChange(a) {
            let that = this;
            this.$validator
                .validateAll(a)
                .then(valid => {
                    if (valid) {
                        that.flagShowLoader = true;
                        that.submitChangePassword();
                    }
                })
                .catch(function(e) {});
        },
        submitChangePassword(e) {
            let that = this;
            axios
                .post(that.urlUpdatePassword, {
                    _token: Laravel.csrfToken,
                    password: that.password,
                    password_confirm: that.password_confirm,
                    id: that.studentInfoEx.student_id,
                })
                .then(response => {
                    that.flagShowLoader = false;
                    that.$refs.closeModel.click();
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "パスワード変更が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            location.reload();
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
    }
};
</script>
