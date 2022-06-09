<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            キャンペーン追加
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title-page">コース情報
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                        >コースID :
                                        </label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ course.course_id }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                                class="col-md-3 col-form-label text-md-right"
                                                for="text-input"
                                        >コース名:</label
                                        >
                                        <div class="col-md-6 pt-7">
                                            {{ course.course_name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save" autocomplete="off">
                                    <div class="card-header">
                                        <h5 class="title-page" >情報</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label
                                                    class="col-md-3 col-form-label text-md-right"
                                                    for="text-input"
                                            >値段:<span class="glyphicon glyphicon-star"
                                            ></span
                                            ></label
                                            >
                                            <div class="col-md-6">
                                                <input
                                                        class="form-control"
                                                        type="number"
                                                        name="price"
                                                        style="max-width: 100px"
                                                        v-model="campaign.price"
                                                        v-validate="'required|min_value:0|max_value:1000000000'"
                                                />

                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('price')"
                                                >
                                                    {{ errors.first("price") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                    class="col-md-3 col-form-label text-md-right"
                                                    for="text-input"
                                            >開始時間:<span class="glyphicon glyphicon-star"
                                            ></span
                                            ></label
                                            >
                                            <div class="col-md-6">
                                                <date-picker
                                                        v-model="campaign.startDate"
                                                        style="width: 150px"
                                                        :format="'YYYY/MM/DD HH:mm'"
                                                        type="datetime"
                                                ></date-picker>
                                                <input type="hidden" name="start_date" v-model="campaign.startDate" v-validate="'required|compare_date|exists_campaign_datetime'">

                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('start_date')"
                                                >
                                                    {{ errors.first("start_date") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                    class="col-md-3 col-form-label text-md-right"
                                                    for="text-input"
                                            >終了時間:<span class="glyphicon glyphicon-star"
                                            ></span
                                            ></label
                                            >
                                            <div class="col-md-6">
                                                <date-picker
                                                        v-model="campaign.endDate"
                                                        style="width: 150px"
                                                        :format="'YYYY/MM/DD HH:mm'"
                                                        type="datetime"
                                                ></date-picker>
                                                <input type="hidden" name="end_date" v-model="campaign.endDate" v-validate="'required|exists_campaign_datetime|compare_date'">


                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('end_date')"
                                                >
                                                    {{ errors.first("end_date") }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="line"></div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlCourseDetail" class="btn btn-default w-100">閉じる</a>
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
    import Loader from "./../common/loader.vue";
    import moment from 'moment-timezone'


    export default {
        name: "course-add-campaign",
        props: ['course', 'urlCourseDetail', 'existCampaignDatetime', 'urlAction'],
        created: function() {
            let messError = {
                custom: {
                    price : {
                        required: "値段を入力してください",
                        min_value: "値段は0～1000000000 を入力してください",
                        max_value: "値段数は0～1000000000 を入力してください"
                    },
                    start_date: {
                        required : '開始時間を入力してください',
                        compare_date : '開始時間は終了時間よりも前に設定してください',
                        exists_campaign_datetime: "この間にキャンペーンを行いました"
                    },
                    end_date: {
                        required : '終了時間を入力してください',
                        compare_date : '開始時間は終了時間よりも前に設定してください',
                        exists_campaign_datetime: "この間にキャンペーンを行いました"
                    }
                }
            };
            this.$validator.localize("en", messError);
            let  that = this;
            this.$validator.extend("compare_date", {
                validate(value, args) {
                    return {valid: (that.campaign.endDate - that.campaign.startDate) > 0 ? true : false}
                }
            });
            this.$validator.extend("exists_campaign_datetime", {
                validate(value, args) {
                    return axios
                        .post(that.existCampaignDatetime, {
                            _token: Laravel.csrfToken,
                            startDate:  moment(that.campaign.startDate).format('YYYY-MM-DD HH:mm:ss'),
                            endDate:  moment(that.campaign.endDate).format('YYYY-MM-DD HH:mm:ss'),
                            type: args[0],
                        })
                        .then(function (response) {
                            return {
                                valid: response.data.valid,
                            };
                        })
                        .catch((error) => {});
                },
            });
        },
        data() {
            return {
                flagShowLoader: false,
                campaign: {
                    price : 0,
                    startDate: new Date(),
                    endDate: new Date(),
                }
            };
        },
        components: {
            Loader,
        },
        methods: {
            save() {
                let that = this;
                this.$validator
                    .validateAll()
                    .then(valid => {
                        if (valid) {
                            that.flagShowLoader = true;
                            that.submit();
                        }
                    })
                    .catch(function(e) {});
            },
            submit(e) {
                let that = this;

                axios
                    .post(that.urlAction, {
                        startDate: moment(this.campaign.startDate).format('YYYY-MM-DD HH:mm:ss'),
                        endDate: moment(this.campaign.endDate).format('YYYY-MM-DD HH:mm:ss'),
                        price: this.campaign.price
                    })
                    .then(response => {
                        that.flagShowLoader = false;
                        if (response.data.status == "OK") {
                            this.$swal({
                                text: "キャンペーン追加が完了しました。",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(result => {
                                window.location.href = that.urlCourseDetail;
                            });
                        }
                    })
                    .catch(e => {
                        this.flagShowLoader = false;
                    });
            }
        },
        watch: {
        }
    }
</script>

<style scoped>

</style>
