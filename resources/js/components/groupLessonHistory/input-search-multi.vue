<template>
    <div class="col-md-10">
        <form method="GET" :action="url" class="input-search">
            <input type="hidden" name="limit" :value="pageLimit"/>
            <div class="input-group" ref="input-group">
                <input
                        name="search_input"
                        placeholder="検索"
                        class="form-control"
                        type="text"
                        :value="dataQuery.search_input"
                />
                <div class="dropdown-menu dropdown-menu-right search-popup" v-on:click="hideShow"
                     style="width: fit-content;padding: 10px 10px;" role="toolbar">

                    <div class="form-group">
                        <label for="test_name">レッスン日時 </label>
                        <div class="input text text-nowrap">
                            <div style="width: 150px" class="d-inline-block">
                                <date-picker
                                        v-model="timeFrom"
                                        :format="'YYYY/MM/DD HH:mm'"
                                        type="datetime"
                                        v-on:change="onchangeDP"
                                ></date-picker>
                                <input type="hidden" name="time_from"
                                       :value="timeFrom === null? '' : timeFrom.getTime()/1000">

                            </div>
                            <div class="d-inline-block">
                                ～

                            </div>
                            <div style="width: 150px" class="d-inline-block">
                                <date-picker
                                        v-model="timeTo"
                                        :format="'YYYY/MM/DD HH:mm'"
                                        type="datetime"
                                        v-on:change="onchangeDP"
                                ></date-picker>
                                <input type="hidden" name="time_to"
                                       :value="timeTo === null? '' :timeTo.getTime()/1000">

                            </div>


                        </div>
                    </div>



                    <button type="submit" class="btn btn-primary width-100">検索</button>
                </div>
                <button type="button" class="btn btn-sm dropdown-toggle bg-gray-100"
                        data-toggle="dropdown" aria-expanded="true">
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
        mounted() {

        },
        data() {
            return {
                timeFrom: (this.dataQuery.time_from === null || this.dataQuery.time_from === undefined) ? null : new Date(this.dataQuery.time_from * 1000),
                timeTo: (this.dataQuery.time_to === null || this.dataQuery.time_to === undefined) ? null : new Date(this.dataQuery.time_to * 1000)
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
