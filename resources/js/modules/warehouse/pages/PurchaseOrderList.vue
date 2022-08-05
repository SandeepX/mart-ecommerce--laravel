<template>
    <div>
        <div v-if="loading">
            <Loading></Loading>
        </div>
        <div v-else-if="purchaseOrderList && !loading">
            <div class="pa-3 white--text subtitle-2"
                 style="background-color: #337ab7;border-top-left-radius: 5px;border-top-right-radius: 5px;">List Of
                Purchase
                Orders
            </div>
            <div class="v-card pa-4">
                <div v-if="filterOptions" class="row">
                    <div class="col-lg-1 text-left">
                        <ShowNumberOfEntries @change_records_per_page="changeRecordsPerPageForTable"
                                             class="mb-4"></ShowNumberOfEntries>
                    </div>
                    <div class="col-lg-1">
                        <div class="subtitle-2 mb-2">Code No. :</div>
                        <input @input="getWarehousePurchaseOrderCode(purchaseOrderFilter['purchase_order_code'])"
                               v-model="purchaseOrderFilter['purchase_order_code']" type="text" class="form-control">
                    </div>
                    <div class="col-lg-2">
                        <div class="subtitle-2 mb-2">Vendor Name :</div>
                        <v-select
                            v-model="purchaseOrderFilter['vendor_name']"
                            :options="filterOptions['vendors']"
                            label="vendor_name"
                            :reduce="vendor_name =>vendor_name['vendor_code']"
                            @input="getSelectedVendor(purchaseOrderFilter['vendor_name'])"
                        />
                    </div>
                    <div class="col-lg-2">
                        <div class="subtitle-2 mb-2">Order Source :</div>
                        <v-select
                            v-model="purchaseOrderFilter['order_source']"
                            :options="filterOptions['order_sources']"
                            @input="getSelectedOrderSource(purchaseOrderFilter['order_source'])"
                        />
                    </div>
                    <div class="col-lg-2">
                        <div class="subtitle-2 mb-2">Payment Type :</div>
                        <v-select
                            v-model="purchaseOrderFilter['payment_type']"
                            :options="filterOptions['payment_types']"
                            @input="getSelectedPaymentType(purchaseOrderFilter['payment_type'])"
                        />
                    </div>
                    <div class="col-lg-2">
                        <div class="subtitle-2 mb-2">Payment Status :</div>
                        <v-select
                            v-model="purchaseOrderFilter['payment_status']"
                            :options="filterOptions['statuses']"
                            @input="getSelectedPaymentStatus(purchaseOrderFilter['payment_status'])"
                        />
                    </div>
                    <div class="col-lg-2">
                        <div class="subtitle-2 mb-2">Order Date :</div>
                        <div class="input-group date datetimepicker" id="picker">
                            <input type="text" class="form-control" v-model="purchaseOrderFilter['order_date']"/>
                            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-10"></div>
                    <div class="col-lg-2 text-right">
                        <button @click="getListOfPurchaseOrders" type="button" class="btn btn-primary btn-sm">Filter
                        </button>
                    </div>
                </div>
                <div v-if="purchaseOrderList.length>0" class="table-responsive">
                    <table class="table table-hover table-bordered" style="margin-bottom: 0 !important;">
                        <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Code No.</th>
                            <th>Vendor</th>
                            <th>Order Source</th>
                            <th>Payment Type</th>
                            <th>Payment Status</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(purchase,index) in purchaseOrderList" :key="index">
                            <td>{{ index + 1 }}.</td>
                            <td>{{ purchase['warehouse_purchase_order_code'] }}</td>
                            <td>{{ purchase['vendor'] }}</td>
                            <td>{{ formatToTitleCase(purchase['order_source']) }}</td>
                            <td>{{formatToTitleCase(purchase['payment_type']) }}</td>
                            <td>{{formatToTitleCase(purchase['status']) }}</td>
                            <td>{{ purchase['order_date'] }}</td>
                            <td>
                                <button @click="viewDetailOfPurchaseOrder(purchase['warehouse_purchase_order_code'])"
                                        type="button" class="btn btn-primary btn-sm">
                                    View More
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-center subtitle-1 font-weight-medium" v-else>
                    Purchase Order Not Available.
                </div>
                <div v-if="purchaseOrderList.length>0" class="row">
                    <div class="col-lg-5"></div>
                    <div class="col-lg-7">
                        <Pagination @change_page_no="changePageNoAndSetActiveAndDisableThePageNo" :pages="totalPages"
                                    class="mt-4"></Pagination>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showErrorAlert">
            <SnackBarMessage :success="success" :message="showSaveAlertMessage"></SnackBarMessage>
        </div>
    </div>
</template>

<script>

import {registerStoreModule, unregisterStoreModule} from "@shared~helpers/checkStoreState";
import PurchaseOrder from "@warehouse~store/purchase-order/store";
import TableConfiguration from "@shared~store/table-config/store";
import stringFormatter from "@shared~helpers/textConverter";

export default {
    name: "PurchaseOrderList",
    components: {
        Pagination: () => import("@shared~components/table/Pagination"),
        ShowNumberOfEntries: () => import("@shared~components/table/ShowNumberOfEntries"),
        SnackBarMessage: () => import("@shared~components/error-flash/SnackBarMessage"),
        Loading: () => import("@shared~components/Loading")
    },
    data() {
        return {
            purchaseOrderList: [],
            show422ErrorMessage: [],
            show422Error: false,
            loading: true,
            showErrorAlert: false,
            showSaveAlertMessage: '',
            success: false,
            totalPages: [],
            doNotGeneratePageNo: false,
            filterOptions: null,
            purchaseOrderFilter: {
                purchase_order_code: null,
                vendor_name: null,
                order_source: null,
                payment_type: null,
                payment_status: null,
                order_date: null,
            }
        }
    },

    mounted() {
        $(function () {
            $('#picker').datetimepicker({
                locale: 'nl',
                minDate: new Date(),
            });
        });
    },


    created() {

        registerStoreModule('purchaseOrder', PurchaseOrder);

        registerStoreModule('tableConfig', TableConfiguration);

        this.getListOfPurchaseOrders();

        this.getOptionsForFiltration();

    },

    beforeRouteLeave(to, from, next) {

        unregisterStoreModule('purchaseOrder');

        unregisterStoreModule('tableConfig');

        next();

    },

    watch: {

        // Disable Save Alert after 1 sec every time the alert is shown.
        showErrorAlert: {

            handler() {

                setTimeout(() => {

                    this.showErrorAlert = false;

                }, 3000);

            },

            immediate: true

        },

    },

    computed: {

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

        //Api to get the list of purchase orders.
        getListOfPurchaseOrders() {

            this.$store.dispatch("purchaseOrder/getListOfPurchaseOrder", this.selectedFilterForTable).then(response => {

                console.log(response.data);

                this.show422ErrorMessage = [];

                this.purchaseOrderList = response.data;

                console.log(response, 'response');

                this.$store.commit("tableConfig/SET_META_FOR_TABLE_CONFIGURATION", response.meta);

                if (!this.doNotGeneratePageNo) {

                    this.generatePageNumber();

                }

                this.loading = false;

            }).catch(e => {

                this.show422ErrorMessage = [];

                if (e.response.data['code'] === 422) {

                    // eslint-disable-next-line no-unused-vars
                    for (let [key, value] of Object.entries(e.response.data.data)) {

                        this.show422ErrorMessage.push({
                            value: true,
                            message: value[0]
                        });

                    }

                    this.show422Error = true;

                } else {

                    this.show422Error = false;

                    this.showSaveAlertMessage = e.response.data.message;

                    this.showErrorAlert = true;

                }

                this.success = false;

                this.loading = false;

            })

        },

        viewDetailOfPurchaseOrder(wh_purchase_order_code) {

            console.log('view details');

            window.open(process.env.MIX_BASE_HOST + 'warehouse/warehouse-purchase-orders/show/' + wh_purchase_order_code,'_blank');

        },

        //Get the list of all the options for filtering the table.
        getOptionsForFiltration() {

            this.$store.dispatch("purchaseOrder/getOptionsForFilter").then(response => {

                console.log(response.data, 'response for filter');

                this.filterOptions = response['data'];

            }).catch(e => {

                this.show422Error = false;

                this.showSaveAlertMessage = e.response.data.message;

                this.showErrorAlert = true;

            })

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

            this.getListOfPurchaseOrders();

        },

        //Change No. of Rows for the table
        changeRecordsPerPageForTable() {

            this.getListOfPurchaseOrders();

        },

        //Selected Vendor For Filter
        getSelectedVendor(vendorCode) {

            console.log('selected', vendorCode);

            this.$store.commit("tableConfig/SET_VENDOR_NAME", vendorCode);

            this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", 1);

        },

        //Selected Order Source Filter
        getSelectedOrderSource(orderSource) {

            console.log('selected', orderSource);

            this.$store.commit("tableConfig/SET_ORDER_SOURCE", orderSource);

            this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", 1);

        },

        //Selected Payment Type Filter
        getSelectedPaymentType(paymentType) {

            console.log('selected', paymentType);

            this.$store.commit("tableConfig/SET_PAYMENT_TYPE", paymentType);

            this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", 1);

        },

        //Selected Payment Status Filter
        getSelectedPaymentStatus(paymentStatus) {

            console.log('selected', paymentStatus);

            this.$store.commit("tableConfig/SET_PAYMENT_STATUS", paymentStatus);

            this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", 1);

        },

        //Selected Purchase Order Code Filter
        getWarehousePurchaseOrderCode(purchaseOrderCode) {

            console.log('selected', purchaseOrderCode);

            this.$store.commit("tableConfig/SET_PURCHASE_ORDER_CODE", purchaseOrderCode);

            this.$store.commit("tableConfig/SET_CURRENT_PAGE_NO", 1);

        },

        //Make the first letter of the word capital.
        formatToTitleCase(name) {
            return stringFormatter.convertToTitleCase(name);
        },

    },

}
</script>

<style scoped>

</style>
