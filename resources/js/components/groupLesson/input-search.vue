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
                              <label for="course_name">コース名</label>
                              <div class="input text">
                                <input
                                  type="text"
                                  name="course_name"
                                  id="course_name"
                                  class="form-control input-sm"
                                  placeholder="コース名"
                                  :value="dataQuery.course_name"
                                />
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="course_name">開催決定日From</label>
                              <div class="input text">
                                <div class="d-inline-block w-300">
                                  <date-picker
                                    v-model="decide_date_start"
                                    :format="'YYYY/MM/DD HH:mm'"
                                    type="date"
                                    v-on:change="onchangeDP"
                                  ></date-picker>
                                  <input
                                    type="hidden"
                                    name="decide_date_start"
                                    :value="
                                      decide_date_start === null
                                        ? ''
                                        : decide_date_start.getTime() / 1000
                                    "
                                  />
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="course_name">開催決定日To</label>
                              <div class="input text">
                                <div class="d-inline-block w-310">
                                  <date-picker
                                    v-model="decide_date_end"
                                    :format="'YYYY/MM/DD HH:mm'"
                                    type="date"
                                    v-on:change="onchangeDP"
                                  ></date-picker>
                                  <input
                                    type="hidden"
                                    name="decide_date_end"
                                    :value="
                                      decide_date_end === null
                                        ? ''
                                        : decide_date_end.getTime() / 1000
                                    "
                                  />
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="course_name">申込期限From</label>
                              <div class="input text">
                                <div class="d-inline-block w-310">
                                  <date-picker
                                    v-model="reserve_end_date_start"
                                    :format="'YYYY/MM/DD HH:mm'"
                                    type="date"
                                    v-on:change="onchangeDP"
                                  ></date-picker>
                                  <input
                                    type="hidden"
                                    name="reserve_end_date_start"
                                    :value="
                                      reserve_end_date_start === null
                                        ? ''
                                        : reserve_end_date_start.getTime() / 1000
                                    "
                                  />
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="course_name">申込期限To</label>
                              <div class="input text">
                                <div class="d-inline-block w-310">
                                  <date-picker
                                    v-model="reserve_end_date_end"
                                    :format="'YYYY/MM/DD HH:mm'"
                                    type="date"
                                    v-on:change="onchangeDP"
                                  ></date-picker>
                                  <input
                                    type="hidden"
                                    name="reserve_end_date_end"
                                    :value="
                                      reserve_end_date_end === null
                                        ? ''
                                        : reserve_end_date_end.getTime() / 1000
                                    "
                                  />
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
<style lang="css" scoped>
.mx-datepicker {
  width: 100% !important;
}
</style>>
  
</style>
<script>
export default {
  data() {
    return {
      decide_date_start:
        this.dataQuery.decide_date_start === null ||
        this.dataQuery.decide_date_start === undefined
          ? null
          : new Date(this.dataQuery.decide_date_start * 1000),
      decide_date_end:
        this.dataQuery.decide_date_end === null ||
        this.dataQuery.decide_date_end === undefined
          ? null
          : new Date(this.dataQuery.decide_date_end * 1000),
      reserve_end_date_start:
        this.dataQuery.reserve_end_date_start === null ||
        this.dataQuery.reserve_end_date_start === undefined
          ? null
          : new Date(this.dataQuery.reserve_end_date_start * 1000),
      reserve_end_date_end:
        this.dataQuery.reserve_end_date_end === null ||
        this.dataQuery.reserve_end_date_end === undefined
          ? null
          : new Date(this.dataQuery.reserve_end_date_end * 1000),
    };
  },
  props: ["url", "pageLimit", "dataQuery"],
  mounted() {},
  methods: {
    onchangeDP() {
      this.$refs["input-group"].classList.value =
        this.$refs["input-group"].classList.value + "show";
    },
  },
};
</script>
