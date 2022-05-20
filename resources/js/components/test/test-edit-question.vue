<template>

    <div class="c-body">
        <main class="c-main pt-0">
            <div class="container-fluid info-screen">
                <div class="page-heading">
                    <div class="page-heading-left">
                        <h5>
                            テスト問題編集


                        </h5>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <form class="form-horizontal " style="width: 100%" method="POST" ref="registerForm"
                                      @submit.prevent="register" autocomplete="off">
                                    <div class="card-body pl-5 mr-5 col-md-4">　


                                        <div class="form-group row ">
                                            <label class="col-md-4 col-form-label text-md-left"><b>テストID:</b>
                                            </label>
                                            <div class="col-md-6 text-md-left p-2">
                                                {{this.test.test_id}}

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-4 col-form-label text-md-left"><b>テスト名:</b>
                                            </label>
                                            <div class="col-md-6 text-md-left p-2">

                                                {{this.test.test_name}}

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-4 col-form-label text-md-left"><b>テスト種別:</b>
                                            </label>
                                            <div class="col-md-6 text-md-left p-2">

                                                {{testTypes[this.test.test_type]}}


                                            </div>
                                        </div>


                                    </div>

                                    <div class="div-deco">
                                        <h5>
                                            大問 {{index}}
                                        </h5>


                                    </div>
                                    <div class="card-body pl-5 pr-5">　


                                        <div class="form-group row ">
                                            <label class="col-md-2 col-form-label text-md-left"><b>ナビゲーション :</b>
                                                <span class="glyphicon glyphicon-star"></span>
                                            </label>
                                            <div class="col-md-10 text-md-left p-2">
                                                <input
                                                        class="form-control"
                                                        name="navigation"
                                                        v-model="navigation"
                                                        v-validate="
                                                        'required|max:255|unique_custom'
                                                    "
                                                />
                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('navigation')"
                                                >
                                                    {{ errors.first("navigation") }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-2 col-form-label text-md-left"><b>問題文 :</b>
                                            </label>
                                            <div class="col-md-10 text-md-left p-2">
                                            <textarea
                                                    class="form-control"
                                                    name="questionContent"
                                                    v-model="questionContent"
                                                    rows="5"
                                            >


                                            </textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label class="col-md-2 col-form-label text-md-left"><b>添付ファイル :</b>
                                            </label>
                                            <div class="col-md-10 text-md-left p-2">
                                                <button type="button" class="btn btn-primary w-100 mr-2"
                                                        v-on:click="show('add-files-modal')">ファイル選択
                                                </button>
                                                <span class="text-nowrap">
                                                    {{fileNameAttached}}
                                                    <button v-if="fileNameAttached" type="button" v-on:click="clearTestQuestionFileAttached" class="btn btn-danger">削除</button>

                                                </span>
                                                <button type="button" v-on:click="newFile"
                                                        class="btn btn-primary  mr-2">新規ファイル追加
                                                </button>
                                                <input type="file" name="newFile" id="newFile" ref="newFile"
                                                       v-on:change="changeFile" class="hidden" v-validate="'max_sz_50'">
                                                <span class="text-nowrap">
                                                    {{fileName}}<button v-if="fileName" type="button" v-on:click="clearTestQuestionFileSelected" class="btn btn-danger">削除</button>

                                                </span>


                                                <div
                                                        class="input-group is-danger"
                                                        role="alert"
                                                        v-if="errors.has('newFile')"
                                                >
                                                    {{ errors.first("newFile") }}
                                                </div>

                                            </div>
                                        </div>


                                    </div>
                                    <div v-for="(item, index) in subQuestion" >
                                        <div class="div-deco answer-bg" >
                                            <h5>
                                                設問{{ getIndex(index)}}
                                                <button v-if="index !== 0 && (!isHasTestResult || !item.isSavedDB)"  type="button" v-on:click="deleteSubQuestion(index)" class="float-right btn">                                                <font-awesome-icon icon="minus-circle"></font-awesome-icon>
                                                </button>
                                            </h5>


                                        </div>

                                        <div class="card-body pl-5 pr-5" >　


                                            <div class="form-group row ">
                                                <label class="col-md-2 col-form-label text-md-left"><b>問題文 :</b>
                                                    <span class="glyphicon glyphicon-star"></span>
                                                </label>
                                                <div class="col-md-10 text-md-left p-2">
                                                    <textarea
                                                            class="form-control"
                                                            :name="'subQuestion[' + index + '][question]'"
                                                            v-model="item.question"
                                                            v-validate="
                                                        'required|max:255'
                                                    "
                                                      ></textarea>
                                                    <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('subQuestion['+ index +'][question]')"
                                                    >
                                                        {{ errors.first("subQuestion["+ index + "][question]") }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row ">
                                                <label class="col-md-2 col-form-label text-md-left"><b>選択肢1(正解) :</b>
                                                    <span class="glyphicon glyphicon-star"></span>
                                                </label>
                                                <div class="col-md-10 text-md-left p-2">
                                                    <input
                                                            class="form-control"
                                                            :name="'subQuestion[' + index + '][answer1]'"
                                                            v-model="item.answer1"
                                                            v-validate="
                                                        'required'
                                                    "
                                                    />
                                                    <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('subQuestion['+ index +'][answer1]')"
                                                    >
                                                        {{ errors.first("subQuestion["+ index +"][answer1]") }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row ">
                                                <label class="col-md-2 col-form-label text-md-left"><b>選択肢2 :</b>
                                                    <span class="glyphicon glyphicon-star"></span>
                                                </label>
                                                <div class="col-md-10 text-md-left p-2">
                                                    <input
                                                            class="form-control"
                                                            :name="'subQuestion[' + index + '][answer2]'"
                                                            v-model="item.answer2"
                                                            v-validate="
                                                        'required'
                                                    "
                                                    />
                                                    <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('subQuestion['+ index +'][answer2]')"
                                                    >
                                                        {{ errors.first("subQuestion["+ index +"][answer2]") }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row ">
                                                <label class="col-md-2 col-form-label text-md-left"><b>選択肢3 :</b>
                                                </label>
                                                <div class="col-md-10 text-md-left p-2">
                                                    <input
                                                            class="form-control"
                                                            :name="'subQuestion[' + index + '][answer3]'"
                                                            v-model="item.answer3"
                                                    />
                                                    <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('subQuestion['+ index +'][answer3]')"
                                                    >
                                                        {{ errors.first("subQuestion["+ index +"][answer3]") }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row ">
                                                <label class="col-md-2 col-form-label text-md-left"><b>選択肢4 :</b>
                                                </label>
                                                <div class="col-md-10 text-md-left p-2">
                                                    <input
                                                            class="form-control"
                                                            :name="'subQuestion[' + index + '][answer4]'"
                                                            v-model="item.answer4"
                                                    />
                                                    <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('subQuestion['+ index +'][answer4]')"
                                                    >
                                                        {{ errors.first("subQuestion["+ index +"][answer4]") }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row " v-if="test.test_type === 0">
                                                <label class="col-md-2 col-form-label text-md-left"><b>解説 :</b>

                                                </label>
                                                <div class="col-md-10 text-md-left p-2">
                                                <textarea
                                                        class="form-control"
                                                        :name="'subQuestion[' + index + '][explanation]'"
                                                        v-model="item.explanation"
                                                >

                                                </textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row " v-if="test.test_type === 0">
                                                <label class="col-md-2 col-form-label text-md-left"><b>添付ファイル :</b>
                                                </label>
                                                <div class="col-md-10 text-md-left p-2">
                                                    <button type="button" class="btn btn-primary w-100 mr-2"
                                                            v-on:click="showModalSubQuestion('add-files-modal', index)">
                                                        ファイル選択
                                                    </button>
                                                    <span class="text-nowrap">
                                                    {{ subQuestion[index].fileNameAttached}}
                                                    <button v-if="subQuestion[index].fileNameAttached" type="button" v-on:click="clearTestSubQuestionFileAttached(index)" class="btn btn-danger">
                                                        削除
                                                    </button>
                                                </span>
                                                    <button type="button"
                                                            v-on:click="newFileQuestion('subQuestion[' + index +']newFile')"
                                                            class="btn btn-primary  mr-2">新規ファイル追加
                                                    </button>
                                                    <input type="file" :name="'subQuestion[' + index +']newFile'"
                                                           id="newFile" :ref="'subQuestion[' + index + ']newFile'"
                                                           v-on:change="changeFileSubQuestion($event, index)"
                                                           class="hidden">
                                                    <span class="text-nowrap">
                                                    {{ subQuestion[index].fileName}}
                                                    <button v-if="subQuestion[index].fileName" type="button" v-on:click="clearTestSubQuestionFileSelected(index)" class="btn btn-danger">削除</button>


                                                </span>

                                                </div>
                                            </div>
                                            <div class="form-group row " v-if="test.test_type === 2">
                                                <label class="col-md-2 col-form-label text-md-left"><b>参考URLリンク :</b>
                                                </label>
                                                <div class="col-md-10 text-md-left p-2">
                                                    <input
                                                            class="form-control"
                                                            :name="'subQuestion[' + index + '][reference_url]'"
                                                            v-model="item.referenceUrl"
                                                            v-validate="'max:30'"
                                                    />
                                                    <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('subQuestion['+ index +'][reference_url]')"
                                                    >
                                                        {{ errors.first("subQuestion["+ index +"][reference_url]") }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row ">
                                                <label class="col-md-2 col-form-label text-md-left"><b>点数 :</b>
                                                </label>
                                                <div class="col-md-10 text-md-left p-2">
                                                    <input
                                                            type="number"
                                                            class="form-control"
                                                            :name="'subQuestion[' + index + '][score]'"
                                                            style="width: 100px"
                                                            v-model="item.score"
                                                            v-validate="
                                                        'required|decimal|min_value:0|max_value:1000000000'
                                                    "
                                                    />
                                                    <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('subQuestion['+ index +'][score]')"
                                                    >
                                                        {{ errors.first("subQuestion["+ index +"][score]") }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row ">
                                                <label class="col-md-2 col-form-label text-md-left"><b>タグ :</b>
                                                </label>
                                                <div class="col-md-9 text-md-left p-2">
                                                    <multiselect v-model="item.value" label="name" track-by="id"
                                                                 :options="optionsTag" :multiple="true"
                                                                 :select-label="' '"
                                                                 :selected-label="' '"
                                                                 :deselect-label="' '"
                                                                 placeholder=""
                                                    >

                                                        <span slot="noResult"></span>
                                                        <span slot="noOptions"></span>
                                                    </multiselect>

                                                </div>
                                                <div class="col-md-1 text-md-left p-2 d-flex align-items-center">
                                                    <button type="button" class="btn btn-primary w-100 mr-2 text-nowrap"
                                                            v-on:click="showAddtag(index)"
                                                    ><font-awesome-icon  icon="plus-circle" />

                                                        追加</button>

                                                </div>
                                            </div>

                                            <div class="form-group row" v-if="test.show_category">
                                                <label
                                                        class="col-md-2 col-form-label text-md-left"
                                                ><b>カテゴリ</b><span v-if="test.test_type === 1" class="glyphicon glyphicon-star"
                                                ></span
                                                ></label>

                                                <div class="col-md-10 text-md-left p-2">
                                                    <select class="form-control"
                                                            v-validate="{ required: test.test_type == 1}"
                                                            :name="'subQuestion[' + index + '][testCategory]'"
                                                            v-model="item.testCategory"

                                                    >
                                                        <option :value="value.test_category_id"
                                                                v-for="(value, key) in testCategories">
                                                            {{ value.parent_category_name }} : {{value.category_name}}
                                                        </option
                                                        >
                                                    </select>
                                                    <div
                                                            class="input-group is-danger"
                                                            role="alert"
                                                            v-if="errors.has('subQuestion['+ index +'][testCategory]')"
                                                    >
                                                        {{ errors.first("subQuestion["+ index +"][testCategory]") }}
                                                    </div>

                                                </div>

                                            </div>





                                        </div>

                                    </div>

                                    <div class="pr-5 pl-5">
                                        <button type="button" v-on:click="addSubQuestion" class="btn btn-primary w-100 mr-2">設問追加</button>

                                    </div>

                                    <div class="line"></div>
                                    <div class="form-group">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary w-100 mr-2">登録</button>
                                            <a :href="urlTestDetail" class="btn btn-default w-100">閉じる</a>
                                        </div>
                                    </div>
                                </form>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <add-files :pageSizeLimit="pageSizeLimit" :url="getFilesUrl">

            </add-files>
            <add-tag-modal :createTagUrl="createTagUrl">

            </add-tag-modal>
            <loader :flag-show="flagShowLoader"></loader>

        </main>
    </div>


</template>

<script>
    import axios from 'axios';
    import Loader from "./../../components/common/loader";
    import AddFiles from "./add-files"
    import Multiselect from 'vue-multiselect'
    import AddTagModal from './add-tag-modal'


    export default {
        created: function () {
            let messError = {
                custom: this.defaultCustomMessage
            };
            this.$validator.localize("en", messError);
            let that = this;
            this.tags.forEach(function (e) {
                that.optionsTag.push({id: e.tag_id, name: e.tag_name})
            });
            this.testQuestion.test_sub_questions.forEach(function (e) {
                var valueTags = [];
                e.tags.forEach(function (tag) {
                    valueTags.push({name : tag.tag_name, id: tag.tag_id});
                });
                that.subQuestion.push({
                    testSubQuestionId: e.test_sub_question_id,
                    question: e.sub_question_content,
                    answer1: e.answer1,
                    answer2: e.answer2,
                    answer3: e.answer3,
                    answer4: e.answer4,
                    explanation: e.explanation,
                    fileId: e.explanation_file_id,
                    fileSelected: null,
                    fileName: '',
                    fileNameAttached: e.file == null ? '' : e.file.file_name_original,
                    score: e.score,
                    referenceUrl: e.reference_url,
                    value: valueTags,
                    testCategory :e.test_category === null ? null : e.test_category.test_category_id,
                    isSavedDB: true


                });

            })
            this.$validator.extend("unique_custom", {
                validate(value, args) {
                    return axios
                        .post(that.checkNavigationUrl, {
                            _token: Laravel.csrfToken,
                            navigation: value,
                            test_question_id : that.testQuestion.test_question_id,
                            type: args[0],
                        })
                        .then(function (response) {
                            return {
                                valid: response.data.valid,
                            };
                        })
                        .catch((error) => {});
                },
            });

            this.$validator.extend("max_sz_50", {
                validate(value, args) {
                    console.log(Math.round(((value[0].size / 1024))));
                    if (Math.round(((value[0].size / 1024))) > (500 * 1000))
                        return {valid : false};
                    return { valid : true};
                },
            });




        },
        components: {
            Loader,
            AddFiles,
            Multiselect,
            AddTagModal
        },
        data() {
            return {
                imageExtensions : ['jpg' , 'jpeg' , 'jfif' , 'pjpeg' , 'pjp', "png", 'svg', 'webp'],
                videoExtensions : ['WEBM', 'MPG', 'MP2', 'MPEG', 'MPE', 'MPV', 'OGG', 'MP4', 'M4P', 'M4V', 'AVI', 'WMV', 'MOV', 'QT', 'FLV', 'SWF', 'AVCHD'],
                mp3Extensions: ['MP3'],
                pdfExtensions: ['pdf'],
                flagShowLoader: false,
                csrfToken: Laravel.csrfToken,
                fileSelected: null,
                fileName: '',
                fileNameAttached: this.testQuestion.file === null ? '' : this.testQuestion.file.file_name_original,
                navigation: this.testQuestion.navigation,
                questionContent: this.testQuestion.question_content,
                fileId :  this.testQuestion.file === null ? '' : this.testQuestion.file.file_id,
                optionsTag : [],
                subQuestion: [],
                defaultCustomMessage : {
                    navigation: {
                        max: "ナビゲーションは255文字以内で入力してください。",
                        required : "ナビゲーションを入力してください。",
                        unique_custom: "このナビゲーションは既に登録されています。"
                    },
                    newFile : {
                        max_sz_50 : "ファイルサイズを500MBを超えた為、アップロードできません。"
                    },
                    'subQuestion[0][question]': {
                        required: "問題文を入力してください。",
                        max: "問題文は255文字以内で入力してください。",

                    },
                    'subQuestion[0][answer1]': {
                        required: "選択肢1を入力してください。"
                    },
                    'subQuestion[0][answer2]': {
                        required: "選択肢2を入力してください。。"
                    },
                    'subQuestion[0][score]': {
                        required: "点数を入力してください。",
                        decimal: "点数は半角数字を入力してください。",
                        min_value: "点数は1～1000000000 を入力してください。",
                        max_value: "点数は1～1000000000 を入力してください。",
                    },
                    'subQuestion[0][testCategory]': {
                        required: "カテゴリを選択してください。",
                    },
                    'subQuestion[0][reference_url]': {
                        max: "参考URLは255文字以内で入力してください。",
                    },


                },

            };
        },
        props: ['test', 'testTypes', 'pageSizeLimit', 'getFilesUrl', 'fileType', 'urlTestDetail',
            'updateQuestionUrl', 'tags', 'createTagUrl', 'testQuestion', 'testCategories', 'isHasTestResult', "checkNavigationUrl", "index"],
        mounted() {
        },
        watch : {
            navigation() {
                this.defaultCustomMessage.navigation.unique_custom ="ナビゲーション「" + this.navigation + "」が既に存在する為、登録できません";
                let messError = {
                    custom: this.defaultCustomMessage
                };
                this.$validator.localize("en", messError);

            }
        },
        methods: {
            clearTestQuestionFileSelected(){
                this.fileSelected = null;
                this.fileName = null;
            },
            clearTestQuestionFileAttached(){
                this.fileId = null;
                this.fileNameAttached = null;
            },
            clearTestSubQuestionFileSelected(index){
                this.subQuestion[index].fileSelected = null;
                this.subQuestion[index].fileName = null;
            },
            clearTestSubQuestionFileAttached(index){
                this.subQuestion[index].fileId = null;
                this.subQuestion[index].fileNameAttached = null;
            },
            deleteSubQuestion (index) {
                this.subQuestion = this.subQuestion.filter((_, indexArr) => indexArr !== index);
                let messError = {
                    custom: this.defaultCustomMessage,
                };

                messError.custom["subQuestion[" + index + "][question]"] = {
                    required: "問題文を入力してください。",
                    max: "問題文は255文字以内で入力してください。",
                };
                messError.custom["subQuestion[" + index + "][answer1]"] = {
                    required: "選択肢1を入力してください。"
                };
                messError.custom["subQuestion[" + index + "][answer2]"] = {
                    required: "選択肢2を入力してください。"
                };
                messError.custom["subQuestion[" + index + "][score]"] = {
                    required: "点数を入力してください。",
                    decimal: "点数は半角数字を入力してください。",
                    min_value: "点数は1～1000000000 を入力してください。",
                    max_value: "点数は1～1000000000 を入力してください。",
                };
                messError.custom["subQuestion[" + index + "][testCategory]"] = {
                    required: "カテゴリを選択してください。",
                };
                messError.custom["subQuestion[" + index + "][reference_url]"] = {
                    max: "参考URLは255文字以内で入力してください。",
                };
                this.$validator.localize("en", messError);

            },

            getIndex(index){ return ++index; },
            addSubQuestion () {
                var index = this.subQuestion.length;
                this.subQuestion.push({
                    question: '',
                    answer1: '',
                    answer2: '',
                    answer3: '',
                    answer4: '',
                    explanation: '',
                    fileId: null,
                    fileSelected: null,
                    fileName: '',
                    fileNameAttached: '',
                    score: 0,
                    referenceUrl: this.test.test_type != 0 ? '' : null ,
                    value: [],
                    testCategory: null,
                    isSavedDB: false
                });
                let messError = {
                    custom: this.defaultCustomMessage,
                };

                messError.custom["subQuestion[" + index + "][question]"] = {
                    required: "問題文を入力してください",
                    max: "問題文は255文字以内で入力してください。",
                };
                messError.custom["subQuestion[" + index + "][answer1]"] = {
                    required: "選択肢1を入力してください。"
                };
                messError.custom["subQuestion[" + index + "][answer2]"] = {
                    required: "選択肢2を入力してください。"
                };
                messError.custom["subQuestion[" + index + "][score]"] = {
                    required: "点数を入力してください。",
                    decimal: "点数は半角数字を入力してください。",
                    min_value: "点数は1～1000000000 を入力してください。",
                    max_value: "点数は1～1000000000 を入力してください。",
                };
                messError.custom["subQuestion[" + index + "][testCategory]"] = {
                    required: "カテゴリを選択してください。",
                };
                this.$validator.localize("en", messError);

            },
            showAddtag(index) {
                this.$modal.show('add-tag-modal', {
                    handlers: {
                        sendTagCreated: (...args) => {
                            this.subQuestion[index].value.push(args[0]);
                            this.optionsTag.push(args[0]);
                        }
                    }
                });
            },
            show(modalName) {
                this.$modal.show(modalName, {
                    fileType: this.fileType,
                    fileId: this.fileId,
                    testQuestionId : this.testQuestion.test_question_id,
                    handlers: {
                        sendFileId: (...args) => {
                            this.fileId = args[0].fileId;
                            this.fileNameAttached = args[0].selectedFileName;
                            this.fileSelected = null;
                            this.fileName = null;
                        }
                    }
                });
            },
            showModalSubQuestion(modalName, index) {
                this.$modal.show(modalName, {
                    fileType: this.fileType,
                    fileId: this.subQuestion[index].fileId,
                    testSubQuestionId : this.subQuestion[index].testSubQuestionId,
                    handlers: {
                        sendFileId: (...args) => {
                            this.subQuestion[index].fileId = args[0].fileId;
                            this.subQuestion[index].fileNameAttached = args[0].selectedFileName;
                            this.subQuestion[index].fileSelected = null;
                            this.subQuestion[index].fileName = null;
                        }
                    }
                });
            },
            changeFile(e) {
                if (this.isAllowFileType(e.target.files[0].name)) {
                    this.fileId = null;
                    this.fileNameAttached = '';
                    this.fileSelected = e.target.files[0];
                    this.fileName = e.target.files[0].name;
                }
            },
            changeFileSubQuestion(e, index) {
                this.subQuestion[index].fileId = null;
                this.subQuestion[index].fileNameAttached = '';
                this.subQuestion[index].fileSelected = e.target.files[0];
                this.subQuestion[index].fileName = e.target.files[0].name;
            },
            newFile() {
                this.$refs.newFile.click();
            },
            newFileQuestion(refName) {
                this.$refs[refName][0].click();
            },
            isAllowFileType (fileName) {
                var re = /(?:\.([^.]+))?$/
                var ext = re.exec(fileName)[1];
                var returnType = '';
                if (this.imageExtensions.includes(ext.toLowerCase()) || this.imageExtensions.includes(ext.toUpperCase()) ||
                    this.videoExtensions.includes(ext.toLowerCase()) || this.videoExtensions.includes(ext.toUpperCase()) ||
                    this.mp3Extensions.includes(ext.toLowerCase()) || this.mp3Extensions.includes(ext.toUpperCase()) ||
                    this.pdfExtensions.includes(ext.toLowerCase()) || this.pdfExtensions.includes(ext.toUpperCase()))
                    return true
                return false;

            },
            register() {
                let that = this;
                let formData = new FormData();
                formData.append("navigation", this.navigation);
                formData.append("questionContent", this.questionContent);
                if (this.fileId)
                    formData.append('fileId', this.fileId);
                if (this.fileSelected)
                    formData.append('fileSelected', this.fileSelected);

                this.subQuestion.forEach(function (e, index) {
                    if (e.fileSelected !== null)
                        formData.append('pushedQuestionFile_' + index, e.fileSelected);

                });
                formData.append('subQuestion', JSON.stringify(this.subQuestion));
                this.$validator.validateAll().then((valid) => {
                    if (valid) {
                        that.flagShowLoader = true;
                        axios
                            .post(that.updateQuestionUrl, formData, {
                                header: {
                                    "Content-Type": "multipart/form-data",
                                },
                            })
                            .then((res) => {
                                that.flagShowLoader = false;
                                this.$swal({
                                    title: "テスト問題編集が完了しました。",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                }).then(function (confirm) {
                                    if (confirm.isConfirmed)
                                        window.location.href = that.urlTestDetail;
                                });

                            })
                            .catch((err) => {
                                switch (err.response.status) {
                                    case 422:
                                    case 400:
                                        this.errorsData = err.response.data;
                                        that.flagShowLoader = false;
                                        break;
                                    case 500:
                                        this.$swal({
                                            title: "失敗したデータを追加しました。",
                                            icon: "error",
                                            confirmButtonText: "OK",
                                        }).then(function (confirm) {
                                        });
                                        that.flagShowLoader = false;
                                        break;
                                    default:
                                        break;
                                }
                            });
                    }
                    else {
                        this.$el
                            .querySelector(
                                "input[name=\"" + Object.keys(this.errors.collect())[0] + "\"]"
                            )
                            .focus();
                        $("html, body").animate(
                            {
                                scrollTop:
                                    $(
                                        "input[name=\"" + Object.keys(this.errors.collect())[0] + "\"]"
                                    ).offset().top - 104,
                            },
                            500
                        );
                    }
                });

            },
        },
    }
</script>
