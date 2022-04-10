<template>
    <nav id="examDrawer" class="exam-drawer">
        <div class="action">
            <a :href="backUrl"><i class="icon bi-arrow-left-short"></i></a>
            <span>戻る</span>
        </div>
        <div class="status">

            <p class="time" v-if="test.test_type === 2 || test.test_type === 1">残り時間<span v-if="countDown">
                <countdown-timer :hours='timeRemain.h' :minutes="timeRemain.m" :seconds="timeRemain.s" :test="test" :testResultId="testResultId" inline-template>
                <a  style="width:40px;">
                    {{ timeLeft }}
                </a>
            </countdown-timer>
            </span>
                <span v-if="!countDown">

                <a style="width:40px;">
                    {{ timeRemain.h}}:{{timeRemain.m }}:{{ timeRemain.s}}
                </a>
            </span>
            </p>
        </div>

        <ul class="list list-dense">
            <li class="subheader"> {{ test.test_name }}</li>
            <li class="list-item" v-for="(item, index) in testQuestions" v-on:click="gotoQuestion(index)">
                <i class="list-item-icon bi-check-circle-fill" v-if="navigationWatcher.filter(x => x.testQuestionId === item.test_question_id).length !== 0 && navigationWatcher.filter(x => x.testQuestionId === item.test_question_id)[0].isCheckAll"></i>
                <i class="list-item-icon bi-check-circle-fill is-grey" v-if="navigationWatcher.filter(x => x.testQuestionId === item.test_question_id).length === 0 ||
                 !navigationWatcher.filter(x => x.testQuestionId === item.test_question_id)[0].isCheckAll" ></i>
                <span class="list-item-content list-item-one-line">{{ item.navigation}}</span>
            </li>
        </ul>
    </nav>

</template>

<script>
    import countdownTimer from './count-down-timer.vue'
    export default {
        name: "exam-nav",
        props : ['dashboardIndex', 'timeRemain', 'test', 'testQuestions', 'countDown', 'testResultId', 'backUrl'],
        components : {
            countdownTimer
        },
        created () {
        },
        mounted() {
            this.$root.$on("handleCheckNavigation", (navigationWatcher) => {
                this.navigationWatcher = navigationWatcher;
            });
        },
        methods : {
            gotoQuestion(index) {
                this.$root.$emit("gotoQuestion", index);

            }
        },
        data : () => {
            return {
                navigationWatcher : []
            }
        },
        computed: {
        },
    }
</script>

<style scoped>

</style>
