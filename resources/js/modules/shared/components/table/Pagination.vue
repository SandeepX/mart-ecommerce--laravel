<template>
    <div class="container">
        <ul class="pagination hover_icon">
            <li class="page-item" :class="disablePreviousButton?'disabled':''"><a class="page-link"
                                                                                  @click="changeCurrentPageNoForPreviousButton(getCurrentPageNo-1)">Previous</a>
            </li>
            <li v-for="(page,index) in pages" :key="index" class="page-item"
                :class="page['active']?'active':''">
                <a class="page-link" @click="changeCurrentPageNo(page['page_no'])">
                    <span>{{ page['page_no'] }}</span>
                </a>
            </li>
            <li class="page-item" :class="disableNextButton?'disabled':''"><a class="page-link"
                                                                              @click="changeCurrentPageNoForNextButton(getCurrentPageNo+1)">Next</a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "Pagination",

    props: ['pages'],

    data() {
        return {
            disablePreviousButton: false,
            disableNextButton: false,
            selectedPageNo: null,
        }
    },

    watch: {

        //Watch for change in the current page no.
        getCurrentPageNo: {

            handler() {

                this.disableNextOrPreviousButton();

            },

            immediate: true

        }

    },

    created() {

        this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", 1);

        this.$emit('change_page_no', 1);

    },

    computed: {

        //Get All Meta Information for pagination.
        getMetaInformation() {

            return this.$store.getters['tableConfig/GET_META_INFORMATION']

        },

        //Get Current Page N0.
        getCurrentPageNo() {

            return this.$store.getters['tableConfig/GET_META_INFORMATION']['current_page'];

        },

        //Get Last Page No.
        getLastPageNo() {

            return this.$store.getters['tableConfig/GET_META_INFORMATION']['last_page'];

        }

    },

    methods: {

        //Check if previous or next button should be disabled.
        disableNextOrPreviousButton() {

            if (this.getCurrentPageNo === 1) {

                this.disablePreviousButton = true;

            } else {

                this.disablePreviousButton = false;

            }

            if (this.getLastPageNo === this.selectedPageNo) {

                this.disableNextButton = true;

            } else {

                this.disableNextButton = false;

            }

            if (this.getLastPageNo === 1) {

                this.disableNextButton = true;

                this.disablePreviousButton = true;

            }

        },

        //Change the table data along with the page no. in the pagination for next button.
        changeCurrentPageNoForPreviousButton(page) {

            if (!this.disablePreviousButton) {

                this.selectedPageNo = page;

                console.log(page, 'page no');

                this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", page);

                this.$emit('change_page_no', page);

            }

        },

        //Change the table data along with the page no. in the pagination for previous button.
        changeCurrentPageNoForNextButton(page) {

            if (!this.disableNextButton) {

                this.selectedPageNo = page;

                console.log(page, 'page no');

                this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", page);

                this.$emit('change_page_no', page);

            }

        },

        //Change the table data along with the page no. in the pagination for paginations button.
        changeCurrentPageNo(page) {

            this.selectedPageNo = page;

            console.log(this.getCurrentPageNo, this.selectedPageNo, 'pages haru');

            if (this.selectedPageNo !== this.getCurrentPageNo) {

                console.log(page, 'page no');

                this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", page);

                this.$emit('change_page_no', page);

            }

        }

    }

}
</script>

<style scoped>

</style>
