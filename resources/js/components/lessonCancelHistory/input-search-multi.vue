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
                                    <label >キャンセル日時</label>
                                    <div class="input text">
                                        <date-picker
                                                :input-attr="{ name: 'cancelDateStart'}"
                                                v-model="cancelDateStart"
                                                :format="'YYYY/MM/DD'"
                                                style="width: 180px !important"
                                                type="date"
                                                v-on:change="onchangeDP"
                                        ></date-picker>

                                        ～

                                        <date-picker
                                                :input-attr="{ name: 'cancelDateEnd'}"
                                                v-model="cancelDateEnd"
                                                :format="'YYYY/MM/DD'"
                                                style="width: 180px !important"
                                                type="date"
                                                v-on:change="onchangeDP"
                                        ></date-picker>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label >レッスン日</label>
                                    <div class="input text">
                                        <date-picker
                                                :input-attr="{ name: 'lessonDateStart'}"
                                                v-model="lessonDateStart"
                                                :format="'YYYY/MM/DD'"
                                                style="width: 180px !important"
                                                type="date"
                                                :value="dataQuery.lessonDateStart"
                                                v-on:change="onchangeDP"
                                        ></date-picker>

                                        ～

                                        <date-picker
                                                :input-attr="{ name: 'lessonDateEnd'}"
                                                v-model="lessonDateEnd"
                                                :format="'YYYY/MM/DD'"
                                                style="width: 180px !important"
                                                type="date"
                                                v-on:change="onchangeDP"
                                        ></date-picker>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label >講師名</label>
                                    <div class="input text w-400">
                                        <input type="text" name="teacherName" id="teacher_name" class="form-control input-sm" placeholder="講師名を入力してください" :value="dataQuery.teacherName">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label >学習者番号</label>
                                    <div class="input text">
                                        <input type="text" name="studentId" id="student_id" class="form-control input-sm" placeholder="学習者番号を入力してください" :value="dataQuery.studentId">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label >学習者名</label>
                                    <div class="input text">
                                        <input type="text" name="studentName" id="studentName" class="form-control input-sm" placeholder="学習者名を入力してください" :value="dataQuery.studentName">
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
        mounted() {
        },
        data() {
            return {
                cancelDateStart : this.dataQuery.cancelDateStart ?  new Date(Date.parse(this.dataQuery.cancelDateStart)): '',
                cancelDateEnd : this.dataQuery.cancelDateEnd ?  new Date(Date.parse(this.dataQuery.cancelDateEnd)): '',
                lessonDateStart: this.dataQuery.lessonDateStart ?  new Date(Date.parse(this.dataQuery.lessonDateStart)): '',
                lessonDateEnd: this.dataQuery.lessonDateEnd ?  new Date(Date.parse(this.dataQuery.lessonDateEnd)): '',
                teacherName : null,
                studentId : null,
                isToggle : false

            }
        },
        methods :{
            submit() {
                this.$refs.form.submit();
            },
            toogle() {
                this.isToggle = !this.isToggle;
                if (this.isToggle)
                $('#popup-multi').show();
                else
                    $('#popup-multi').hide();

            },
            onchangeDP() {
                this.$refs['input-group'].classList.value = this.$refs['input-group'].classList.value + 'show';
            }
        }
    };
</script>
