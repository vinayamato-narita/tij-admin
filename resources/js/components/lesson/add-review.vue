<template>
    <modal name="add-review-modal"   :pivotY="0.1" :reset="true" :width="1000" :height="auto"  :scrollable="true" :adaptive="true" :clickToClose="false" @before-open="getData" >
        <div class="card">
            <div class="card-header"> 復習一覧

                <div class="float-right">
                    <button type="button" class="close"  v-on:click="hide" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-10">
                        <form method="GET" @submit.prevent="search" class="input-search">
                            <input type="hidden" name="limit" :value="pageLimit" />
                            <div class="input-group">
                                <input
                                        name="search_input"
                                        placeholder="検索"
                                        class="form-control text-md-left"
                                        type="text"
                                        :value="inputSearch"
                                />
                                <span class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </span>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="row mb-2">
                    <div class="col-md-2">
                        <select
                                class="form-control page-size-select cursor-point"
                                name="limit"
                                @change="onChangePageSize($event)"
                        >
                            <option
                                    v-for="value in pageSizeLimit"
                                    :value="value"
                                    v-bind:selected="value == pageLimit"
                            >
                                {{ value + "件" }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-10">
                        <nav class="transeeker-pagination" v-if="dataList.length !=  0">
                            <span class="total-item-pagination">{{from}} - {{to}} / 全 {{total}}件</span>
                            <ul class="pagination"><li aria-current="page" class="page-item active">
                                <li v-if="currentPage != 1" class="page-item">
                                    <a class="page-link"  v-on:click="toPage(1)" rel="prev" aria-label="many-previous')"><<</a>
                                </li>
                                <li v-if="currentPage != 1"  class="page-item">
                                    <a class="page-link" v-on:click="toPage(currentPage - 1)"  rel="prev" aria-label="previous')"><</a>
                                </li>
                                <li v-for="index in pageList " class="page-item" :class="index == currentPage ? 'active' : ''">
                                    <a class="page-link" v-on:click="toPage(index)" rel="prev" aria-label="previous')">{{index}}</a>
                                </li>
                                <li v-if="currentPage != lastPage"   class="page-item">
                                    <a class="page-link" v-on:click="toPage(lastPage)" rel="next" aria-label="many-next')">>></a>
                                </li>
                                <li v-if="currentPage != lastPage" class="page-item">
                                    <a class="page-link" v-on:click="toPage(currentPage + 1)" rel="next" aria-label="next')">></a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="tanemaki-table pd-t-7" style="width: 100%;">
                        <div v-if="dataList.length ==  0" class="data-empty-message text-center alert alert-danger">
                            該当データがありません

                        </div>
                        <table v-if="dataList.length !=  0" class="table table-responsive-sm table-striped border">
                            <thead >
                            <tr>
                                <th class="text-center bg-gray-100 " style="width: 50px">
                                    <input   type="radio" class=" checkbox" style="width: auto; height: auto; display: none;">
                                </th>
                                <th class="text-center text-md-left bg-gray-100">予習名</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="review in dataList">
                                <td class="text-center">
                                    <input :value="review.review_id"  type="radio" class=" checkbox" style="width: auto; height: auto; display: inline-block;" v-model="reviewId" >
                                </td>
                                <td class="text-md-left">{{  review.review_name }}</td>

                            </tr>


                            </tbody>

                        </table>
                    </div>


                </div>
                <div class="row mb-2">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-10">
                        <nav class="transeeker-pagination" v-if="dataList.length !=  0">
                            <span class="total-item-pagination">{{from}} - {{to}} / 全 {{total}}件</span>
                            <ul class="pagination" ><li aria-current="page" class="page-item active">
                                <li v-if="currentPage != 1" class="page-item">
                                    <a class="page-link"  v-on:click="toPage(1)" rel="prev" aria-label="many-previous')"><<</a>
                                </li>
                                <li v-if="currentPage != 1"  class="page-item">
                                    <a class="page-link" v-on:click="toPage(currentPage - 1)"  rel="prev" aria-label="previous')"><</a>
                                </li>
                                <li v-for="index in pageList " class="page-item" :class="index == currentPage ? 'active' : ''">
                                    <a class="page-link" v-on:click="toPage(index)" rel="prev" aria-label="previous')">{{index}}</a>
                                </li>
                                <li v-if="currentPage != lastPage"   class="page-item">
                                    <a class="page-link" v-on:click="toPage(lastPage)" rel="next" aria-label="many-next')">>></a>
                                </li>
                                <li v-if="currentPage != lastPage" class="page-item">
                                    <a class="page-link" v-on:click="toPage(currentPage + 1)" rel="next" aria-label="next')">></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="form-actions text-center">
                    <div class="line"></div>
                    <div class="form-group">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-100 mr-2" v-on:click="submit">登録</button>
                            <a v-on:click="hide" class="btn btn-default w-100">閉じる</a></div>
                    </div>
                </div>


            </div>



        </div>
    </modal>

</template>

<script>
    import Loader from "./../../components/common/loader";

    export default {
        name: "add-preparation-modal",
        components: {
            Loader
        },
        data() {
            return {
                pageLimit: 100,
                dataList : [{}],
                inputSearch :'',
                from: 0,
                to : 0,
                total :0,
                currentPage : 0,
                lastPage : 0,
                auto : 'auto',
                reviewId : null,
                pageList: [],


            };
        },
        props: [ 'url', 'pageSizeLimit', 'id', 'registerUrl', 'detailUrl', 'type'],
        mounted() {
        },
        created: function () {

        },
        methods: {
            toPage(page) {
                this.currentPage = page;
                this.getData()

            },
            hide () {
                this.$modal.hide('add-review-modal');
            },
            getData(){
                let that = this;
                axios.get(this.url, {
                    params: {
                        limit : that.pageLimit,
                        inputSearch : that.inputSearch,
                        page : that.currentPage,
                        id : that.id,
                    }
                })
                    .then(function (response) {
                        that.from = response.data.dataList.from;
                        that.to = response.data.dataList.to;
                        that.total = response.data.dataList.total;
                        that.currentPage = response.data.dataList.current_page;
                        that.lastPage = response.data.dataList.last_page;
                        that.dataList = response.data.dataList.data;
                        that.pageList = [];
                        if (that.currentPage <= 3) {
                            for (let i = 1; (i <= that.currentPage + 3) && (i <= that.lastPage) ; i++)
                                that.pageList.push(i)
                        } else {
                            for (let i = that.currentPage - 3; (i <= that.currentPage + 3) && (i <= that.lastPage) ; i++)
                                that.pageList.push(i)

                        }
                    })
                    .catch(function (error) {
                    });            },
            onChangePageSize(event) {
                this.pageLimit = event.target.value;
                this.getData();
            },
            search(e) {
                this.inputSearch = $('[name="search_input"]').val();
                this.pageLimit = 100;
                this.getData();

            },
            submit() {
                let that = this;
                axios
                    .post(that.registerUrl, { reviewId:  that.reviewId, lessonId : that.id})
                    .then(response => {
                        window.location = that.detailUrl;
                    })
                    .catch(e => {
                    });
            }
        },

    }
</script>

<style scoped>

</style>
