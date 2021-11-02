<template>
  <div class="c-body">
    <main class="c-main pt-0">
      <div class="container-fluid">
        <div class="page-heading">
          <div class="page-heading-left">
            <h5>CSV出力</h5>
          </div>
        </div>
        <div class="fade-in">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <form class="basic-form">
                  <input
                    name="_token"
                    type="hidden"
                    v-model="csvExport._token"
                  />
                  <div class="card-header">
                    <h5 class="title-page">受注履歴</h5>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >受注年月日
                      </label>
                      <div class="col-md-2">
                        <date-picker
                          v-model="paymentDateStart"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                      <div
                        class="col-sm-1 text-center"
                        style="width: auto !important; padding-top: 8px"
                      >
                        ～
                      </div>
                      <div class="col-md-2">
                        <date-picker
                          v-model="paymentDateEnd"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >法人コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          placeholder="法人コード"
                          v-model="corporationCodePayment"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >商品コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          placeholder="商品コード"
                          v-model="productCode"
                        />
                      </div>
                    </div>

                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >得意先コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          placeholder="得意先コード"
                          v-model="customerCode"
                        />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="text-center">
                        <button
                          type="button"
                          class="btn btn-default w-100 mr-2"
                          @click="exportOrderHistory()"
                        >
                          出力
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <form class="basic-form">
                  <input
                    name="_token"
                    type="hidden"
                    v-model="csvExport._token"
                  />
                  <div class="card-header">
                    <h5 class="title-page">レッスン実績</h5>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >受講日
                      </label>
                      <div class="col-md-2">
                        <date-picker
                          v-model="exportLessonHistoryStart"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                      <div
                        class="col-sm-1 text-center"
                        style="width: auto !important; padding-top: 8px"
                      >
                        ～
                      </div>
                      <div class="col-md-2">
                        <date-picker
                          v-model="exportLessonHistoryEnd"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >受注番号
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="lessonResultNumber"
                          placeholder="受注番号"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >法人コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="lessonResultCampaign"
                          placeholder="法人コード"
                        />
                      </div>
                    </div>

                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >商品コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          placeholder="商品コード"
                          v-model="lessonResultProduct"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >得意先コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          placeholder="得意先コード"
                          v-model="lessonResultCustomer"
                        />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="text-center">
                        <button
                          type="button"
                          class="btn btn-default w-100 mr-2"
                          @click="exportLessonHistory()"
                        >
                          出力
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <form class="basic-form">
                  <input
                    name="_token"
                    type="hidden"
                    v-model="csvExport._token"
                  />
                  <div class="card-header">
                    <h5 class="title-page">SuperGrace法人会員用</h5>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >対象年月
                      </label>
                      <div class="col-md-2">
                        <date-picker
                          v-model="superGraceDate"
                          :format="'YYYY/MM'"
                          type="month"
                        ></date-picker>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >法人コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceCampaign"
                          placeholder="法人コード"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >企業名
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          placeholder="企業名"
                          v-model="superGraceCompany"
                        />
                      </div>
                    </div>

                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >得意先コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceCustomer"
                          placeholder="得意先コード"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >キャンペーンコード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceCampaignCode"
                          placeholder="キャンペーンコード"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >商品コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceProduct"
                          placeholder="商品コード"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >企業ID
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceProjectCode"
                          placeholder="企業ID"
                        />
                      </div>
                    </div>

                    <div class="form-group row ">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >法人名なしを含む
                      </label>

                      <div class="col-md-6 block-radio">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            value="0"
                            v-model="superGraceContainCompanyName"
                          />
                          <label class="form-check-label" for="radio1"
                            >はい</label
                          >
                        </div>
                        
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            value="1"
                            v-model="superGraceContainCompanyName"
                          />
                          <label class="form-check-label" for="radio2"
                            >いいえ</label
                          >
                        </div>
                      </div>
                    </div>

                    <div class="form-group row ">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >出力形式
                      </label>

                      <div class="col-md-5 block-radio">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            value="0"
                            v-model="superGraceDownloadType"
                          />
                          <label class="form-check-label" for="radio1"
                            >TXT</label
                          >
                        </div>
                        
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            name="radios"
                            value="1"
                            v-model="superGraceDownloadType"
                          />
                          <label class="form-check-label" for="radio2"
                            >CSV</label
                          >
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="text-center">
                        <button
                          type="button"
                          class="btn btn-default w-100 mr-2"
                          @click="exportSuperGrace()"
                        >
                          出力
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <form class="basic-form">
                  <input
                    name="_token"
                    type="hidden"
                    v-model="csvExport._token"
                  />
                  <div class="card-header">
                    <h5 class="title-page">SuperGrace一般会員用</h5>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >対象年月
                      </label>
                      <div class="col-md-2">
                        <date-picker
                          v-model="superGraceNormalDate"
                          :format="'YYYY/MM'"
                          type="month"
                        ></date-picker>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >法人コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceNormalCampaign"
                          placeholder="法人コード"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >企業名
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          placeholder="企業名"
                          v-model="superGraceNormalCompany"
                        />
                      </div>
                    </div>

                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >得意先コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceNormalCustomer"
                          placeholder="得意先コード"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >キャンペーンコード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceNormalCampaignCode"
                          placeholder="キャンペーンコード"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >商品コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceNormalProduct"
                          placeholder="商品コード"
                        />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >生徒番号
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="superGraceNormalStudentId"
                          placeholder="例:生徒番号1,生徒番号2,生徒番号3"
                        />
                      </div>
                    </div>

                    <div class="form-group row ">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >法人名なしを含む
                      </label>

                      <div class="col-md-5 block-radio">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            value="0"
                            v-model="superGraceNormalContainCompanyName"
                          />
                          <label class="form-check-label" for="radio1"
                            >はい</label
                          >
                        </div>
                        
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            value="1"
                            v-model="superGraceNormalContainCompanyName"
                          />
                          <label class="form-check-label" for="radio2"
                            >いいえ</label
                          >
                        </div>
                      </div>
                    </div>

                    <div class="form-group row ">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >出力形式
                      </label>

                      <div class="col-md-5 block-radio">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            value="0"
                            v-model="superGraceNormalDownloadType"
                          />
                          <label class="form-check-label" for="radio1"
                            >TXT</label
                          >
                        </div>
                        
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="radio"
                            name="radios"
                            value="1"
                            v-model="superGraceNormalDownloadType"
                          />
                          <label class="form-check-label" for="radio2"
                            >CSV</label
                          >
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="text-center">
                        <button
                          type="button"
                          class="btn btn-default w-100 mr-2"
                          @click="exportSuperGraceNormal()"
                        >
                          出力
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <form class="basic-form">
                  <input
                    name="_token"
                    type="hidden"
                    v-model="csvExport._token"
                  />
                  <div class="card-header">
                    <h5 class="title-page">CSシステム用CSV出力</h5>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >受注日
                      </label>
                      <div class="col-md-2">
                        <date-picker
                          v-model="paymentDateStart"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                      <div
                        class="col-sm-1 text-center"
                        style="width: auto !important; padding-top: 8px"
                      >
                        ～
                      </div>
                      <div class="col-md-2">
                        <date-picker
                          v-model="paymentDateEnd"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >開講希望月
                      </label>
                      <div class="col-md-2">
                        <date-picker
                          v-model="projectCourseStudentDateStart"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                      <div
                        class="col-sm-1 text-center"
                        style="width: auto !important; padding-top: 8px"
                      >
                        ～
                      </div>
                      <div class="col-md-2">
                        <date-picker
                          v-model="projectCourseStudentDateEnd"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >生徒名
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="studentName"
                          placeholder="生徒名"
                        />
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="text-center">
                        <button
                          type="button"
                          class="btn btn-default w-100 mr-2"
                          @click="exportStudentBoughtCourse()"
                        >
                          出力
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <form class="basic-form">
                  <input
                    name="_token"
                    type="hidden"
                    v-model="csvExport._token"
                  />
                  <div class="card-header">
                    <h5 class="title-page">全体集計CSV出力</h5>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >受講日
                      </label>
                      <div class="col-md-2">
                        <date-picker
                          v-model="smLessonDateStart"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                      <div
                        class="col-sm-1 text-center"
                        style="width: auto !important; padding-top: 8px"
                      >
                        ～
                      </div>
                      <div class="col-md-2">
                        <date-picker
                          v-model="smLessonDateEnd"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >有効期限日
                      </label>
                      <div class="col-md-2">
                        <date-picker
                          v-model="smExpireDateStart"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                      <div
                        class="col-sm-1 text-center"
                        style="width: auto !important; padding-top: 8px"
                      >
                        ～
                      </div>
                      <div class="col-md-2">
                        <date-picker
                          v-model="smExpireDateEnd"
                          :format="'YYYY/MM/DD'"
                          type="date"
                        ></date-picker>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >法人コード
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="smCompanyCode"
                          placeholder="法人コード"
                        />
                      </div>
                    </div>

                    <div class="form-group row">
                      <label
                        class="col-md-3 col-form-label text-md-right"
                        for="text-input"
                        >企業ID
                      </label>
                      <div class="col-md-5">
                        <input
                          class="form-control"
                          v-model="smProjectCode"
                          placeholder="企業ID"
                        />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="text-center">
                        <button
                          type="button"
                          class="btn btn-default w-100 mr-2"
                          @click="exportLessonSummaryProcess()"
                        >
                          出力
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <loader :flag-show="flagShowLoader"></loader>
  </div>
</template>

<style scoped>
.mx-datepicker {
  width: 100% !important;
}

.block-radio {
  display: flex;
}

.block-radio .form-check {
  margin-left: 10px;
}
</style>

<script type="text/javascript">
import axios from "axios";
import Loader from "../common/loader.vue";

export default {
  created: function () {},
  components: {
    Loader,
  },
  data() {
    return {
      flagShowLoader: false,
      csvExport: {
        _token: Laravel.csrfToken,
      },
      paymentDateStart: new Date(),
      paymentDateEnd: new Date(),
      corporationCodePayment: null,
      productCode: null,
      customerCode: null,
      exportLessonHistoryStart: new Date(),
      exportLessonHistoryEnd: new Date(),
      lessonResultNumber: null,
      lessonResultCampaign: null,
      lessonResultProduct: null,
      lessonResultCustomer: null,
      superGraceDate: new Date(),
      superGraceCampaign : null,
      superGraceCompany : null,
      superGraceProduct : null,
      superGraceProjectCode : null,
      superGraceCampaignCode : null,
      superGraceContainCompanyName : 0,
      superGraceCustomer : null,
      superGraceDownloadType : 0,
      superGraceNormalDate: new Date(),
      superGraceNormalCampaign : null,
      superGraceNormalCompany : null,
      superGraceNormalProduct : null,
      superGraceNormalStudentId : null,
      superGraceNormalCampaignCode : null,
      superGraceNormalContainCompanyName : 0,
      superGraceNormalCustomer : null,
      superGraceNormalDownloadType : 0,
      paymentDateStart : new Date(),
      paymentDateEnd : new Date(),
      projectCourseStudentDateStart : new Date(),
      projectCourseStudentDateEnd : new Date(),
      studentName : null,
      smLessonDateStart : new Date(),
      smLessonDateEnd : new Date(),
      smExpireDateStart : new Date(),
      smExpireDateEnd : new Date(),
      smCompanyCode : null,
      smProjectCode : null
    };
  },
  props: ["urlExportCsv", "urlExportLessonHistory", "urlExportSuperGrace", "urlExportSuperGraceNormal", "urlExportLessonSummaryProcess", "urlExportStudentBoughtCourse"],
  mounted() {},
  methods: {
    exportOrderHistory() {
      this.flagShowLoader = true;
      let that = this;
      axios
        .post(that.urlExportCsv, {
          payment_date_1: this.paymentDateStart,
          payment_date_2: this.paymentDateEnd,
          corporation_code: this.corporationCodePayment,
          product_code: this.productCode,
          customer_code: this.customerCode,
          _token: Laravel.csrfToken,
        })
        .then((res) => {
          this.flagShowLoader = false;
          if (res.data.status == 200) {
            var fileLink = document.createElement("a");
            fileLink.href = res.data.path;
            fileLink.setAttribute("download", res.data.file_name);
            document.body.appendChild(fileLink);

            window.setTimeout(function () {
              fileLink.click();
            }, 1000);
          } else {
            this.showAlert(res.data.message)
          }
        })
        .catch((error) => {
          this.flagShowLoader = true;
        });
    },
    exportLessonHistory() {
      this.flagShowLoader = true;
      let that = this;

      axios
        .post(that.urlExportLessonHistory, {
          lesson_result_date1: this.exportLessonHistoryStart,
          lesson_result_date2: this.exportLessonHistoryEnd,
          lesson_result_number: this.lessonResultNumber,
          lesson_result_campaign: this.lessonResultCampaign,
          lesson_result_product: this.lessonResultProduct,
          lesson_result_customer: this.lessonResultCustomer,
          _token: Laravel.csrfToken,
        })
        .then((res) => {
          this.flagShowLoader = false;
          console.log(res.data)
          if (res.data.status == 200) {
            var fileLink = document.createElement("a");
            fileLink.href = res.data.path;
            fileLink.setAttribute("download", res.data.file_name);
            document.body.appendChild(fileLink);

            window.setTimeout(function () {
              fileLink.click();
            }, 1000);
          } else {
            this.showAlert(res.data.message)
          }
        })
        .catch((error) => {
          this.flagShowLoader = true;
        });
    },
    exportSuperGrace() {
      this.flagShowLoader = true;
      let that = this;
      axios
        .post(that.urlExportSuperGrace, {
          super_grace_date : this.superGraceDate,
          super_grace_campaign : this.superGraceCampaign,
          super_grace_campaign_code : this.superGraceCampaignCode,
          super_grace_product : this.superGraceProduct,
          super_grace_customer : this.superGraceCustomer,
          super_grace_company : this.superGraceCompany,
          super_grace_company_name : this.superGraceContainCompanyName,
          super_grace_download_type : this.superGraceDownloadType,
          project_code : this.superGraceProjectCode,
          _token: Laravel.csrfToken,
        })
        .then((res) => {
          this.flagShowLoader = false;

          if (res.data.status == 200) {
            var fileLink = document.createElement("a");
            fileLink.href = res.data.path;
            fileLink.setAttribute("download", res.data.file_name);
            document.body.appendChild(fileLink);

            window.setTimeout(function () {
              fileLink.click();
            }, 1000);
          } else {
            this.showAlert(res.data.message)
          }
        })
        .catch((error) => {
          this.flagShowLoader = true;
        });
    },
    exportSuperGraceNormal() {
      this.flagShowLoader = true;
      let that = this;

      axios
        .post(that.urlExportSuperGraceNormal, {
          super_grace_date_normal : this.superGraceNormalDate,
          super_grace_campaign_normal : this.superGraceNormalCampaign,
          super_grace_campaign_code_normal : this.superGraceNormalCampaignCode,
          super_grace_product_normal : this.superGraceNormalProduct,
          super_grace_customer_normal : this.superGraceNormalCustomer,
          super_grace_company_normal : this.superGraceNormalCompany,
          super_grace_company_name_normal : this.superGraceNormalContainCompanyName,
          super_grace_download_type_normal : this.superGraceNormalDownloadType,
          student_id : this.superGraceNormalStudentId,
          _token: Laravel.csrfToken,
        })
        .then((res) => {
          this.flagShowLoader = false;
          if (res.data.status == '200') {
            var fileLink = document.createElement("a");
            fileLink.href = res.data.path;
            fileLink.setAttribute("download", res.data.file_name);
            document.body.appendChild(fileLink);

            window.setTimeout(function () {
              fileLink.click();
            }, 1000);
          } else {
            this.showAlert(res.data.message)
          }
        })
        .catch((error) => {
          this.flagShowLoader = true;
        });
    },
    exportStudentBoughtCourse() {
      this.flagShowLoader = true;
      let that = this;

      axios
        .post(that.urlExportStudentBoughtCourse, {
          payment_date_from: this.paymentDateStart,
          payment_date_to: this.paymentDateEnd,
          project_course_student_start_date: this.projectCourseStudentDateStart,
          project_course_student_end_date: this.projectCourseStudentDateEnd,
          student_name: this.studentName,
          _token: Laravel.csrfToken,
        })
        .then((res) => {
          this.flagShowLoader = false;
          if (res.data.status == 200) {
            var fileLink = document.createElement("a");
            fileLink.href = res.data.path;
            fileLink.setAttribute("download", res.data.file_name);
            document.body.appendChild(fileLink);

            window.setTimeout(function () {
              fileLink.click();
            }, 1000);
          } else {
            this.showAlert(res.data.message)
          }
        })
        .catch((error) => {
          this.flagShowLoader = true;
        });
    },
    exportLessonSummaryProcess() {
      this.flagShowLoader = true;
      let that = this;

      axios
        .post(that.urlExportLessonSummaryProcess, {
          sm_lesson_date_from: this.smLessonDateStart,
          sm_lesson_date_to: this.smLessonDateEnd,
          sm_expire_date_from: this.smExpireDateStart,
          sm_expire_date_to: this.smExpireDateEnd,
          sm_company_code: this.smCompanyCode,
          sm_project_code: this.smProjectCode,
          _token: Laravel.csrfToken,
        })
        .then((res) => {
          this.flagShowLoader = false;
          if (res.data.status == 200) {
            var fileLink = document.createElement("a");
            fileLink.href = res.data.path;
            fileLink.setAttribute("download", res.data.file_name);
            document.body.appendChild(fileLink);

            window.setTimeout(function () {
              fileLink.click();
            }, 1000);
          } else {
            this.showAlert(res.data.message)
          }
        })
        .catch((error) => {
          this.flagShowLoader = true;
        });
    },
    showAlert(message) {
      let that = this;
      this.$swal({
        title: message,
        icon: "warning",
        showCancelButton: false,
      }).then(result => {});
    }
  },
};
</script>
