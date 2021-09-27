<template>
    <div class="col-md-10">
        <form method="GET" :action="url" class="input-search" ref="form" @submit="event.preventDefault()">
            <input type="hidden" name="limit" :value="pageLimit" />
            <div class="input-group">
                <input
                        name="search_input"
                        placeholder="検索"
                        class="form-control"
                        type="text"
                        :value="dataQuery.search_input"
                />
                <div class="dropdown-menu dropdown-menu-right search-popup"  id="popup-multi" style="width: 100%;padding: 10px 10px" role="menu">
                    <div class="form-group">
                        <label >キャンセル日時</label>
                        <div class="input text">
                            <date-picker
                                    :input-attr="{ name: 'cancelDateStart'}"
                                    v-model="cancelDateStart"
                                    :format="'YYYY/MM/DD'"
                                    style="width: 125px"
                                    type="date"
                            ></date-picker>

                            ～

                            <date-picker
                                    :input-attr="{ name: 'cancelDateEnd'}"
                                    v-model="cancelDateEnd"
                                    :format="'YYYY/MM/DD'"
                                    style="width: 125px"
                                    type="date"
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
                                    style="width: 125px"
                                    type="date"
                                    :value="dataQuery.lessonDateStart"
                            ></date-picker>

                            ～

                            <date-picker
                                    :input-attr="{ name: 'lessonDateEnd'}"
                                    v-model="lessonDateEnd"
                                    :format="'YYYY/MM/DD'"
                                    style="width: 125px"
                                    type="date"
                            ></date-picker>
                        </div>
                    </div>
                    <div class="form-group">
                        <label >講師名</label>
                        <div class="input text">
                            <input type="text" name="teacherName" id="teacher_name" class="form-control input-sm" placeholder="講師名を入力してください" :value="dataQuery.teacherName">
                        </div>
                    </div>

                    <div class="form-group">
                        <label >生徒番号</label>
                        <div class="input text">
                            <input type="text" name="studentId" id="student_id" class="form-control input-sm" placeholder="生徒番号を入力してください" :value="dataQuery.studentId">
                        </div>
                    </div>
                    <div class="form-group">
                        <label >生徒名</label>
                        <div class="input text">
                            <input type="text" name="studentName" id="studentName" class="form-control input-sm" placeholder="生徒名を入力してください" :value="dataQuery.studentName">
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary width-100" v-on:click="submit()">検索</button>
                </div>

                <button type="button" class="btn btn-sm dropdown-toggle bg-gray-100" v-on:click="toogle" aria-expanded="false" >
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

            }
        }
    };
</script>
