<template>
<div>
  <a class="cursor-pointer btn btn-danger w-100 mr-2 color-white" @click="showAlert">
    削除
  </a>
  <loader :flag-show="flagShowLoader"></loader>
</div>
</template>

<script>
import Loader from "./loader.vue"
import axios from "axios";
export default {
  data() {
    return {
      flagShowLoader: false,
    }
  },
  components: {
    Loader
  },
  props: ['deleteAction', 'messageConfirm', 'urlRedirect'],
  mounted() {},
  methods: {
    showAlert() {
      let that = this;
      this.$swal({
        title: that.messageConfirm,
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
                  window.location.href = that.urlRedirect;
                });
            })
            .catch(error => {
              that.flagShowLoader = false;
              that
                .$swal({
                  title: error.response.data.message,
                  icon: "error",
                  confirmButtonText: "OK"
                })
                .then(function () {
                  location.reload();
                });
            });
        }
      });
    }
  }
};
</script>
