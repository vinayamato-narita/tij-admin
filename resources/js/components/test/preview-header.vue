<template>
    <header class="navbar exam-navbar">
        <p v-if="test.test_type === 2 || test.test_type === 1" class="text-accent">残り時間　<span v-if="countDown">
                <countdown-timer :hours='timeRemain.h' :minutes="timeRemain.m" :seconds="timeRemain.s" :test="test"
                                 :testResultId="testResultId" inline-template>
                <a style="width:40px;">
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
        <div class="navbar-spacer"></div>
        <button class="navbar-toggler btn btn-icon ms-3" type="button" mdui-drawer="{target: '#examDrawer'}"><i
                class="icon bi-list"></i></button>
    </header>

</template>

<script>
    import countdownTimer from './count-down-timer.vue'

    export default {
        name: "exam-header",
        props: ['dashboardIndex', 'timeRemain', 'test', 'countDown', 'testResultId', 'titleCode'],
        components: {
            countdownTimer
        },
        data: () => {
            return {}
        },
        computed: {},
        mounted: function () {
        }
    }
</script>

<style scoped>

</style>
