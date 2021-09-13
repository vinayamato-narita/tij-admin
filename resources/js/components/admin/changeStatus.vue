<template>
<div>
    <input type="checkbox" class="cursor-pointer" v-on:change="changeStatus()" v-bind:checked="status==1" />
    <loader :flag-show="flagShowLoader"></loader>
</div>
</template>

<script>
import Loader from "../common/loader.vue"
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
    props: ['urlAction', 'status'],
    mounted() {},
    methods: {
        changeStatus() {
            let that = this;
            that.flagShowLoader = true;
            $('.loading-div').removeClass('hidden');
            axios
                .post(that.urlAction, {
                  _token: Laravel.csrfToken
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
