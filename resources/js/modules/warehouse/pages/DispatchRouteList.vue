<template>
    <div v-if="allDispatchRoutes.length>0" class="container-fluid">
        <div class="row mb-5">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th>S.N</th>
                    <th>Dispatch Code</th>
                    <th>Dispatch Name</th>
                    <th>Associated Stores</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(route,index) in allDispatchRoutes" :key="index">
                    <td>{{ index + 1 }}.</td>
                    <td>{{ route['dispatch_route_code'] }}</td>
                    <td>{{ route['dispatch_route_name'] }}</td>
                    <td>
                        <div class="mb-2 font-weight-bold" v-if="route['associated_stores'].length>0">
                            Total No. of Stores : {{ route['associated_stores'].length }}
                        </div>
                        <div v-if="route['associated_stores'].length>0">
                            <div v-for="(store,index) in route['associated_stores']" :key="index">
                                {{ index + 1 }}. <span class="font-weight-medium">Store Code : {{ store['store_code'] }} , </span>
                                {{ store['store_name'] }} ({{ store['store_address'] }})

                            </div>
                        </div>
                        <div v-else>No any stores associated</div>
                    </td>
                    <td>
                        <span class="label ml-2"
                              :class="`label-${deliveryStatus[route['status']]}`">{{
                                formatToTitleCase(route['status'])
                            }}</span></td>
                    <td>
                        <button @click="goToDispatchOrderDetailPage(route['dispatch_route_code'])"
                                class="btn btn-xs btn-info">
                            <span v-if="route['status']==='pending'">Proceed Dispatch</span>
                            <span v-else>Dispatch Detail</span>
                        </button>
                        <button @click="removeTheDispatchedRoute(index,route['dispatch_route_code'])"
                                class="btn btn-xs btn-danger">Delete Route
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-lg-5"></div>
            <div class="col-lg-7">
                <Pagination @change_page_no="changePageNoAndSetActiveAndDisableThePageNo" :pages="totalPages"
                            class="mt-4"></Pagination>
            </div>
        </div>
        <div v-if="snackBar">
            <snack-bar-message :success="snackBarSuccess" :message="snackBarMessage"></snack-bar-message>
        </div>
    </div>
</template>

<script>
import {registerStoreModule, unregisterStoreModule} from "@shared~helpers/checkStoreState";
import DispatchRoute from "@warehouse~store/dispatch-route/store";
import TableConfiguration from "@shared~store/table-config/store";
import SnackBarMessage from "../../shared/components/error-flash/SnackBarMessage";
import Pagination from "../../shared/components/table/Pagination";
import stringFormatter from "@shared~helpers/textConverter";

export default {
    name: "DispatchRouteList",
    components: {Pagination, SnackBarMessage},
    data() {
        return {
            dispatchRoutes: [],
            snackBarMessage: null,
            snackBarSuccess: false,
            snackBar: false,
            doNotGeneratePageNo: false,
            totalPages: [],
            color: ['red', 'green', 'purple', 'blue', 'pink', 'black'],
            deliveryStatus: {
                'dispatched': 'success',
                'pending': 'info'
            },
        }
    },

    created() {

        registerStoreModule('dispatchRoute', DispatchRoute);

        registerStoreModule('tableConfig', TableConfiguration);

        this.getListOfDispatchRoutes();

    },

    beforeRouteLeave(to, from, next) {

        unregisterStoreModule('dispatchRoute');

        unregisterStoreModule('tableConfig');

        next();

    },

    computed: {

        allDispatchRoutes() {

            return this.dispatchRoutes;

        },

        //Get All Meta Information
        getAllMetaInformation() {

            return this.$store.getters['tableConfig/GET_META_INFORMATION'];

        },

        //Get Selected Filter For Table.
        selectedFilterForTable() {
            return this.$store.getters['tableConfig/GET_TABLE_CONFIGURATION'];
        }

    },

    methods: {

        //Function to get the list of dispatch routes.
        getListOfDispatchRoutes() {

            this.$store.dispatch("dispatchRoute/getDispatchRouteList", this.selectedFilterForTable).then(response => {

                console.log(response.data);

                this.dispatchRoutes = response.data;

                this.$store.commit("tableConfig/SET_META_FOR_TABLE_CONFIGURATION", response.meta);

                if (!this.doNotGeneratePageNo) {

                    this.generatePageNumber();

                }

            }).catch(e => {

                this.snackBarMessage = e.response.data.message;

                this.snackBarSuccess = false;

                this.snackBar = true;

            });
        },

        //Generate the page no. based on the last page no.
        generatePageNumber() {

            for (let i = 0; i < this.getAllMetaInformation['last_page']; i++) {

                this.totalPages.push({
                    page_no: i + 1,
                    active: false
                });

            }

        },

        //Change Selected Page class to active and disable the button.
        changePageNoAndSetActiveAndDisableThePageNo(page) {

            this.doNotGeneratePageNo = true;

            for (let i = 0; i < this.totalPages.length; i++) {

                if (this.totalPages[i]['page_no'] === page) {

                    this.totalPages[i]['active'] = true;

                } else {

                    this.totalPages[i]['active'] = false;

                }

            }

            this.getListOfDispatchRoutes();

        },

        //Make the first letter of the word capital.
        formatToTitleCase(name) {
            return stringFormatter.convertToTitleCase(name);
        },

        //Function to delete the dispatch route.
        removeTheDispatchedRoute(index, dispatchRouteCode) {

            this.$store.dispatch('dispatchRoute/deleteDispatchRoute', dispatchRouteCode).then(response => {

                this.snackBarMessage = response.message;

                this.snackBarSuccess = true;

                this.snackBar = true;

                this.dispatchRoutes.splice(index, 1);

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            }).catch(e => {

                this.snackBarMessage = e.response.data.message;

                this.snackBarSuccess = false;

                this.snackBar = true;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            });

        },

        //Function to redirect to the dispatch order detail page.
        goToDispatchOrderDetailPage(dispatchOrderCode) {

            location.href = `${window.location.origin}/warehouse/warehouse-dispatch-routes/${dispatchOrderCode}/show`;

        },

    }
}
</script>

<style scoped>

</style>
