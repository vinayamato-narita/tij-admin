<template>
    <form
        method="POST"
        ref="importUsers"
        action=""
        enctype="multipart/form-data"
    >
        <input type="hidden" :value="csrfToken" name="_token" />
        <div class="c-body">
            <main class="c-main pt-0">
                <div class="container-fluid">
                    <div class="page-heading">
                        <div class="page-heading-left">
                            <h5>
                                法人ユーザ登録
                            </h5>
                        </div>
                    </div>
                    <div>
                        <div class="form-group row">
                            <div class="col-sm-4 offset-md-2">
                                <input
                                    name="file_name"
                                    readonly="readonly"
                                    placeholder="ファイルを選択してください"
                                    id="pathFile"
                                    @click="$refs.file.click()"
                                    class="form-control input-large cursor-point"
                                    style="background: white;"
                                    type="text"
                                    v-model="nameFile"
                                />
                                <input
                                    type="file"
                                    name="file"
                                    class="hidden"
                                    ref="file"
                                    @change="onFileChange"
                                    value=""
                                    v-validate="'required'"
                                    accept=".xlsx"
                                />
                            </div>
                            <div class="col-sm-6 text-left">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-w-100"
                                    @click="$refs.file.click()"
                                >
                                    参照
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-primary btn-w-100 ml-2 mr-2"
                                    @click="importFile"
                                >
                                    読込
                                </button>
                                <a
                                    href="/files/import_format/student_import.xlsx"
                                    class="btn btn-default"
                                    >フォーマットファイル</a
                                >
                            </div>
                        </div>

                        <div class="form-group row" v-if="errors.has('file')">
                            <div class="col-sm-10 offset-md-2 is-danger">
                                ファイルを指定してください
                            </div>
                        </div>
                        <div class="form-group row" v-if="error">
                            <div class="col-sm-10 offset-md-2 is-danger">
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group row" v-if="errorMessage.success">
                            <div class="col-sm-10 offset-md-2 is-success">
                                {{ errorMessage.success }}
                            </div>
                        </div>
                        <div class="form-group row" v-if="errorMessage.error_list">
                            <div class="col-sm-10 offset-md-2 is-danger">
                                {{ errorMessage.error_list }}
                            </div>
                        </div>

                        <div v-if="dataImport.length > 0">
                            <div class="form-group row">
                                <div class="col-sm-8 offset-md-2">
                                    <table class="table table-import-user">
                                        <thead>
                                            <tr class="headings">
                                                <th
                                                    class="column-title"
                                                    style="width: 60px"
                                                ></th>
                                                <th
                                                    class="column-title"
                                                    style="min-width: 180px"
                                                >
                                                    姓名
                                                </th>
                                                <th
                                                    class="column-title"
                                                    style="min-width: 180px"
                                                >
                                                    ニックネーム
                                                </th>
                                                <th
                                                    class="column-title"
                                                    style="min-width: 180px"
                                                >
                                                    メールアドレス
                                                </th>
                                                <th
                                                    class="column-title"
                                                    style="min-width: 180px"
                                                >
                                                    生年月日
                                                </th>
                                                <th
                                                    class="column-title"
                                                    style="min-width: 180px"
                                                >
                                                    性別
                                                </th>
                                                <th
                                                    class="column-title"
                                                    style="min-width: 180px"
                                                >
                                                    法人名
                                                </th>
                                                <th
                                                    class="column-title"
                                                    style="min-width: 180px"
                                                >
                                                    パスワード
                                                </th>
                                                  <th
                                                    class="column-title"
                                                    style="min-width: 180px"
                                                >
                                                    言語
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr
                                                v-for="(email,
                                                indexUser) in dataImport"
                                                :key="indexUser++"
                                            >
                                                <td>
                                                    {{ indexUser }}
                                                </td>

                                                <td>
                                                    {{ email.student_name }}
                                                </td>
                                                <td>
                                                    {{ email.student_nickname }}
                                                </td>
                                                <td>
                                                    {{ email.student_email }}
                                                </td>
                                                <td>
                                                    {{ email.student_birthday }}
                                                </td>
                                                <td>
                                                    <p
                                                        v-if="
                                                            email.student_sex ==
                                                               0
                                                        "
                                                    >男子</p>
                                                    <p
                                                        v-if="
                                                            email.student_sex ==
                                                                1
                                                        "
                                                    > 女性</p>
                                                    <p
                                                        v-if="
                                                            email.student_sex ==
                                                                2
                                                        "
                                                    >回答しない</p>
                                                </td>
                                                <td>
                                                    {{ email.company_name }}
                                                </td>
                                                <td
                                                    v-for="(pass,
                                                    indexUser) in showList"
                                                    :key="indexUser"
                                                >
                                                    {{ pass }}
                                                </td>
                                                    <td>
                                                    <p
                                                        v-if="
                                                            email.lang_type ==
                                                             'ja'
                                                        "
                                                    >日本語</p>
                                                    <p
                                                        v-if="
                                                            email.student_sex ==
                                                                'en'
                                                        "
                                                    > 英語</p>
                                                    <p
                                                        v-if="
                                                            email.student_sex ==
                                                                'zh'
                                                        "
                                                    >中国語</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <loader :flag-show="flagShowLoader"></loader>
        </div>
    </form>
</template>

<script type="text/javascript">
import VueCal from "vue-cal";
import "vue-cal/dist/i18n/ja.js";
import "vue-cal/dist/vuecal.css";
import axios from "axios";
import Loader from "./../../components/common/loader";

export default {
    components: {
        Loader,
        VueCal
    },
    props: ["errorMessage", "showList", "dataImport", "urlSave"],
    mounted() {},
    data() {
        return {
            baseUrl: Laravel.baseUrl,
            csrfToken: Laravel.csrfToken,
            flagShowLoader: false,
            nameFile: "",
            file: "",
            success: "",
            error: "",
            emails: [],
            password: []
        };
    },
    methods: {
        importFile() {
          this.$validator.validateAll().then((valid) => {
          if (valid) {
            this.$refs.importUsers.submit();
          }
        });
        },
        onFileChange(e) {
            this.nameFile = "";
            this.error = "";
            this.success = "";
            if (e.target.files.length != 0) {
                let val = e.target.files[0].name.toLowerCase(),
                    regex = new RegExp("(.*?)\.(xlsx)$");

                if (!regex.test(val)) {
                    Swal.fire({
                        html:
                            "拡張子が異なります。「xlsx」ファイルを指定してください。",
                        confirmButtonText: "閉じる"
                    });

                    this.$refs.file.value = "";
                    return;
                }

                this.nameFile = e.target.files[0].name;
                this.file = e.target.files[0];
            }
        }
    }
};
</script>
<style scoped>
.is-success {
    color: green;
    font-weight: 700;
    white-space: nowrap;
}
</style>
