<template>
    <div>

        <main class="container pb-4">

            <h1 class="tit-1 mt-2 mt-md-0 mb-4"> {{ test.test_name}}</h1>

            <div class="quiz-item">
                <div class="quiz-head">
                    <h2 v-if="currTestQuestion">{{currTestQuestion['navigation']}}</h2>
                </div>
                <div class="quiz-body">
                    <nl2br v-if="typeof (currTestQuestion) !== 'undefined' && currTestQuestion['question_content'] !== ''"
                           tag="p" :text="currTestQuestion['question_content']">

                    </nl2br>

                </div>
                <div style="text-align: center;
    margin-bottom: 25px;" v-if="currTestQuestion.file">
                    <img :src="currTestQuestion.file.azure_storage_path" width="50%" v-if="checkFileType(currTestQuestion.file.file_name_original) == 'image'">


                    <a :href="currTestQuestion.file.azure_storage_path" style="color: #6CBB5A;
    text-decoration: underline;
}" v-if="checkFileType(currTestQuestion.file.file_name_original) == 'video' || checkFileType(currTestQuestion.file.file_name_original) == 'mp3'" target="_blank">{{ currTestQuestion.file.file_name_original }}</a>



                </div>


            </div>
            <!-- <div class="quiz-card"> -->
            <div v-if="typeof(currTestQuestion) !== 'undefined'">
                <div class="quiz-card" v-for="(subQuestion, index) in currTestQuestion.test_sub_questions"
                     :key="'quiz-card' + subQuestion.test_sub_question_id">
                    <div class="quiz-head">
                        <div class="wrap">
                            <h4 class="title">設問 {{ ++index}}</h4>
                            <p class="result"><i class="bi-check-lg"></i></p>
                            <div class="spacer"></div>
                            <p class="tips">配点：{{ subQuestion.score}}点</p>
                        </div>
                        <nl2br v-if="subQuestion['sub_question_content'] !== ''" tag="p" class="desc"
                               :text="subQuestion['sub_question_content']">

                        </nl2br>
                    </div>
                    <div class="quiz-body">
                        <ul>
                            <li class="quiz d_ra" v-for="(answer, indexAnswer) in subQuestion.mixedAnswer">
                                <label v-if="answer !== ''"
                                       v-on:click="setSubQuestionAnswer(subQuestion.test_sub_question_id, answer)">

                                    <input type="radio" v-if="subQuestionAnswer.filter(x => x.testSubQuestionId === subQuestion.test_sub_question_id).length === 0
                            || answer !== subQuestionAnswer.filter(x => x.testSubQuestionId === subQuestion.test_sub_question_id)[0].answer "
                                           :name="'qs' + subQuestion.test_sub_question_id" :value="answer"
                                           :id="subQuestion.test_sub_question_id">
                                    <input type="radio" v-if="subQuestionAnswer.filter(x => x.testSubQuestionId === subQuestion.test_sub_question_id).length !== 0
                            && answer === subQuestionAnswer.filter(x => x.testSubQuestionId === subQuestion.test_sub_question_id)[0].answer"
                                           checked="" :name="'qs' + subQuestion.test_sub_question_id" :value="answer"
                                           :id="subQuestion.test_sub_question_id">
                                    <span>
                                {{ ++indexAnswer }}
                            </span>
                                    <i>{{answer}}</i>
                                </label>
                                <span class="judge" v-if="answer !== ''"></span>
                            </li>
                        </ul>
                        <div class="comment">
                            <h4>正解：１</h4>
                            <p>
                                解説：解説をここに表示します解説をここに表示します解説をここに表示します解説をここに表示します解説をここに表示します解説をここに表示します解説をここに表示します解説をここに表示します解説をここに表示します解説をここに表示します解説をここに表示します解説をここに表示します</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="quiz-card"> -->

            <div class="d-flex justify-between mt-5 mb-3">
                <a class="btn btn-primary btn-lg" v-on:click="prev" v-if="currIndex > 0">前へ</a>
                <a class="btn btn-lg" disabled="" v-if="currIndex === 0">前へ</a>
                <button class="btn btn-primary btn-lg" v-on:click="next"
                        v-if="currIndex < this.testQuestions.length - 1 && this.ajaxProceed ">次へ
                </button>
                <button class="btn btn-primary btn-lg" disabled="" v-on:click="next"
                        v-if="currIndex < this.testQuestions.length - 1 && !this.ajaxProceed ">次へ

                </button>
<!--                <a class="btn btn-red btn-lg" v-if="currIndex === this.testQuestions.length - 1 && this.ajaxProceed"
                   v-on:click="submit">提出</a>
                <a class="btn btn-red btn-lg" disabled=""
                   v-if="currIndex === this.testQuestions.length - 1 && !this.ajaxProceed">提出</a>-->
            </div>


        </main>

        <!--    <script src="./asserts/js/common.js"></script>

            <div class="selection_bubble_root" style="display: none;"></div>-->
    </div>





</template>

<script>
    import axios from 'axios';

    export default {
        name: "preview",
        props: ['test', 'testQuestions', 'testResult', 'subQuestionAnswerProp'],
        data: () => {
            return {
                testQuestionInstances: [],
                currTestQuestion: {},
                currIndex: 0,
                subQuestionAnswer: [],
                ajaxProceed: true,
                isSubmit: false,
                navigationWatcher: [],
                imageExtensions : ['jpg' , 'jpeg' , 'jfif' , 'pjpeg' , 'pjp', "png", 'svg', 'webp'],
                videoExtensions : ['WEBM', 'MPG', 'MP2', 'MPEG', 'MPE', 'MPV', 'OGG', 'MP4', 'M4P', 'M4V', 'AVI', 'WMV', 'MOV', 'QT', 'FLV', 'SWF', 'AVCHD'],
                mp3Extensions: ['MP3']
            }
        },
        created() {
            if (this.subQuestionAnswerProp != null && this.subQuestionAnswerProp.length > 0) {
                this.subQuestionAnswer = this.subQuestionAnswerProp;

            }

            this.testQuestionInstances = this.testQuestions;
            this.testQuestionInstances.forEach((tq) => {
                tq.test_sub_questions.forEach((e) => {
                    var mixArr = ([e['answer1'], e['answer2'], e['answer3'], e['answer4']])
                        .filter(x => x !== "" &&  x !== null).sort(() => Math.random() - 0.5);
                    e.mixedAnswer = mixArr;
                })
            });
            this.currTestQuestion = this.testQuestionInstances[this.currIndex];
            this.testQuestionInstances.forEach((tq, tqIndex) => {
                var object = {
                    testQuestionId: tq.test_question_id,
                    childSubQuestionIds: [],
                    isCheckAll: false
                };
                tq.test_sub_questions.forEach((e) => {
                    object.childSubQuestionIds.push(e.test_sub_question_id);

                });
                this.navigationWatcher.push(object)
            });

        },
        mounted() {
            this.handleCheckNavigation();

            this.$root.$on("gotoQuestion", (index) => {
                this.currIndex = parseInt(index);
                this.updateData();
            });
        },
        methods: {
            checkFileType (fileName) {
                var re = /(?:\.([^.]+))?$/
                var ext = re.exec(fileName)[1];
                var returnType = '';
                if (this.imageExtensions.includes(ext.toLowerCase()) || this.imageExtensions.includes(ext.toUpperCase()))   returnType = 'image';
                if (this.videoExtensions.includes(ext.toLowerCase()) || this.videoExtensions.includes(ext.toUpperCase()))   returnType = 'video';
                if (this.mp3Extensions.includes(ext.toLowerCase()) || this.mp3Extensions.includes(ext.toUpperCase()))  returnType ='mp3';
                return returnType;

            },

            submit() {
                this.isSubmit = true;
                this.updateData();
            },
            setSubQuestionAnswer(testSubQuestionId, answer) {
                this.subQuestionAnswer = this.subQuestionAnswer.filter(x => x.testSubQuestionId !== testSubQuestionId);
                this.subQuestionAnswer.push({testSubQuestionId: testSubQuestionId, answer: answer});
            },
            next() {
                this.currIndex++;
                this.updateData();
            },
            prev() {
                this.currIndex--;
                this.updateData();
            },
            handleCheckNavigation() {
                this.navigationWatcher.forEach((testQuestion) => {
                    var isCheckAll = true;
                    testQuestion.childSubQuestionIds.forEach((testSubQuestionId) => {
                        if (this.subQuestionAnswer.filter(x => x.testSubQuestionId === testSubQuestionId).length === 0)
                            isCheckAll = false;

                    });
                    testQuestion.isCheckAll = isCheckAll;
                });
                this.$root.$emit("handleCheckNavigation", this.navigationWatcher);


            },
            updateData() {

                this.ajaxProceed = false;
                this.currTestQuestion = this.testQuestions[this.currIndex];
                this.ajaxProceed = true;
                this.handleCheckNavigation();
            }
        }

    }
</script>

<style scoped>

</style>
