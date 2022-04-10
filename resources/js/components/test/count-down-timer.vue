<script>
    export default {
        props: {

            hours: {
                default: 0
            },

            minutes: {
                default: 0
            },

            seconds: {
                default: 0
            },
            test : {

            },
            testResultId : {

            },
            endpoint: {}

        },

        data() {
            return {
                hoursLeft: this.hours,
                minutesLeft: this.minutes,
                secondsLeft: this.seconds,
            }
        },

        methods: {

            resetTimer() {
                this.hoursLeft = this.hours;
                this.minutesLeft = this.minutes;
                this.secondsLeft = this.seconds;
            },

            zeroPad(input, length) {
                return (Array(length + 1).join('0') + input).slice(-length);
            }

        },

        mounted() {
            this.resetTimer();

            this.$nextTick(function () {
                window.setInterval(() => {
                    if (this.secondsLeft > 0) {
                        this.secondsLeft--;
                    }
                    else if (this.secondsLeft == 0 && this.minutesLeft > 0) {
                        this.secondsLeft = 59;
                        this.minutesLeft--;
                    }
                    else if (this.secondsLeft == 0 && this.minutesLeft == 0 && this.hoursLeft > 0) {
                        this.secondsLeft = 59;
                        this.minutesLeft = 59;
                        this.hoursLeft--;
                    }
                    else {

                        // Timer hits 0 do what you want to do here.
                    }
                },1000);
            })
        },
        computed: {
            timeLeft: function () {
                if (this.hours !== 0) {
                    return this.hoursLeft + ':' + this.zeroPad(this.minutesLeft, 2) + ':' + this.zeroPad(this.secondsLeft, 2);
                }
                else if (this.minutes !== 0) {
                    return this.minutesLeft + ':' + this.zeroPad(this.secondsLeft, 2);
                }
                else {
                    return this.secondsLeft;
                }
            },
        }

    }
</script>

