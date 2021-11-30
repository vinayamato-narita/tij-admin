<template>
    <modal name="add-tag-modal"    :pivotY="0.3" :reset="true" :width="500" :height="160"  :scrollable="true" :adaptive="true" :clickToClose="false" @before-open="getData">
        <div class="card">
            <div class="card-header"><h5>タグ作成</h5>

                <div class="float-right">
                    <button type="button" class="close"  v-on:click="hide" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-8">
                        <input class="form-control" v-model="tagName" name="tagName" v-validate="'required|max:255'">
                        <div
                                class="input-group is-danger"
                                role="alert"
                                v-if="errors.has('tagName')"
                        >
                            {{ errors.first("tagName") }}
                        </div>
                    </div>
                    <div class="col-md-2">

                    </div>


                </div>

                <div class="form-actions text-center">
                    <div class="form-group">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-100 mr-2" v-on:click="submit">登録</button>
                            <a v-on:click="hide" class="btn btn-default w-100">閉じる</a></div>
                    </div>
                </div>


            </div>



        </div>
    </modal>

</template>

<script>
    import Loader from "./../../components/common/loader";
    import axios from "axios";

    export default {
        name: "add-tag-modal",
        components: {
            Loader
        },
        data() {
            return {
                tagName : '',
                event : null,


            };
        },
        props: [ 'createTagUrl'],
        mounted() {
        },
        created: function () {
            let messError = {
                custom: {
                    tagName: {
                        required: "タグ名を入力してください",
                        max: "タグ名は255文字以内で入力してください",
                    },
                }
            };
            this.$validator.localize("en", messError);
        },
        methods: {
            getData(e){
                this.tagName = '';
                if (e !== undefined) {
                    this.event = e;
                }}
                ,
            hide () {
                this.$modal.hide('add-tag-modal');
            },
            submit() {
                let that = this;
                this.$validator
                    .validateAll()
                    .then(valid => {
                        if (valid) {
                            var formData = new FormData();
                            formData.append('tagName', that.tagName);
                            axios.post(that.createTagUrl, formData)
                                .then(function (response) {
                                    if (response.status === 200) {
                                        that.event.params.handlers.sendTagCreated({name : that.tagName, id : response.data.tag_id})
                                    }
                            })
                                .catch(function (error) {
                                    this.$modal.hide('add-tag-modal');

                                });
                            this.hide();
                        }
                    })
                    .catch(function(e) {});
            }
        },

    }
</script>

<style scoped>

</style>
