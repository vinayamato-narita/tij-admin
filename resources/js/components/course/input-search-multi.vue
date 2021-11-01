<template>
    <div class="col-md-10">
        <form method="GET" :action="url" class="input-search">
            <input type="hidden" name="limit" :value="pageLimit" />
            <div class="input-group">
                <input
                        name="search_input"
                        placeholder="検索"
                        class="form-control"
                        type="text"
                        :value="dataQuery.search_input"
                />
                <div class="dropdown-menu dropdown-menu-right search-popup"  style="width: 100%;padding: 10px 10px" role="menu">
                        <div class="form-group">
                            <label for="course_id">コースID</label>
                            <div class="input text">
                                <input type="text" name="course_id" id="course_id" class="form-control input-sm" placeholder="コースID" :value="dataQuery.course_id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="course_name">コース名</label>
                            <div class="input text">
                                <input type="text" name="course_name" id="course_name" class="form-control input-sm" placeholder="コース名" :value="dataQuery.course_name">
                            </div>
                        </div>
                    <div class="form-group">
                        <label for="paypal_item_number">商品コード</label>
                        <div class="input text"><input type="text" name="paypal_item_number" id="paypal_item_number"
                                                       class="form-control input-sm" placeholder="商品コード" :value="dataQuery.paypal_item_number">
                        </div>
                    </div>
                        <div class="form-group">
                            <label for="campaign_code">キャンペーンコード</label>
                            <div class="input text"><input type="text" name="campaign_code" id="campaign_code" class="form-control input-sm" placeholder="キャンペーンコード" :value="dataQuery.campaign_code">
                            </div>
                        </div>
                        <div class="form-group">

                            <label>公開状況</label>
                            <div class="col-sm-12">
                                <label class="radio-inline">
                                    <input type="radio" name="is_show" id="is_show_2" value="2" v-if="dataQuery.is_show == 2 "  checked> 全て
                                    <input type="radio" name="is_show" id="is_show_2" value="2" v-if="dataQuery.is_show != 2 ">
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_show" id="is_show_1" value="1" v-if="dataQuery.is_show == 1" checked>
                                    <input type="radio" name="is_show" id="is_show_1" value="1" v-if="dataQuery.is_show != 1"> 公開中
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_show" id="is_show_0" value="0" v-if="dataQuery.is_show == 0" checked>
                                    <input type="radio" name="is_show" id="is_show_0" value="0" v-if="dataQuery.is_show != 0"> 非公開
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary width-100">検索</button>
                </div>

                <button type="button" class="btn btn-sm dropdown-toggle bg-gray-100" data-toggle="dropdown" aria-expanded="false" >
                    <span class="caret"></span>
                </button>
                <span class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </span>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ["url", "pageLimit", "dataQuery"],
        mounted() {}
    };
</script>
