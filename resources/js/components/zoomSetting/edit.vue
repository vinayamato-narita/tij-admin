<template>
  <div class="c-body">
    <main class="c-main pt-0">
      <div class="container-fluid">
        <div class="page-heading">
          <div class="page-heading-left">
            <h5>ZOOM連携設定</h5>
          </div>
        </div>
        <div class="fade-in">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <form
                  class="form-horizontal"
                  style="width: 100%"
                  method="POST"
                  ref="updateForm"
                  @submit.prevent="update"
                  autocomplete="off"
                  :action="updateUrl"
                >
                  <input type="hidden" :value="csrfToken" name="_token" />
                  <input
                    type="hidden"
                    :value="zoomSetting.zoom_setting_id"
                    name="id"
                  />
                  <div class="card-header">ZOOM連携設定情報</div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label
                        class="col-md-4 col-form-label text-md-right"
                        for=""
                        >会議開始前参加:
                        <span class="glyphicon glyphicon-star"></span>
                      </label>
                      <div class="col-md-6 col-form-label">
                        <div class="form-check form-check-inline mr-1">
                          <input
                            class="form-check-input"
                            type="radio"
                            value="0"
                            name="join_before_host"
                            id="inline-radio1"
                            v-validate="'required'"
                            v-model="zoomSetting.join_before_host"
                          />
                          <label class="form-check-label" for="inline-radio1"
                            >無効</label
                          >
                        </div>
                        <div class="form-check form-check-inline mr-1">
                          <input
                            class="form-check-input"
                            type="radio"
                            value="1"
                            name="join_before_host"
                            id="inline-radio2"
                            v-validate="'required'"
                            v-model="zoomSetting.join_before_host"
                          />
                          <label class="form-check-label" for="inline-radio2"
                            >有効</label
                          >
                        </div>
                        <div class="input-group is-danger" role="alert">
                          {{ errors.first("join_before_host") }}
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label
                        class="col-md-4 col-form-label text-md-right"
                        for="join_before_host"
                        >待機室:
                        <span class="glyphicon glyphicon-star"></span>
                      </label>
                      <div class="col-md-6 col-form-label">
                        <div class="form-check form-check-inline mr-1">
                          <input
                            class="form-check-input"
                            id="inline-radio3"
                            type="radio"
                            value="0"
                            name="waiting_room"
                            v-validate="'required'"
                            v-model="zoomSetting.waiting_room"
                          />
                          <label class="form-check-label" for="inline-radio3"
                            >無効</label
                          >
                        </div>
                        <div class="form-check form-check-inline mr-1">
                          <input
                            class="form-check-input"
                            id="inline-radio4"
                            type="radio"
                            value="1"
                            name="waiting_room"
                            v-validate="'required'"
                            v-model="zoomSetting.waiting_room"
                          />
                          <label class="form-check-label" for="inline-radio4"
                            >有効</label
                          >
                        </div>
                        <div class="input-group is-danger" role="alert">
                          {{ errors.first("waiting_room") }}
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label
                        class="col-md-4 col-form-label text-md-right"
                        for="auto_recording"
                        >録画方法: <span class="glyphicon glyphicon-star"></span
                      ></label>
                      <div class="col-md-4 col-form-label">
                        <select
                          class="form-control"
                          id="auto_recording"
                          name="auto_recording"
                          v-validate="'required'"
                          v-model="zoomSetting.auto_recording"
                        >
                          <option value="0">ローカル</option>
                          <option value="1">クラウド(編集済み)</option>
                          <option value="2">無効</option>
                        </select>
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
                            class="btn btn-outline-secondary w-100"
                          >
                            キャンセル
                          </button>
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
        join_before_host: {
          required: "会議開始前参加を入力してください",
        },
        auto_recording: {
          required: "録画方法を入力してください",
        },
        waiting_room: {
          required: "待機室を入力してください",
        },
      },
    };
    this.$validator.localize("en", messError);
  },
  components: {
    Loader
  },
  data() {
    return {
      csrfToken: Laravel.csrfToken,
      flagShowLoader: false,
    };
  },
  props: ["zoomSetting", "updateUrl"],
  mounted() {},
  methods: {
    update: function (e) {
      e.preventDefault();
      let that = this;
      this.$validator.validateAll().then((valid) => {
        if (valid) {
          // that.$refs.updateForm.submit();
          that.flagShowLoader = true;
          axios
            .post(this.updateUrl, this.zoomSetting, {
              header: {
                "Content-Type": "multipart/form-data",
              },
            })
            .then((res) => {
              this.$swal({
                title: "ZOOM連携設定情報変更が完了しました。",
                icon: "success",
                confirmButtonText: "OK",
              }).then(function (confirm) {
                that.flagShowLoader = false;
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
                  }).then(function (confirm) {});
                  that.flagShowLoader = false;
                  break;
                default:
                  break;
              }
            });
        } else {
          this.$el
            .querySelector(
              "input[name=" + Object.keys(this.errors.collect())[0] + "]"
            )
            .focus();
          $("html, body").animate(
            {
              scrollTop:
                $(
                  "input[name=" + Object.keys(this.errors.collect())[0] + "]"
                ).offset().top - 104,
            },
            500
          );
        }
      });
    },
  },
};
</script>
