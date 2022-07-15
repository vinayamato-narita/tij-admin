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
                    <div class="dropdown dropdown-lg" ref="input-group">
                        <button type="button" class="btn btn-sm dropdown-toggle btn-drop-detail" data-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right search-popup"  style="padding: 10px 10px" role="menu">
                            <form method="GET" :action="url">
                                <input type="hidden" name="limit" :value="pageLimit" />
                                <div class="form-group">
                                    <label for="test_name">レッスン日時 </label>
                                    <div class="input text text-nowrap">
                                        <div style="width: 150px" class="d-inline-block">
                                            <date-picker
                                                    v-model="time_from"
                                                    :format="'YYYY/MM/DD HH:mm'"
                                                    type="datetime"
                                                    v-on:change="onchangeDP"
                                                    :input-attr="{ name: 'time_from'}"
                                            ></date-picker>
                                        </div>
                                        <div class="d-inline-block">
                                            ～
                                        </div>
                                        <div style="width: 150px" class="d-inline-block">
                                            <date-picker
                                                    v-model="time_to"
                                                    :format="'YYYY/MM/DD HH:mm'"
                                                    type="datetime"
                                                    v-on:change="onchangeDP"
                                                    :input-attr="{ name: 'time_to'}"
                                            ></date-picker>
                                        </div>
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
    </div>
</template>

<script>
    export default {
        props: ["url", "pageLimit", "dataQuery"],
        mounted() {
            
        },
        data() {
            return {
                time_from: (this.dataQuery.time_from === null || this.dataQuery.time_from === undefined) ? null : new Date(this.dataQuery.time_from),
                time_to: (this.dataQuery.time_to === null || this.dataQuery.time_to === undefined) ? null : new Date(this.dataQuery.time_to)
            }
        },
        methods: {
            hideShow() {
                if (this.$refs['input-group'].classList.value.includes('show')) {
                    this.$refs['input-group'].classList.value = 'input-group show';
                }
                event.stopPropagation();
            },
            onchangeDP() {
                this.$refs['input-group'].classList.value = this.$refs['input-group'].classList.value + 'show';
            }
        }
    };
</script>
