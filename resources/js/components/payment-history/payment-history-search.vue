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
                <div class="dropdown dropdown-lg" ref="input-group">
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
                                            v-on:change="onchangeDP"
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
                                            v-on:change="onchangeDP"
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
                                            v-on:change="onchangeDP"
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
                                            v-on:change="onchangeDP"
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
                                            v-on:change="onchangeDP"
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
                                            v-on:change="onchangeDP"
                                        ></date-picker>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>学習者番号</label>
                                <div class="input text">
                                    <input type="text" name="student_id" class="form-control input-sm" placeholder="学習者番号を入力してください" :value="dataQuery.student_id">
                                </div>
                            </div>  

                            <div class="form-group">
                                <label>学習者名</label>
                                <div class="input text">
                                    <input type="text" name="student_name" class="form-control input-sm" placeholder="学習者名を入力してください" :value="dataQuery.student_name">
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
                                <label>支払方法</label>
                                <div class="input text">
                                    <select
                                        class="form-control"
                                        name="payment_way"
                                        v-model="dataQuery.payment_way"
                                        v-validate="'required'"
                                    >
                                        <option></option>
                                        <option :value="key" v-for="(value, key) in paymentWays">
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
        props: ["url", "pageLimit", "dataQuery", "paymentWays"],
        mounted() {
            
        },
        data() {
            return {
                dataQueryEx: {
                    payment_date_start: new Date(this.dataQuery.payment_date_start ?? ""),
                    payment_date_end: new Date(this.dataQuery.payment_date_end ?? ""),
                    begin_date_start: new Date(this.dataQuery.begin_date_start ?? ""),
                    begin_date_end: new Date(this.dataQuery.begin_date_end ?? ""),
                    point_expire_date_start: new Date(this.dataQuery.point_expire_date_start ?? ""),
                    point_expire_date_end: new Date(this.dataQuery.point_expire_date_end ?? ""),
                },
            };
        },
        methods: {
            onchangeDP() {
                this.$refs['input-group'].classList.value = this.$refs['input-group'].classList.value + 'show';
            }
        }
    };
</script>
