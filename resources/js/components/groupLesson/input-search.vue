<template>
  <div class="col-md-10">
    <form method="GET" :action="url" class="input-search">
      <input type="hidden" name="limit" :value="pageLimit" />
      <div class="input-group" ref="input-group">
        <input
          name="search_input"
          placeholder="検索"
          class="form-control"
          type="text"
          :value="dataQuery.search_input"
        />
        <div
          class="dropdown-menu dropdown-menu-right search-popup"
          style="width: 100%; padding: 10px 10px"
          role="menu"
        >
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
              <div style="width: 150px" class="d-inline-block">
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
              <div style="width: 150px" class="d-inline-block">
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
              <div style="width: 150px" class="d-inline-block">
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
              <div style="width: 150px" class="d-inline-block">
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
        </div>

        <button
          type="button"
          class="btn btn-sm dropdown-toggle bg-gray-100"
          data-toggle="dropdown"
          aria-expanded="false"
        >
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
  mounted() {
    console.log(this.dataQuery);
  },
  methods: {
    onchangeDP() {
      this.$refs["input-group"].classList.value =
        this.$refs["input-group"].classList.value + "show";
    },
  },
};
</script>
