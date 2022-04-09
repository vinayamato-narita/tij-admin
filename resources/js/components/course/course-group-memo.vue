<template>
    <modal name="course-group-memo" :pivotY="0.1" :reset="true" :width="600" height="auto"  :scrollable="true" :adaptive="true" :clickToClose="false" >
        <div class="card">
            <div class="card-header">引き継ぎメモ
                <div class="float-right">
                    <button type="button" class="close"  v-on:click="hide" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form  class="form-horizontal " style="width: 100%" method="POST" ref="registerForm" @submit.prevent="register" autocomplete="off">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea 
                                    class="form-control" 
                                    id="memo"  
                                    name="memo"
                                    v-model="courseGroupMemoEx.memo"
                                    rows="10"
                                    v-validate="'max:20000'">
                                </textarea>

                                <div class="input-group is-danger" role="alert">
                                    {{ errors.first("memo") }}
                                </div>
                            </div>
                        </div>

                        <div class="form-actions text-center">
                            <div class="line"></div>
                            <div class="form-group" style="margin-bottom: 0">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary w-100 mr-2" >登録</button>
                                    <a v-on:click="hide" class="btn btn-default w-100">閉じる</a></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </modal>
</template>

<script>
    import Loader from "./../../components/common/loader";

    export default {
        name: "modal-table",
        components: {
            Loader
        },
        data() {
            return {
                courseGroupMemoEx : this.courseGroupMemo
            };
        },
        props: [ 'urlUpdateGroupMemo', 'courseGroupMemo', 'detailUrl' ],
        mounted() {
        },
        created: function () {
            let messError = {
                custom: {
                    memo : {
                        max: "引き継ぎメモは20000文字以内で入力してください。",
                    },
                },
            };
            this.$validator.localize("en", messError);
        },
        methods: {
            hide () {
                this.$modal.hide('course-group-memo');
            },
            register() {
                let that = this;

                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.urlUpdateGroupMemo , that.courseGroupMemoEx)
                            .then((res) => {
                                window.location = that.detailUrl;
                            })
                            .catch((err) => {
                                
                            });
                    }
                });

            },
        },
    }
</script>

<style scoped>

</style>
