<template>
    <div class="input-group pull-right" style="width: auto;">
        <form method="GET" :action="url" class="input-search" id="searchInput" style="width: 250px">
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
                        <form method="GET" :action="url"
                              ref="form"
                              @submit.prevent="search"
                        >
                            <input type="hidden" name="limit" :value="pageLimit" />
                            <input type="hidden" name="search_detail" value="1" />
                            <div class="form-group">
                                <label>レッスン日</label>
                                <div class="input text row">
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'lesson_date_start'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.lesson_date_start"
                                            type="date"
                                            v-on:change="onchangeDP"
                                            @panel-change="onchangeDP"
                                            @calendar-change="onchangeDP"
                                            @open="onchangeDP"
                                        ></date-picker>
                                    </div>
                                    <div class="col-md-1">
                                        <span>～</span>
                                    </div>
                                    <div class="col-md-5">
                                        <date-picker
                                            :input-attr="{ name: 'lesson_date_end'}"
                                            :format="'YYYY/MM/DD'"
                                            v-model="dataQueryEx.lesson_date_end"
                                            type="date"
                                            v-on:change="onchangeDP"
                                            @panel-change="onchangeDP"
                                            @calendar-change="onchangeDP"
                                            @open="onchangeDP"
                                        ></date-picker>
                                    </div>


                                </div>
                                <div
                                        v-if="over2Month"
                                        class="input-group is-danger text-center"
                                        role="alert"
                                >
                                    2ヶ月以上の期間で集計はできません。

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
        props: ["url", "pageLimit", "dataQuery", "checkMonth"],
        mounted() {

        },
        data() {
            return {
                dataQueryEx: {
                    lesson_date_start: new Date(this.dataQuery.lesson_date_start ?? ""),
                    lesson_date_end: new Date(this.dataQuery.lesson_date_end ?? ""),
                },
                over2Month : false,
            };
        },
        methods: {
            subtractMonths(numOfMonths, date = new Date()) {
                date.setMonth(date.getMonth() - numOfMonths);
                return date;
            },
            search() {
                let that = this;
                if (this.checkMonth && that.dataQueryEx.lesson_date_start && that.dataQueryEx.lesson_date_end) {
                    if (that.dataQueryEx.lesson_date_start < that.dataQueryEx.lesson_date_end) {
                        if (that.dataQueryEx.lesson_date_start <
                            that.subtractMonths(2, that.dataQueryEx.lesson_date_end)) {
                            that.over2Month = true;
                        } else {
                            this.$refs.form.submit();
                            that.over2Month = false;
                        }

                    }

                } else {
                    this.$refs.form.submit();
                    that.over2Month = false;

                }

            },
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
