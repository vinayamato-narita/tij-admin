<template>
    <div class="col-md-2">
        <select
            class="form-control page-size-select cursor-point"
            name="limit"
            @change="onChange($event)"
        >
            <option
                v-for="value in pageSize"
                :value="value"
                v-bind:selected="value == pageLimit"
            >
                {{ value + "件" }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    props: ["pageSize", "pageLimit"],
    methods: {
        onChange(event) {
            let pathname = window.location.pathname;
            let search = window.location.search;
            if (search.indexOf("limit=") >= 0) {
                search = search.replace(
                    /limit=([0-9]*)/gi,
                    "limit=" + event.target.value
                );
            } else {
                if (search == "") {
                    search = search + "?limit=" + event.target.value;
                } else {
                    search = search + "&limit=" + event.target.value;
                }
            }
            window.location = window.location.origin + pathname + search;
        }
    }
};
</script>
