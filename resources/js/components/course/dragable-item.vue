<template>
    <modal name="drag-teacher-lesson-modal" :pivotY="0.1" :reset="true" :width="1000" :height="auto" :scrollable="true"
           :adaptive="true" :clickToClose="false" @before-open="getData">
        <div class="card">
            <div class="card-header"> レッスン一覧

                <div class="float-right">
                    <button type="button" class="close" v-on:click="hide" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-10">
                    </div>

                </div>
                <div class="row mb-2">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-10">
                    </div>

                    <div class="tanemaki-table pd-t-7" style="width: 100%;">
                        <div v-if="dataList.length ==  0" class="data-empty-message text-center alert alert-danger">
                            該当データがありません

                        </div>
                        <table v-if="dataList.length !=  0" class="table table-responsive-sm table-striped border">
                            <thead>
                            <tr>
                                <th class="text-center bg-gray-100 " style="width: 50px">
                                    <input type="checkbox" class=" checkbox"
                                           style="width: auto; height: auto; display: none;">
                                </th>
                                <th class="text-center text-md-left bg-gray-100">レッスン名</th>
                            </tr>
                            </thead>
                            <draggable v-model="dataList" tag="tbody">
                                <tr v-for="lesson in dataList" :key="lesson.lesson_id">
                                    <td class="text-center">
                                        <input type="checkbox" class=" checkbox" style="display: none;">
                                    </td>
                                    <td class="text-md-left">{{ lesson.lesson_name }}</td>
                                </tr>
                            </draggable>
                            <tbody>


                            </tbody>

                        </table>
                    </div>


                </div>
                <div class="row mb-2">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-10">
                    </div>
                </div>
                <div class="form-actions text-center">
                    <div class="line"></div>
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

    export default {
        name: "drag-teacher-lesson-modal",
        components: {
            Loader,
        },
        data() {
            return {
                dataList: [{}],
                auto: 'auto',
                checkedIds: [],

            };
        },
        props: ['id', 'registerUrl', 'detailUrl', 'listLessonAttachUrl'],
        mounted() {
        },
        created: function () {
            this.getData();

        },
        methods: {
            toPage(page) {
                this.currentPage = page;
                this.getData()

            },
            hide() {
                this.$modal.hide('drag-teacher-lesson-modal');
            },
            getData() {
                let that = this;
                axios.get(this.listLessonAttachUrl, {
                    params: {
                        id: that.id,
                    }
                })
                    .then(function (response) {
                        that.dataList = response.data.dataList;
                    })
                    .catch(function (error) {
                    });
            },
            submit() {
                let that = this;
                axios
                    .post(that.registerUrl, that.checkedIds)
                    .then(response => {
                        window.location = this.detailUrl;
                    })
                    .catch(e => {
                    });
            }
        },

    }
</script>

<style scoped>

</style>
