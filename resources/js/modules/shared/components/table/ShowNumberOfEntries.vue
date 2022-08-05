<template>
    <div class="form-group">
        <div class="subtitle-2 mb-2 font-weight-medium">Per Page :</div>
        <select @change="changeNoOfListToBeShownOnTable($event)" class="form-control" id="sel1">
            <option v-for="(perPage,index) in rows_per_page"
                    :key="index">{{ perPage['id'] }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    name: "ShowNumberOfEntries",
    data() {
        return {
            rows_per_page: [
                {
                    id: 10,
                },
                {
                    id: 20,
                },
                {
                    id: 30,
                },
                {
                    id: 40,
                },
                {
                    id: 50,
                }
            ]
        }
    },

    methods: {

        changeNoOfListToBeShownOnTable(event) {

            console.log(event.target.value, 'per page records');

            this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", 1);

            this.$store.commit("tableConfig/SET_RECORDS_PER_PAGE", event.target.value);

            this.$emit("change_records_per_page");

        }

    }
}
</script>

<style scoped>

</style>
