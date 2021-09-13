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

import PopupAlert from "./components/common/popupAlert.vue"
import DataEmpty from "./components/common/dataEmpty.vue"
import DeleteItem from "./components/common/deleteItem.vue"
import LimitPageOption from "./components/common/limitPageOption.vue"
import InputSearch from "./components/common/inputSearch.vue"
import PageSize from "./components/common/pageSize.vue"
import TeacherAdd from "./components/teacher/add.vue"
Vue.use(VeeValidate, {
    useConstraintAttrs: false
});
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
        TeacherAdd
    },
    methods: {},
    mounted() {}
});
