<template>
<div>
    <div v-if="status" class="cursor-pointer div-primary" v-on:click="changeStatus()">
        <button class="btn btn-primary btn-primary-attendance">
            <span>出席</span>
        </button>
    </div>
    <div v-else class="cursor-pointer div-danger" v-on:click="changeStatus()">
        <button class="btn btn-danger btn-danger-attendance">
            欠席
        </button>
    </div>
    
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
            studentInfoEx: {
                lesson_history_id : this.studentInfo.lesson_history_id,
                student_id : this.studentInfo.student_id,
                lesson_schedule_id : this.studentInfo.lesson_schedule_id,
                _token: Laravel.csrfToken
            }
        }
    },
    components: {
        Loader
    },
    props: ['urlAction', 'status', 'studentInfo'],
    mounted() {
        
    },
    methods: {
        changeStatus() {
            let that = this;
            that.flagShowLoader = true;
            $('.loading-div').removeClass('hidden');
            axios
                .post(that.urlAction, that.studentInfoEx)
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
