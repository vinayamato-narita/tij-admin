<template>

    <div class="c-body answer-detail">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            実力テスト回答詳細


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">実力テスト情報


                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card " style="border-top: inset">
                                                <div class="p-2 cursor-pointer">

                                                    <h5>
                                                        {{ testResult.test.test_name }}
                                                    </h5></div>
                                                <div v-for="(tq, tqIndex) in testQuestions" class="p-2 cursor-pointer tq-navi" v-on:click="setTestQuestion(tqIndex)"
                                                     style="margin-left: 10px">

                                                    <i class="list-item-icon bi-check-circle-fill"
                                                       style="color: #6CBB5A" v-if="tq.is_answered"></i>
                                                    <i class="list-item-icon bi-check-circle-fill"
                                                       style="color: #E0E0E0;" v-if="!tq.is_answered"></i>
                                                    <span style="margin-left: 5px" >

                                                        {{ tq.navigation }}

                                                    </span>
                                                </div>

                                            </div>


                                        </div>


                                        <div class="col-md-9">
                                            <div class="card " style="border-top: inset">
                                                <div class="p-2 font-weight-bold">

                                                    <h5>
                                                        {{ testResult.test.test_name }}
                                                    </h5>
                                                </div>

                                                <div class=""
                                                     v-for="(subQuestion, index) in this.testQuestionCurr.test_sub_questions">
                                                    <div :class="['quiz-card', subQuestion.is_right ? 'right-card' : 'wrong-card',  'm-3']">
                                                        <div class="m-3">
                                                            <div class="tit font-weight-bold d-inline-block">
                                                           <span>
                                                               設問{{ ++index }}
                                                           </span>

                                                            </div>
                                                            <p v-if="!subQuestion.is_right"
                                                               class="result wrong d-inline-block">
                                                                <i class="bi-x-lg"></i>

                                                            </p>

                                                            <p v-if="subQuestion.is_right"
                                                               class="result right d-inline-block">
                                                                <i class="bi-check-lg"></i>

                                                            </p>
                                                            <nl2br tag="p" :text="subQuestion.sub_question_content"
                                                                   v-if="subQuestion.sub_question_content">

                                                            </nl2br>
                                                            <div class="quiz-body">
                                                                <ul class="list-unstyled">
                                                                    <li class="quiz d_ra position-relative" v-for="(answer, answerIndex) in subQuestion['answers']">
                                                                        <label style="margin-bottom: 0px">
                                                                            <input v-if="answer === subQuestion.choiced_answer"
                                                                                    checked="checked"
                                                                                    style="position: absolute; visibility: hidden;"
                                                                                    type="radio"
                                                                                    disabled="disabled">
                                                                            <input v-if="answer !== subQuestion.choiced_answer"
                                                                                   style="position: absolute; visibility: hidden;"
                                                                                   type="radio"
                                                                                   disabled="disabled">
                                                                            <span> {{ ++answerIndex}} </span>
                                                                            <i>{{ answer }}</i>
                                                                        </label>
                                                                        <span class="judge"></span>
                                                                    </li>

                                                                </ul>
                                                                <div  class="comment">
                                                                    <h4 :class="[subQuestion.is_right ? 'right' : 'wrong']">正解： {{subQuestion['answers'].findIndex((element) => element === subQuestion.answer1) + 1}}
                                                                    </h4>
                                                                </div>

                                                            </div>
                                                        </div>


                                                    </div>


                                                </div>


                                            </div>

                                        </div>


                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


</template>

<script>
    export default {
        name: "answer-detail",
        data() {
            return {
                testQuestionCurr: this.testQuestions[0]
            }
        },
        methods : {
            setTestQuestion(index) {
                this.testQuestionCurr = this.testQuestions[index];
            },
        },
        props: ['testResult', 'testQuestions']
    }
</script>

<style scoped>

</style>
