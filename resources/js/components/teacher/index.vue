<template>
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <a :href="registerUrl" class="btn btn-primary mb-3">営業マンを追加する</a>
                            <div class="cd-table-responsive admin-user-list">
                                <data-table
                                        :columns="columns"
                                        :url="urlGetData"
                                        ref="userList"
                                        :translate="translate"
                                        :classes="classes"
                                >
                                    <span slot="filters"> </span>
                                    <tbody slot="body" slot-scope="{ data }">
                                    <tr :key="item.id" v-for="item in data">
                                        <td :key="column.name" v-for="column in columns">
                                            <data-table-cell
                                                    :value="item"
                                                    :name="column.name"
                                                    :meta="column.meta"
                                                    :comp="column.component"
                                                    :classes="column.classes"
                                            >
                                            </data-table-cell>
                                            <slot v-if="column.name == 'action'">
                                                <a :href="`/admin/user/${item.id}/edit`"> <button class="btn btn-success delete-button" type="button">編集</button></a>

                                                <button
                                                        class="btn btn-danger delete-button"
                                                        @click="handleDelete(item.id)"
                                                >
                                                    削除
                                                </button>
                                            </slot>
                                        </td>
                                    </tr>
                                    </tbody>
                                </data-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <loader :flag-show="flagShowLoader"></loader>
    </div>
</template>
<style scoped>
    .delete-button {
        min-width: 60px;
    }
</style>
<script>
    import axios from "axios";
    import Loader from "./../../components/common/loader";

    export default {
        created: function () {},
        components: {
            Loader,
        },
        data() {
            return {
                csrfToken: Laravel.csrfToken,
                flagShowLoader: false,
                urlGetData: this.data.urlGetData,
                columns: [
                    { label: "表示順", name: "display_order", orderable: true },
                    { label: "講師名", name: "teacher_name", orderable: true },
                    { label: "ニックネーム", name: "teacher_nickname", orderable: true },
                    { label: "スカイプ名", name: "teacher_skypename", orderable: true },
                    { label: "メールアドレス", name: "teacher_email", orderable: true },
                    { label: "固定/自由", name: "is_free_teacher", orderable: true },
                    { label: "最終ログイン日時", name: "last_login_date", orderable: true },
                    { label: "", name: "action" },
                ],
                filters: {},
                translate: { nextButton: ">", previousButton: "<" },
                pagination: { data: {}, hideWhenEmpty: true },
                classes : {
                    "table-container": {
                        "table-responsive": true,
                    },
                    "table": {
                        "table": true,
                        "table-borderless": true,
                        "border" : false
                    },
                    "t-head": {

                    },
                    "t-body": {

                    },
                    "t-head-tr": {

                    },
                    "t-body-tr": {

                    },
                    "td": {

                    },
                    "th": {

                    },
                }
            };
        },
        props: ["data", "registerUrl"],
        mounted() {console.log('fff')},
        methods: {
            handleDelete(id) {
                let that = this;
                this.$swal({
                    title: "ユーザーを削除しますか？",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: "キャンセル",
                    confirmButtonText: "削除",
                }).then((result) => {
                    if (result.value) {
                        that.flagShowLoader = true;
                        axios
                            .get("users/" + id + "/delete")
                            .then((response) => {
                                this.$swal({
                                    title: "ユーザーが削除されました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {});
                                that.updateTable();
                                that.flagShowLoader = false;
                            })
                            .catch((error) => {
                                alert(error);
                            });
                    }
                });
            },
            updateTable() {
                this.$refs.userList.getData();
            },
        },
        created() {},
    };
</script>

