<template>
<div class="container">
  <div class="fade-in">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="POST" ref="formData" action="/forgot_password" v-on:submit.prevent="submit">
          <input type="hidden" :value="csrfToken" name="_token" />
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
                    <label class="title-forgot-password">パスワード再設定のURLを記載したメールを送信します。</label>
                    <input class="form-control" placeholder="メールアドレス" type="text" name="email" v-validate="'required|email'" v-model="model.email" />
                    <div class="input-group error" role="alert" v-if="errors.has('email')">
                      {{ errors.first("email") }}
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
        email: {
          required: "メールアドレスを入力してください。",
          email: "メールアドレス形式が正しくありません。"
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
  props: [],
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
