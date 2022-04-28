<template>
<div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            グループレッスンスケジュール管理
                        </h5>
                    </div>
                </div>
                <div>
                    <vue-cal :selected-date="selectedDate"
                             :locale="'ja'"
                             :time-from="0"
                             :time-to="24 * 60"
                             :disable-views="['years', 'year', 'month', 'day']"
                             hide-view-selector
                             :editable-events="{ title: false, drag: false, resize: true, delete: false, create: false }"
                             :events="events"
                             class=""
                             @cell-dblclick="cellDblclick($event)"
                             @view-change="viewChange($event)"
                             @ready="ready($event)"
                             :on-event-dblclick="onEventDblclick">
                          <!-- Custom arrow -->
                          <template v-slot:arrow-prev="{ arrow, view }">
                            <i class="angle"></i>前週
                          </template>
                          <template v-slot:arrow-next="{ arrow, view }">
                            次週<i class="angle"></i>
                          </template>

                          <!-- Custom title -->
                          <template v-slot:title="{ title, view }">
                            <span>{{ view.startDate.format('YYYY年M月') }}</span>
                            <span v-if="view.startDate.format('MMMM YYYY') != view.endDate.format('MMMM YYYY')">{{ view.endDate.format(' - M月') }}</span>
                          </template>

                          <!-- Custom weekday-heading -->
                          <template v-slot:weekday-heading="{ heading, view }">
                            <span>{{ heading.date.format('D（dddd）') }}</span>
                          </template>

                          <!-- Custom cells -->
                          <template v-slot:cell-content="{ cell, view, events, goNarrower }">
                          </template>

                          <!-- Alternatively to custom cells if you just want custom no-event text: -->
                          <template v-slot:no-event><span></span></template>
                    </vue-cal>
                </div>
            </div>
        </main>
        <loader :flag-show="flagShowLoader"></loader>
        <edit-schedule-modal :selected-event="selectedEvent" :bought-course="boughtCourse" :selected-time="selectedTime" :course-data="courseData"></edit-schedule-modal>
    </div>
</template>

<script  type="text/javascript">
import VueCal from 'vue-cal'
import 'vue-cal/dist/i18n/ja.js'
import 'vue-cal/dist/vuecal.css'
import axios from "axios";
import Loader from "../common/loader.vue";
import EditScheduleModal from "./edit-schedule-modal";

export default {
    components: {
        Loader,
        VueCal,
        EditScheduleModal
    },
    props: [],
    mounted() {
    },
    data() {
        return {
            baseUrl: Laravel.baseUrl,
            flagShowLoader : false,
            selectedDate : new Date(),
            selectedEvent : null,
            selectedTime : new Date(),
            courseData : null,
            events: [
              // events.
            ],
            boughtCourse: []
        }
    },
    methods :{
        cellDblclick(event) {
            this.selectedEvent = null
            this.selectedTime = event
            this.$modal.show('edit-schedule-modal');
        },
        ready(event) {
            console.log(event)
            this.getSchedule(event)
        },
        viewChange(event) {
            console.log(event)
            this.getSchedule(event)
        },
        onEventDblclick (event, e) {
            // Prevent navigating to narrower view (default vue-cal behavior).
            e.stopPropagation()
            e.preventDefault()

            this.selectedEvent = event
            this.selectedTime = event.start
            this.$modal.show('edit-schedule-modal');
        },
        getSchedule (event) {
            let that = this;
            axios.get(that.baseUrl + '/groupSchedule/getSchedule', {
                params: {
                    startDate : event.startDate,
                    endDate : event.endDate,
                }
            })
            .then(function (response) {
                console.log(response)
                that.events = response.data.data
                that.boughtCourse = response.data.boughtCourse
            })
            .catch(function (error) {
            });
        }
    }
};
</script>
