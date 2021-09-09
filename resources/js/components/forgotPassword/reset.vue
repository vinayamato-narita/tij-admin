<template>
<div class="container">
  <div class="fade-in">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="POST" ref="formData" action="/change_password" v-on:submit.prevent="submit">
          <input type="hidden" :value="csrfToken" name="_token" />
          <input type="hidden" :value="data.token" name="reset_password_token" />
          <div class="card">
            <div class="card-body card-login">
              <div class="row justify-content-center">
                <div class="col-sm-8 text-center title-login">
                  <h3>パスワードの再設定</h3>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label for="ccnumber">パスワード<span class="text-danger">*</span></label>
                    <input class="form-control" type="password" name="password" v-model="model.password" v-validate="'required|min:8|max:15|password_rule'" ref="password">
                    <div class="input-group error" role="alert" v-if="errors.has('password')">
                      {{ errors.first("password") }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label for="ccnumber">パスワード（確認用）<span class="text-danger">*</span></label>
                    <input class="form-control" type="password" name="password_confirm" v-validate="'required|confirmed:password'">
                    <div class="input-group error" role="alert" v-if="errors.has('password_confirm')">
                      {{ errors.first("password_confirm") }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-sm-8 p-l-20">
                  <button class="btn btn-primary px-4 btn-login" type="submit">送信する</button>
                </div>
              </div>
              <div class="row justify-content-center group-back-login">
                <div class="col-sm-8 p-l-20">
                  <a class="btn-link px-0" :href="baseUrl+'/login'">ログイン画面へ</a><br>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <loader :flag-show="flagShowLoader"></loader>
</div>
</template>

<script>
import Loader from "../common/loader.vue"
export default {
  created: function () {
    let messError = {
      custom: {
        password: {
          required: "パスワードを入力してください。",
          max: "パスワードは15文字以内で入力してください。",
          min: "パスワードは8文字以上で入力してください。",
          password_rule: "パスワードは半角英数字で、大文字、小文字、数字で入力してください。"
        },
        password_confirm: {
          required: "パスワード（確認用）を入力してください。",
          confirmed: "パスワード（確認用）が入力されたものと異なります。"
        }
      }
    };
    this.$validator.localize("en", messError);
  },
  data() {
    return {
      flagShowLoader: false,
      model: {},
      csrfToken: Laravel.csrfToken,
      baseUrl: Laravel.baseUrl
    }
  },
  mounted() {},
  props: ['data'],
  components: {
    Loader
  },
  methods: {
    submit() {
      let that = this;
      this.$validator.validateAll().then(valid => {
        if (valid) {
          this.flagShowLoader = true;
          that.$refs.formData.submit();
        } else {
          this.$el
            .querySelector(
              "input[name=" + Object.keys(this.errors.collect())[0] + "]"
            )
            .focus();
          $("html, body").animate({
              scrollTop: $(
                "input[name=" + Object.keys(this.errors.collect())[0] + "]"
              ).offset().top - 104
            },
            500
          );
        }
      });
    },

  }
}
</script>
