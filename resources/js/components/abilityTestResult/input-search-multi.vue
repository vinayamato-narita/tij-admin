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
                        <label for="student_id">学習者ID</label>
                        <div class="input text">
                            <input type="text" name="student_id" id="student_id" class="form-control input-sm"
                                   placeholder="学習者ID" :value="dataQuery.student_id">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="student_name">学習者名 </label>
                        <div class="input text">
                            <input type="text" name="student_name" id="student_name" class="form-control input-sm"
                                   placeholder="学習者名 " :value="dataQuery.student_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="test_name">実力テスト名</label>
                        <div class="input text">
                            <input type="text" name="test_name" id="test_name"
                                   class="form-control input-sm" placeholder="実力テスト名" :value="dataQuery.test_name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="test_name">受講日時 </label>
                        <div class="input text text-nowrap">
                            <div style="width: 150px" class="d-inline-block">
                                <date-picker
                                        v-model="testStartTimeForm"
                                        :format="'YYYY/MM/DD HH:mm'"
                                        type="datetime"
                                        v-on:change="onchangeDP"
                                ></date-picker>
                                <input type="hidden" name="test_start_time_form"
                                       :value="testStartTimeForm === null? '' : testStartTimeForm.getTime()/1000">

                            </div>
                            <div class="d-inline-block">
                                ～

                            </div>
                            <div style="width: 150px" class="d-inline-block">
                                <date-picker
                                        v-model="testStartTimeTo"
                                        :format="'YYYY/MM/DD HH:mm'"
                                        type="datetime"
                                        v-on:change="onchangeDP"
                                ></date-picker>
                                <input type="hidden" name="test_start_time_to"
                                       :value="testStartTimeTo === null? '' :testStartTimeTo.getTime()/1000">

                            </div>


                        </div>
                    </div>

                    <div class="form-group">

                        <label>ステータス</label>
                        <div class="col-sm-12">

                            <label class="radio-inline" v-for="(status , index) in this.status">
                                <input type="radio" name="status" :id="'status_' + index" :value="index"
                                       v-model="statusModel">
                                {{status}}
                                &nbsp;&nbsp;&nbsp;
                            </label>
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
                status: {
                    'NONE': 'なし',
                    'WAITING_EVALUATION': '評価待ち',
                    'UNDER_EVALUATION': '評価中',
                    'ALREADY': '済',
                },
                statusModel: this.dataQuery.status ?? 'NONE',
                testStartTimeForm: (this.dataQuery.test_start_time_form === null || this.dataQuery.test_start_time_form === undefined) ? null : new Date(this.dataQuery.test_start_time_form * 1000),
                testStartTimeTo: (this.dataQuery.test_start_time_to === null || this.dataQuery.test_start_time_to === undefined) ? null : new Date(this.dataQuery.test_start_time_to * 1000)
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
