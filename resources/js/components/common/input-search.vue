<template>
    <form method="GET" :action="url" class="input-search" autocomplete="off">
        <input type="hidden" name="limit" :value="pageLimit" />
        <div class="input-group">
            <input
                name="search_input"
                placeholder="検索"
                class="form-control"
                type="text"
                :value="dataQuery.search_input"
            />
            <span class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </span>
        </div>
    </form>
</template>

<script>
export default {
    props: ["url", "pageLimit", "dataQuery"],
    mounted() {}
};
</script>
