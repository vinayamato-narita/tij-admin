<template>
    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            権限編集
                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="basic-form" @submit.prevent="save">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-md-6 text-right">
                                               管理ユーザ名 : {{ adminInfo.admin_user_name }}
                                            </div>
                                            <div class="col-md-6">
                                               メールアドレス : {{ adminInfo.admin_user_email }}
                                            </div>
                                        </div>
                                       
                                        <div class="form-group row">
                                           <table class="table table-responsive-sm table-striped border">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">編集</th>
                                                        <th class="text-center">閲覧</th>
                                                        <th>操作メニュー</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(role, index) in adminRoleEx" v-if="!isNaN(index)">
                                                        <td class="text-center">
                                                            <input type="checkbox" 
                                                                v-model="role.can_edit"
                                                                :name="'adminRoleEx[' + index + '][can_edit]'"
                                                                :ref="'adminRoleEx[' + index + '][can_edit]'"
                                                                @change="changeCheckbox(index, 'can_edit', role.admin_rights_id)"
                                                            >
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" 
                                                                v-model="role.is_permitted"
                                                                :name="'adminRoleEx[' + index + '][is_permitted]'"
                                                                :ref="'adminRoleEx[' + index + '][is_permitted]'"
                                                                @change="changeCheckbox(index, 'is_permitted', role.admin_rights_id)"
                                                            >
                                                        </td>
                                                        <td>{{ role.admin_rights_name_ja }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                                <a :href="urlAdminList" class="btn btn-default w-100">閉じる</a>
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

<script type="text/javascript">
import axios from "axios";
import Loader from "./../common/loader.vue";

export default {
    created: function() {
        
    },
    components: {
        Loader,
    },
    data() {
        return {
            flagShowLoader: false,
            adminRoleEx: this.adminRole
        };
    },
    props: ["urlAction", "urlAdminList", "adminInfo", "adminRole"],
    mounted() {
        
    },
    methods: {
        save(e) {
            let that = this;
            axios
                .post(that.urlAction,  {
                    roles: that.adminRoleEx, 
                    _token: Laravel.csrfToken
                })
                .then(response => {
                    that.flagShowLoader = false;
                    if (response.data.status == "OK") {
                        this.$swal({
                            text: "権限編集が完了しました。",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(result => {
                            window.location.href = that.urlAdminList;
                        });
                    }
                })
                .catch(e => {
                    this.flagShowLoader = false;
                });
        },
        changeCheckbox(index, text, rightId) {
            if (rightId != 1 && rightId != 6) {
                if (text == 'can_edit') {
                    this.adminRoleEx[index]['is_permitted'] = this.adminRoleEx[index][text];
                }
                if (text == 'is_permitted') {
                    this.adminRoleEx[index]['can_edit'] = this.adminRoleEx[index][text];
                }
            }

            if (rightId == 1 || rightId == 6) {
                if(text == 'can_edit' && (this.adminRoleEx[index][text] == true || this.adminRoleEx[index][text] == 1)) {
                    this.adminRoleEx[index]['is_permitted'] = true;
                }
                if(text == 'is_permitted' && (this.adminRoleEx[index][text] == false || this.adminRoleEx[index][text] == 0)) {
                    this.adminRoleEx[index]['can_edit'] = false;
                }
            }
        },
    }
};
</script>
