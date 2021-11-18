require('./bootstrap');
// require('@coreui/coreui/dist/js/coreui.bundle.min');

import Vue from "vue";
import VModal from "vue-js-modal";
import VeeValidate from "vee-validate";
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret, faPlus, faTrash } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/ja';
import Draggable from 'vuedraggable'


import Multiselect from 'vue-multiselect'

Vue.component('multiselect', Multiselect)
Vue.component('draggable', Draggable)

library.add(faUserSecret, faPlus, faTrash)
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.use(VeeValidate, {
    locale: "ja"
});

Vue.use(VModal);
Vue.use(VueSweetalert2);
import moment from 'moment-timezone'
Vue.filter("formatDateTime", function(value) {
    if (value) {
      return moment(String(value)).tz("Asia/Tokyo").format("YYYY-MM-DD HH:mm:ss");
    }
  });
  Vue.filter("formatDate", function(value) {
    if (value) {
      return moment(String(value)).tz("Asia/Tokyo").format("YYYY-MM-DD");
    }
  });
  Vue.filter("formatDateCourse", function(value) {
    if (value) {
      return moment(String(value)).tz("Asia/Tokyo").format("YYYY/MM/DD HH:mm");
    }
  });
Vue.config.productionTip = false
//Nl2br
import Nl2br from 'vue-nl2br'
Vue.component('nl2br', Nl2br)
Vue.use(DatePicker);

import PopupAlert from "./components/common/popup-alert.vue"
import DataEmpty from "./components/common/data-empty.vue"
import DeleteItem from "./components/common/delete-item.vue"
import LimitPageOption from "./components/common/limit-page-option.vue"
import InputSearch from "./components/common/input-search.vue"
import PageSize from "./components/common/page-size.vue"
import ChangeStatusAdmin from "./components/admin/change-status.vue"
import CreateAdmin from "./components/admin/create-admin.vue"
import EditAdmin from "./components/admin/edit-admin.vue"
import TeacherAdd from "./components/teacher/add.vue"
import CreateFaq from "./components/faq/create-faq.vue"
import EditFaq from "./components/faq/edit-faq.vue"
import TeacherEdit from "./components/teacher/edit.vue"
import TeacherShow from "./components/teacher/detail.vue"
import TextAdd from "./components/text/add.vue"
import TextShow from "./components/text/detail.vue"
import TextEdit from "./components/text/edit.vue"
import ChangeStatusNews from "./components/news/change-status.vue"
import CreateNews from "./components/news/create-news.vue"
import EditNews from "./components/news/edit-news.vue"
import EditLangNews from "./components/news/edit-lang-news.vue"
import LessonAdd from "./components/lesson/add.vue"
import LessonShow from "./components/lesson/detail.vue"
import LessonEdit from "./components/lesson/edit.vue"
import EditLangFaq from "./components/faq/edit-lang-faq.vue"
import EditInquiry from "./components/inquiry/edit-inquiry.vue"
import ModalTable from "./components/common/modal-table.vue"
import EditLangInquirySubject from "./components/inquirySubject/edit-lang.vue"
import EditInquirySubject from "./components/inquirySubject/edit-inquiry-subject.vue"
import CreateInquirySubject from "./components/inquirySubject/create-inquiry-subject.vue"
import LessonStatusIndex from "./components/lessonStatus/lesson-status-index.vue"
import InputSearchMulti from "./components/course/input-search-multi.vue"
import CancelHistorySearchMulti from "./components/lessonCancelHistory/input-search-multi.vue"
import CourseAdd from "./components/course/add"
import CourseSetAdd from "./components/course/set-add.vue"
import CourseShow from "./components/course/detail.vue"
import CourseSetShow from "./components/course/set-detail.vue"
import CourseEdit from "./components/course/edit.vue"
import CourseSetEdit from "./components/course/set-edit.vue"
import EditLangCourse from "./components/course/edit-lang-course.vue"
import StudentCreateComment from "./components/student/student-create-comment.vue"
import StudentEditComment from "./components/student/student-edit-comment.vue"
import ShowLessonHistory from "./components/student/show-lesson-history.vue"
import RemindMailShow from "./components/remindMailPatern/detail.vue"
import RemindMailEdit from "./components/remindMailPatern/edit.vue"
import CategoryAdd from "./components/category/add.vue"
import CategoryShow from "./components/category/detail.vue"
import CategoryEdit from "./components/category/edit.vue"
import CsvExport from "./components/csv/csv-export.vue"
import CreatePaymentHistory from "./components/student/create-payment-history.vue"
import EditPaymentHistory from "./components/student/edit-payment-history.vue"
import StudentSearch from "./components/student/student-search.vue"
import EditStudent from "./components/student/edit-student.vue"
import PaymentHistorySearch from "./components/payment-history/payment-history-search.vue"
import EditHistoryPayment from "./components/payment-history/edit-history-payment.vue"
import LessonSchedule from "./components/LessonSchedule/index-lesson-schedule.vue"
import ShowPointHistory from "./components/student/show-point-history.vue"
import EditRole from "./components/admin/edit-role.vue"
import EditLangCategory from "./components/category/edit-lang-category.vue"

new Vue({
    created() {
        // this.$validator.extend('required', {
        //   validate: function (value) {
        //     return value.trim() != '';
        //   }
        // })
        $(document).on('change', '.checkbox-type', function() {
            $(this).closest('form').submit();
        })
        this.$validator.extend('rfid_code', {
            validate: function(value) {
                return /^[A-Za-z0-9]{1,20}$/i.test(value.trim())
            }
        });
        this.$validator.extend('file_csv', {
            validate: function(value) {
                var ext = value.match(/\.([^\.]+)$/)[1];
                return (ext == "bin" || ext == "BIN");
            }
        });
        this.$validator.extend('file_csv', {
            validate: function(value) {
                var ext = value.match(/\.([^\.]+)$/)[1];
                return (ext == "CSV" || ext == "csv");
            }
        });
        this.$validator.extend('file_name_import', {
            validate: function(value) {
                let first3String = value.substring(0, 3);
                return first3String == "OUT";
            }
        });
        this.$validator.extend('file_name_import_return', {
            validate: function(value) {
                let first3String = value.substring(0, 2);
                return first3String == "IN";
            }
        });
        this.$validator.extend('number', {
            validate: function(value) {
                return /^[0-9]{1,20}$/i.test(value.trim())
            }
        });
        this.$validator.extend("password_rule", {
            validate: function(value) {
                return /^[A-Za-z0-9]*$/i.test(value);
            }
        });
        this.$validator.extend("is_hiragana", {
            validate: function(value) {
                return /^[ア-ン゛゜ァ-ォャ-ョーヴ　]*$/i.test(value);
            }
        });
        this.$validator.extend("email_format", {
            validate: function(value) {
                return /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/i.test(value);
            }
        });
        this.$validator.extend("management_number", {
            validate: function(value) {
                return /^[!-~]+$/i.test(value);
            }
        });
        this.$validator.extend("isKana", {
            validate: function(value) {
                var re = new RegExp(/^[A-Za-zア-ン゛゜ァ-ォャ-ョーヴ 　]+$/);
                return re.test(value);
            }
        });
        this.$validator.extend("skype", {
            validate: function(value) {
                var re = new RegExp(/^[a-zA-Z][a-zA-Z0-9\_\-\.\,\:]{5,31}$/i);
                return re.test(value);
            }
        });
        this.$validator.extend("login_id", {
            validate: function(value) {
                return /^[A-Za-z0-9]+$/i.test(value);
            }
        });
        this.$validator.extend("isTelephone", {
            validate: function(value) {
                return /^[0-9-.()]{1,20}$/i.test(value);
            }
        });
        this.$validator.extend("postcode", {
            validate: function(value) {
                return /^[0-9-ー]{0,10}$/.test(value);
            }
        });
        this.$validator.extend("payment_checkHankaku", {
            validate: function(value) {
                return /^[0-9０-９-]+$/i.test(value);
            }
        });
    },
    el: "#app",
    components: {
        PopupAlert,
        LimitPageOption,
        DeleteItem,
        DataEmpty,
        InputSearch,
        PageSize,
        ChangeStatusAdmin,
        CreateAdmin,
        EditAdmin,
        TeacherAdd,
        CreateFaq,
        EditFaq,
        TeacherEdit,
        TeacherShow,
        TextAdd,
        TextShow,
        TextEdit,
        ChangeStatusNews,
        CreateNews,
        EditNews,
        EditLangNews,
        LessonAdd,
        LessonShow,
        LessonEdit,
        EditLangFaq,
        EditInquiry,
        ModalTable,
        CsvExport,
        EditLangInquirySubject,
        EditInquirySubject,
        CreateInquirySubject,
        LessonStatusIndex,
        CourseAdd,
        CourseSetAdd,
        CourseShow,
        CourseSetShow,
        InputSearchMulti,
        CourseEdit,
        CourseSetEdit,
        StudentCreateComment,
        StudentEditComment,
        CancelHistorySearchMulti,
        ShowLessonHistory,
        RemindMailShow,
        CreatePaymentHistory,
        EditPaymentHistory,
        RemindMailEdit,
        CategoryAdd,
        CategoryShow,
        CategoryEdit,
        StudentSearch,
        EditStudent,
        PaymentHistorySearch,
        EditHistoryPayment,
        LessonSchedule,
        ShowPointHistory,
        EditRole,
        EditLangCategory,
        EditLangCourse

    },
    methods: {},
    mounted() {}
});
