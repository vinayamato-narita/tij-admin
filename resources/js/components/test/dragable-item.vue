<template>
    <modal name="drag-question-modal" :pivotY="0.1" :reset="true" :width="1000" :height="auto" :scrollable="true"
           :adaptive="true" :clickToClose="false" @before-open="getData">
        <div class="card">
            <div class="card-header"> 設問一覧

                <div class="float-right">
                    <button type="button" class="close" v-on:click="hide" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-10">
                    </div>

                </div>
                <div class="row mb-2">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-10">
                    </div>

                    <div class="tanemaki-table pd-t-7" style="width: 100%;">
                        <div v-if="dataList.length ==  0" class="data-empty-message text-center alert alert-danger">
                            該当データがありません

                        </div>
                        <table v-if="dataList.length > 0" class="table table-responsive-sm table-striped border">
                            <thead>
                            <tr>
                                <th class="text-center bg-gray-100 " style="width: 50px">
                                    <input type="checkbox" class=" checkbox"
                                           style="width: auto; height: auto; display: none;">
                                </th>
                                <th class="text-center text-md-left bg-gray-100">設問名</th>
                                <th class="text-center text-md-left bg-gray-100"></th>
                            </tr>
                            </thead>
                            <draggable v-model="dataList" tag="tbody" >
                                <tr v-for="(test_question, test_question_index) in dataList" :key="test_question.test_question_id" >
                                    <td class="text-center">
                                        <input type="checkbox" class=" checkbox" style="display: none;">
                                    </td>
                                    <td class="text-md-left">
                                        <div class="row">
                                            <div class="col-md-4">
                                                大問{{test_question.display_order}} {{test_question.question_content| truncate(30, '...')}}
                                            </div>

                                            <div class="col-md-2">
                                                小計{{test_question.total_score}}点

                                            </div>

                                        </div>
                                        <draggable v-model="test_question.test_sub_questions" tag="div">
                                            <div class="row draggable-test-sub-question" v-for="(test_sub_question, test_sub_question_index) in test_question.test_sub_questions" :key="test_sub_question.test_sub_question_id" >
                                                <div class="col-md-2">

                                                </div>
                                                <div class="col-md-4"  style="">
                                                    設問{{ getindexSee(test_sub_question.display_order) }}　{{test_sub_question.sub_question_content | truncate(30, '...')}}

                                                </div>
                                                <div class="col-md-5">
                                                    <input
                                                            type="number"
                                                            class="form-control h-30px"
                                                            :name="'question[' + test_question_index +']subQuestion[' + test_sub_question_index + '][score]'"
                                                            style="width: 100px"
                                                            v-model="test_sub_question.score"
                                                            v-on:change="updateSubTotal($event, test_question.test_question_id, test_sub_question.test_sub_question_id)"
                                                            v-validate="
                                                        'required|decimal|min_value:0|max_value:1000000000'
                                                    "
                                                    />
                                                    <div
                                                            class="input-group is-danger"
                                                            role="alert"

                                                    >
                                                        {{ errors.first("question[" + test_question_index +"]subQuestion["+ test_sub_question_index +"][score]") }}
                                                    </div>
                                                </div>

                                            </div>

                                        </draggable>
                                    </td>
                                    <td>

                                    </td>


                                </tr>
                            </draggable>
                            <tbody>


                            </tbody>

                        </table>
                    </div>


                </div>
                <div class="row mb-2">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-10">
                    </div>
                </div>
                <div class="form-actions text-center">
                    <div class="line"></div>
                    <div class="form-group">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-100 mr-2" v-on:click="submit">登録</button>
                            <a v-on:click="hide" class="btn btn-default w-100">閉じる</a></div>
                    </div>
                </div>


            </div>


        </div>
    </modal>

</template>

<script>
    import Loader from "./../../components/common/loader";

    export default {
        name: "drag-teacher-lesson-modal",
        components: {
            Loader,
        },
        data() {
            return {
                dataList: [],
                auto: 'auto',
                courseLessonIds: []
            };
        },
        props: ['id', 'detailUrl', 'listQuestionAttachUrl', 'listQuestionAttachUpdateUrl'],
        mounted() {
        },
        created: function () {
            this.getData();

        },
        methods: {
            updateSubTotal(event, testQuestionId, testSubquestionId) {
                var testQuestion = this.dataList.find(tq => tq.test_question_id === testQuestionId);

                var  newScore = 0;
                testQuestion.test_sub_questions.forEach((tsqs => {
                    newScore += parseInt(tsqs.score);
                }));

                this.dataList[this.dataList.indexOf(testQuestion)].total_score = newScore;

            },
            getindexSee($index){
                return $index++;
            },
            toPage(page) {
                this.currentPage = page;
                this.getData()

            },
            hide() {
                this.$modal.hide('drag-question-modal');
            },
            getData() {
                let that = this;
                axios.get(this.listQuestionAttachUrl, {
                    params: {
                        id: that.id,
                    }
                })
                    .then(function (response) {
                        that.dataList = response.data.dataList;
                        let messError = {
                            custom: {}
                        };
                        that.dataList.forEach((testQuestion, indexTestQuestion) => {
                            testQuestion.test_sub_questions.forEach((testSubQuestion, testSubQuestionIndex) => {
                                messError.custom["question[" + indexTestQuestion +"]subQuestion["+ testSubQuestionIndex +"][score]"] = {
                                    required: "点数を入力してください",
                                    decimal: "点数は半角数字を入力してください",
                                    min_value: "点数は1～1000000000 を入力してください",
                                    max_value: "点数は1～1000000000 を入力してください",

                                }
                            })

                        });
                        that.$validator.localize("en", messError);
                    })
                    .catch(function (error) {
                    });
            },
            submit() {
                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        let  that = this;
                        that.flagShowLoader = true;
                        let formData = new FormData();
                        formData.append('testQuestions', JSON.stringify(this.dataList));
                        axios
                            .post(that.listQuestionAttachUpdateUrl, formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                that.flagShowLoader = false;
                                window.location.href = that.detailUrl;


                            })
                            .catch((err) => {
                                switch (err.response.status) {
                                    case 422:
                                    case 400:
                                        this.errorsData = err.response.data;
                                        that.flagShowLoader = false;
                                        break;
                                    case 500:
                                        this.$swal({
                                            title: "失敗したデータを追加しました",
                                            icon: "error",
                                            confirmButtonText: "OK",
                                        }).then(function (confirm) {
                                        });
                                        that.flagShowLoader = false;
                                        break;
                                    default:
                                        break;
                                }
                            });
                    }
                });

            }
        },

    }
</script>

<style scoped>

</style>
