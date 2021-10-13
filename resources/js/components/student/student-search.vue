<template>
    <div class="input-group pull-right" style="width: auto;">
        <form method="GET" :action="url" class="input-search" id="searchInput">
            <input type="hidden" name="limit" :value="pageLimit" />
                <input
                    name="search_input"
                    placeholder="検索"
                    class="form-control clss_search_input"
                    type="text"
                    :value="dataQuery.search_input"
                />
        </form>

        <div class="input-group-btn">
            <div class="btn-group" role="group">
                <div class="dropdown dropdown-lg">
                    <button type="button" class="btn btn-sm dropdown-toggle btn-drop-detail" data-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right search-popup"  style="width: 400px;padding: 10px 10px" role="menu">
                        <form method="GET" :action="url">
                            <input type="hidden" name="limit" :value="pageLimit" />
                            <div class="form-group">
                                <label>生徒番号</label>
                                <div class="input text">
                                    <input type="text" name="student_id" class="form-control input-sm" placeholder="生徒番号を入力してください" :value="dataQuery.student_id">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>生徒名</label>
                                <div class="input text">
                                    <input type="text" name="student_name" class="form-control input-sm" placeholder="生徒名を入力してください" :value="dataQuery.student_name">
                                </div>
                            </div>   
                            <div class="form-group">
                                <label>ニックネーム</label>
                                <div class="input text">
                                    <input type="text" name="student_nickname" class="form-control input-sm" placeholder="ニックネームを入力してください" :value="dataQuery.student_nickname">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label>スカイプ名</label>
                                <div class="input text">
                                    <input type="text" name="student_skypename" class="form-control input-sm" placeholder="スカイプ名を入力してください" :value="dataQuery.student_skypename">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label>メールアドレス</label>
                                <div class="input text">
                                    <input type="text" name="student_email" class="form-control input-sm" placeholder="メールアドレスを入力してください" :value="dataQuery.student_email">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label>法人名</label>
                                <div class="input text">
                                    <input type="text" name="company_name" class="form-control input-sm" placeholder="法人名を入力してください" :value="dataQuery.company_name">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label>企業名</label>
                                <div class="input text">
                                    <input type="text" name="all_project_company_name" class="form-control input-sm" placeholder="企業名を入力してください" :value="dataQuery.all_project_company_name">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label>企業ID</label>
                                <div class="input text">
                                    <input type="text" name="project_code" class="form-control input-sm" placeholder="企業IDを入力してください" :value="dataQuery.project_code">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label style="margin-right: 20px">法人コード</label> <input type="checkbox" name="check_company_code" v-model="dataQuery.check_company_code" @change="dataQueryEx.check_company_code = !dataQueryEx.check_company_code" value=1> 法人コード空白のみ
                                <div class="input text" v-if="!dataQueryEx.check_company_code">
                                    <input type="text" name="company_code" class="form-control input-sm" placeholder="企業IDを入力してください" :value="dataQuery.company_code">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label>初回登録日</label>
                                <div class="input text">
                                    <date-picker
                                        :input-attr="{ name: 'first_lesson_date'}"
                                        :format="'YYYY/MM/DD'"
                                        v-model="dataQueryEx.first_lesson_date"
                                        type="date"
                                    ></date-picker>
                                </div>
                            </div> 
                            <button type="submit" class="btn btn-primary width-100">検索</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <span class="input-group-append">
            <button class="btn btn-primary" type="submit" form="searchInput">
                <i class="fa fa-search" aria-hidden="true"></i>
            </button>
        </span>
    </div>
</template>

<script>
    export default {
        props: ["url", "pageLimit", "dataQuery"],
        mounted() {},
        data() {
        return {
            dataQueryEx: {
                first_lesson_date: new Date(this.dataQuery.first_lesson_date ?? ""),
                check_company_code: this.dataQuery.check_company_code
            },
            check_company_code: true
        };
    },
    };
</script>
