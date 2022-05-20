<template>
    <div class="col-md-10">
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
                        <div class="dropdown-menu dropdown-menu-right search-popup"  style="padding: 10px 10px" role="menu">
                            <form method="GET" :action="url">
                                <input type="hidden" name="limit" :value="pageLimit" />
                                <div class="form-group">
                                    <label for="course_id">コースID</label>
                                    <div class="input text w-310">
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
                                    <label for="paypal_item_number">コースコード</label>
                                    <div class="input text"><input type="text" name="paypal_item_number" id="paypal_item_number"
                                                                   class="form-control input-sm" placeholder="コースコード" :value="dataQuery.paypal_item_number">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>公開状況</label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="is_show" id="is_show_2" value="2" v-if="dataQuery.is_show == 2 || dataQuery.is_show === undefined"  checked>
                                            <input type="radio" name="is_show" id="is_show_2" value="2" v-if="dataQuery.is_show != 2 &&  dataQuery.is_show !== undefined">全て

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
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary width-100">検索</button>
                                </div>                       
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
    </div>
</template>

<script>
    export default {
        props: ["url", "pageLimit", "dataQuery"],
        mounted() {}
    };
</script>
