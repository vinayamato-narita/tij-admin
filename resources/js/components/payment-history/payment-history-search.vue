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
                            <input type="hidden" name="search_detail" value="1" />
                            <div class="form-group">
                                <label>受注日</label>
                                <div class="input text row">
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'payment_date_start'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.payment_date_start"
                                            type="date"
                                        ></date-picker>
                                    </div>
                                    <div class="col-md-1">
                                        <span>～</span>
                                    </div>
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'payment_date_end'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.payment_date_end"
                                            type="date"
                                        ></date-picker>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>基準日</label>
                                <div class="input text row">
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'start_date_start'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.start_date_start"
                                            type="date"
                                        ></date-picker>
                                    </div>
                                    <div class="col-md-1">
                                        <span>～</span>
                                    </div>
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'start_date_end'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.start_date_end"
                                            type="date"
                                        ></date-picker>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>受講開始日</label>
                                <div class="input text row">
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'begin_date_start'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.begin_date_start"
                                            type="date"
                                        ></date-picker>
                                    </div>
                                    <div class="col-md-1">
                                        <span>～</span>
                                    </div>
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'begin_date_end'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.begin_date_end"
                                            type="date"
                                        ></date-picker>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>有効期限日</label>
                                <div class="input text row">
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'point_expire_date_start'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.point_expire_date_start"
                                            type="date"
                                        ></date-picker>
                                    </div>
                                    <div class="col-md-1">
                                        <span>～</span>
                                    </div>
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'point_expire_date_end'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.point_expire_date_end"
                                            type="date"
                                        ></date-picker>
                                    </div>
                                </div>
                            </div>

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
                                <label>メール</label>
                                <div class="input text">
                                    <input type="text" name="student_email" class="form-control input-sm" placeholder="メールを入力してください" :value="dataQuery.student_email">
                                </div>
                            </div> 

                            <div class="form-group">
                                <label>商品名</label>
                                <div class="input text">
                                    <input type="text" name="item_name" class="form-control input-sm" placeholder="商品名を入力してください" :value="dataQuery.item_name">
                                </div>
                            </div> 

                            <div class="form-group">
                                <label>企業ID</label>
                                <div class="input text">
                                    <input type="text" name="project_code" class="form-control input-sm" placeholder="企業IDを入力してください" :value="dataQuery.project_code">
                                </div>
                            </div> 

                            <div class="form-group">
                                <label>企業名</label>
                                <div class="input text">
                                    <input type="text" name="company_name" class="form-control input-sm" placeholder="企業名を入力してください" :value="dataQuery.company_name">
                                </div>
                            </div> 

                            <div class="form-group">
                                <label style="margin-right: 20px">法人コード</label> <input type="checkbox" name="check_corporation_code" v-model="dataQuery.check_corporation_code" @change="dataQueryEx.check_corporation_code = !dataQueryEx.check_corporation_code" value=1> 法人コード空白のみ
                                <div class="input text" v-if="!dataQueryEx.check_corporation_code">
                                    <input type="text" name="corporation_code" class="form-control input-sm" placeholder="法人コードを入力してください" :value="dataQuery.corporation_code">
                                </div>
                            </div> 

                            <div class="form-group">
                                <label>キャンペーンコード</label>
                                <div class="input text">
                                    <input type="text" name="campaign_code" class="form-control input-sm" placeholder="企業名を入力してください" :value="dataQuery.campaign_code">
                                </div>
                            </div> 

                            <div class="form-group">
                                <label>支払方法</label>
                                <div class="input text">
                                    <select
                                        class="form-control"
                                        name="j_paid_status"
                                        v-model="dataQuery.j_paid_status"
                                        v-validate="'required'"
                                    >
                                        <option></option>
                                        <option :value="key" v-for="(value, key) in paymentType">
                                            {{ value }}</option
                                        >
                                    </select>
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
        props: ["url", "pageLimit", "dataQuery", "paymentType"],
        mounted() {
            
        },
        data() {
        return {
            dataQueryEx: {
                payment_date_start: new Date(this.dataQuery.payment_date_start ?? ""),
                payment_date_end: new Date(this.dataQuery.payment_date_end ?? ""),
                start_date_start: new Date(this.dataQuery.start_date_start ?? ""),
                start_date_end: new Date(this.dataQuery.start_date_end ?? ""),
                begin_date_start: new Date(this.dataQuery.begin_date_start ?? ""),
                begin_date_end: new Date(this.dataQuery.begin_date_end ?? ""),
                point_expire_date_start: new Date(this.dataQuery.point_expire_date_start ?? ""),
                point_expire_date_end: new Date(this.dataQuery.point_expire_date_end ?? ""),
                check_corporation_code: this.dataQuery.check_corporation_code
            },
        };
    },
    };
</script>
