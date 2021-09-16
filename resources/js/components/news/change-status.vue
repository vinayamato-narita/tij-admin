<template>
    <div>
        <a class="dropdown-item cursor-pointer mr-2" data-toggle="tooltip" data-placement="top" v-on:click="changeStatus()"><i v-bind:class="['fa', statusNews == 0 ? 'fa-toggle-on' : 'fa-toggle-off']" aria-hidden="true"></i>{{statusNews == 0 ? "表示" : "非表示"}}</a>
        <loader :flag-show="flagShowLoader"></loader>
    </div>
</template>

<script>
import Loader from "../common/loader.vue"
import axios from "axios";
export default {
    data() {
        return {
            statusNews: this.status,
            flagShowLoader: false,
        }
    },
    components: {
        Loader
    },
    props: ['urlAction', 'status'],
    mounted() {},
    methods: {
        changeStatus() {
            let that = this;
            that.flagShowLoader = true;
            axios
                .post(that.urlAction, {
                  _token: Laravel.csrfToken,
                })
                .then(function (response) {
                    that.flagShowLoader = false;
                    location.reload();
                })
                .catch(error => {
                    that.flagShowLoader = false;
                });
        }
    }
};
</script>
