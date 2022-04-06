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
                            ユーザ一括登録
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
                            href="/files/import_format/course_user_import.xlsx"
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
                      <div class="form-group row" v-if="errorMessage">
                        <div class="col-sm-10 offset-md-2 is-danger">
                          {{ errorMessage }}
                        </div>
                      </div>

                      <div v-if="showList">
                        <div class="form-group row">
                          <div class="col-sm-8 offset-md-2">
                            <table class="table table-import-user">
                              <thead>
                                <tr class="headings">
                                  <th class="column-title" style="width: 60px">
                                    NO.
                                  </th>

                                  <th class="column-title" style="width: 180px">
                                    コースID
                                  </th>

                                  <th class="column-title" style="min-width: 180px">
                                    メールアドレス
                                  </th>

                                  <th class="column-title" style="min-width: 200px;">
                                    チェック結果
                                  </th>
                                </tr>
                              </thead>

                              <tbody>
                                <tr
                                  v-for="(user, indexUser) in userDataImport"
                                  :key="indexUser"
                                >
                                  <td>
                                    {{ indexUser }}
                                  </td>

                                  <td>
                                    {{ user.course_id }}
                                  </td>

                                  <td>
                                    {{ user.email }}
                                  </td>

                                  <td v-if="user.error_list.length > 0">
                                    {{ setShowSave() }}
                                    <div
                                      class="is-danger"
                                      v-for="(error, index) in user.error_list"
                                      :key="index"
                                    >
                                      {{ error }}
                                    </div>
                                  </td>
                                  <td v-else>
                                    登録可能
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="form-group" style="margin-top: 20px;">
                          <div class="text-center">
                            <button
                              v-if="showSaveBtn"
                              type="button"
                              method="post"
                              class="btn btn-success btn-w-100"
                              v-on:click="save"
                            >
                              登録
                            </button>
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

<script  type="text/javascript">
import VueCal from 'vue-cal'
import 'vue-cal/dist/i18n/ja.js'
import 'vue-cal/dist/vuecal.css'
import axios from "axios";
import Loader from "./../../components/common/loader";

export default {
    components: {
        Loader,
        VueCal,
    },
    props: [
      "errorMessage",
      "showList",
      "dataImport",
      "urlSave",
    ],
    mounted() {
    },
    data() {
        return {
            baseUrl: Laravel.baseUrl,
            csrfToken: Laravel.csrfToken,
            flagShowLoader : false,
            nameFile: "",
            userDataImport: this.dataImport,
            showSaveBtn: Object.keys(this.dataImport).length ? true : false,
        }
    },
    methods :{
      importFile() {
        this.$validator.validateAll().then((valid) => {
          if (valid) {
            this.$refs.importUsers.submit();
          }
        });
      },
      onFileChange(e) {
        this.nameFile = "";
        if (e.target.files.length != 0) {
          let val = e.target.files[0].name.toLowerCase(),
            regex = new RegExp("(.*?)\.(xlsx)$");

          if (!regex.test(val)) {
            Swal.fire({
              html: "拡張子が異なります。「xlsx」ファイルを指定してください。",
              confirmButtonText: "閉じる",
            });

            this.$refs.file.value = "";
            return;
          }

          this.nameFile = e.target.files[0].name;
        }
      },
      save() {
        window.location.href = this.urlSave;
      },
      setShowSave() {
        this.showSaveBtn = false;
      },
    }
};
</script>
