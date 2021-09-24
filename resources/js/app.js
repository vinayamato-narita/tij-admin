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

import CsvExport from "./components/csv/csv-export.vue"

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
        LessonEdit,
        ModalTable,
        CsvExport,
        EditLangInquirySubject,
        EditInquirySubject,
        CreateInquirySubject,
        LessonStatusIndex
    },
    methods: {},
    mounted() {}
});
