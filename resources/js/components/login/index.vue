<template>
<div class="container">
  <div class="fade-in">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="POST" ref="formLogin" action="/login" v-on:submit.prevent="submit">
          <input type="hidden" :value="csrfToken" name="_token" />
          <input type="hidden" :value="data.request.url_redirect" name="url_redirect" />
          <div class="card">
            <div class="card-body card-login">
              <div class="row justify-content-center">
                <div class="col-sm-8 text-center title-login">
                  <h3>ログイン</h3>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-sm-8">
                  <div class="form-group">
                    <input class="form-control" placeholder="メールアドレス" type="text" name="email" v-model="model.email" />
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-sm-8">
                  <div class="form-group">
                    <input class="form-control" type="password" placeholder="パスワード" name="password" v-model="model.password" />
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-md-8 col-form-label">
                  <div class="form-check checkbox">
                    <input class="form-check-input" id="remember_me" name="remember_me" type="checkbox" value="1">
                    <label class="form-check-label" for="remember_me">次回から自動的にログイン</label>
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-sm-8 p-l-20">
                  <a class="btn-link px-0" :href="urlForgotPassword">パスワードを忘れた方へ</a><br>
                  <div class="error text-center" role="alert" v-if="data.message">
                    {{ data.message }}
                  </div>
                  <button class="btn btn-primary px-4 btn-login" type="submit">ログイン</button>
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
        },
        password: {
          required: 'パスワードを入力してください。',
          min: "パスワードは8文字以上にしてください。"
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
  props: ['data', 'urlForgotPassword'],
  components: {
    Loader
  },
  methods: {
    submit() {
      this.flagShowLoader = true;
      this.$refs.formLogin.submit();
    },

  }
}
</script>
