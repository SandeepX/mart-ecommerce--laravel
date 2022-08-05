<template>
    <div>
        <div v-if="loading">
            <Loading></Loading>
        </div>
        <div v-else-if="warehousePurchaseOrderDetail && !loading">
            <div class="v-card pa-5 px-3">
                <div class="row pl-3 pr-5">
                    <div class="col-lg-3 px-3">
                        <div class="v-card-border border-radius">
                            <div class="pa-2 white--text text-uppercase subtitle-2 font-weight-medium"
                                 style="background-color: #337ab7">
                                Warehouse & Vendor Detail
                            </div>
                            <div class="pa-2 mt-1 subtitle-2 font-weight-regular">
                                <div class="mb-3"><strong>Purchase Order Code
                                    : </strong>{{ warehousePurchaseOrderDetail['warehouse_purchase_order_code'] }}
                                </div>
                                <div class="mb-3"><strong>Vendor Code : </strong>
                                    {{ warehousePurchaseOrderDetail['vendor_code'] }}
                                </div>
                                <div class="mb-3"><strong>Vendor Name : </strong>
                                    {{ warehousePurchaseOrderDetail['vendor_name'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="warehousePurchaseOrderDetail['order_discounts'].length>0" class="col-lg-3 px-3">
                        <div class="v-card-border border-radius">
                            <div class="pa-2 white--text text-uppercase subtitle-2 font-weight-medium"
                                 style="background-color: #337ab7">
                                Discounts
                            </div>
                            <div class="pa-2 mt-1 subtitle-2 font-weight-regular">
                                <div v-for="(discount,index) in warehousePurchaseOrderDetail['order_discounts']"
                                     :key="index" class="mb-3">
                                    <div>{{ index + 1 }}. <span>Discount :
                                        <span
                                            v-if="discount['discount_type']==='p'">{{
                                                discount['discount_value']
                                            }} %</span>
                                        <span v-else>Rs. {{ discount['discount_value'] }}</span>
                                    </span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 px-3">
                        <div class="v-card-border border-radius">
                            <div class="pa-2 white--text text-uppercase subtitle-2 font-weight-medium"
                                 style="background-color: #337ab7">
                                Payment Detail
                            </div>
                            <div class="pa-2 mt-1 subtitle-2 font-weight-regular">
                                <div class="mb-3"><strong>Order Source
                                    : </strong>{{ formatToTitleCase(warehousePurchaseOrderDetail['order_source']) }}
                                </div>
                                <div class="mb-3"><strong>Payment Type
                                    : </strong>{{ formatToTitleCase(warehousePurchaseOrderDetail['payment_type']) }}
                                </div>
                                <div class="mb-3"><strong>Total Amount
                                    : </strong>Rs. {{ warehousePurchaseOrderDetail['total_amount'].toFixed(2) }}
                                </div>
                                <div class="mb-3"><strong>Accepted Amount
                                    : </strong>
                                    <span v-if=" warehousePurchaseOrderDetail['accepted_amount']">Rs. {{
                                            warehousePurchaseOrderDetail['accepted_amount'].toFixed(2)
                                        }}</span>
                                    <span v-else>N/A</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 px-3">
                        <div class="v-card-border border-radius">
                            <div class="pa-2 white--text text-uppercase subtitle-2 font-weight-medium"
                                 style="background-color: #337ab7">
                                Remarks & Status
                            </div>
                            <div class="pa-2 mt-1 subtitle-2 font-weight-regular">
                                <div class="mb-3"><strong>Remarks
                                    : </strong>
                                    <button v-if="warehousePurchaseOrderDetail['remarks']" type="button"
                                            class="btn btn-primary btn-xs">Remarks
                                    </button>
                                    <span v-else>N/A</span>
                                </div>
                                <div class="mb-3"><strong>Status
                                    : </strong>{{ formatToTitleCase(warehousePurchaseOrderDetail['status']) }}
                                </div>
                                <div class="mb-3"><strong>Order Date : </strong>
                                    {{ warehousePurchaseOrderDetail['order_date'] }}
                                </div>
                                <div class="mb-3"><strong>Order Note
                                    : </strong>
                                    <span v-if="warehousePurchaseOrderDetail['order_note']">{{
                                            warehousePurchaseOrderDetail['order_note']
                                        }}</span>
                                    <span v-else>N/A</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5 px-4">
                <div class="v-card pa-5">
                    <div class="row px-4">
                        <div class="col-lg-12 v-card-border border-radius px-0">
                            <div class="pa-2 white--text text-uppercase subtitle-2 font-weight-medium"
                                 style="background-color: #337ab7">
                                Purchase Order Detail
                            </div>
                            <div class="pa-3">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered"
                                           style="margin-bottom: 0 !important;">
                                        <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Order Code</th>
                                            <th>Product Info</th>
                                            <th>Package Name</th>
                                            <th>Quantity</th>
                                            <th>C.P/unit</th>
                                            <th>Scheme Amt.</th>
                                            <th>Discount Amt.</th>
                                            <th>Net Total</th>
                                            <th>Status</th>
                                            <th>Ordered By</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(order,index) in warehousePurchaseOrderDetail['purchase_order_details']"
                                            :key="index">
                                            <td>{{ index + 1 }}.</td>
                                            <td>{{ order['order_detail_code'] }}</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        <img :src=" order['product_image']" height="50">
                                                    </div>
                                                    <div class="col-lg-10">
                                                        <div class="mb-1">{{ order['product_name'] }}</div>
                                                        <div v-if="order['product_variant_name']" class="mb-1">Variant :
                                                            {{ order['product_variant_name'] }}
                                                        </div>
                                                        <div v-if="order['is_taxable']" class="mb-1">Taxable</div>
                                                        <div v-else class="mb-1">Non-Taxable</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mb-1">Ordered : {{ order['ordered_package_name'] }}</div>
                                                <div v-if="order['accepted_package_name']" class="mb-1">Accepted :
                                                    {{ order['accepted_package_name'] }}
                                                </div>
                                                <div v-else class="mb-1">Accepted : N/A</div>
                                            </td>
                                            <td>
                                                <div class="mb-1">Ordered : {{ order['ordered_quantity'] }}</div>
                                                <div v-if="order['accepted_quantity']" class="mb-1">Accepted :
                                                    {{ order['accepted_quantity'] }}
                                                </div>
                                                <div v-else class="mb-1">Accepted : N/A</div>
                                            </td>
                                            <td>Rs. {{ order['unit_cp'] }}</td>
                                            <td>Rs. {{ order['scheme_amount'] }}</td>
                                            <td>Rs. {{ order['discount_amount'] }}</td>
                                            <td>Rs. {{ order['net_total'] }}</td>
                                            <td>{{ formatToTitleCase(order['acceptance_status']) }}</td>
                                            <td>{{ order['orderedBy'] }}</td>
                                            <td>
                                                <div v-if="order['schemeable']" class="mb-2">
                                                    <button
                                                        @click="openDialogBoxForSchemeDetail(order,order['order_detail_code'])"
                                                        type="button"
                                                        class="btn btn-primary btn-sm">Scheme Offer
                                                    </button>
                                                </div>
                                                <div v-if="order['remarks']" class="mb-2">
                                                    <button @click="openDialogBoxForRemarks(order['remarks'])"
                                                            type="button" class="btn btn-primary btn-sm">Remarks
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="warehousePurchaseOrderDetail['status_logs'].length>0" class="row mt-5 px-4">
                <div class="v-card pa-5">
                    <div class="row px-4">
                        <div class="col-lg-12 v-card-border border-radius px-0">
                            <div class="pa-2 white--text text-uppercase subtitle-2 font-weight-medium"
                                 style="background-color: #337ab7">
                                Purchase Order Status Log
                            </div>
                            <div class="pa-3">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered"
                                           style="margin-bottom: 0 !important;">
                                        <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Updated By</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(status,index) in warehousePurchaseOrderDetail['status_logs']"
                                            :key="index">
                                            <td>{{ index + 1 }}.</td>
                                            <td>{{ status['updated_by'] }}</td>
                                            <td>{{ formatToTitleCase(status['status']) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showErrorAlert">
            <SnackBarMessage :success="success" :message="showSaveAlertMessage"></SnackBarMessage>
        </div>
        <ViewDetailDialogBox :detail_to_show="selectedPurchaseOrderDetail"
                             :action="selectedAction"
                             :title_name="selectedProductName"
                             v-if="showModal && selectedPurchaseOrderDetail && selectedProductName"
                             @close="showModal = false">
        </ViewDetailDialogBox>
    </div>
</template>

<script>
import {registerStoreModule, unregisterStoreModule} from "@shared~helpers/checkStoreState";
import PurchaseOrder from "@warehouse~store/purchase-order/store";
import fetchParamsFromUrl from "@shared~helpers/getTheParamFromTheUrl";
import stringFormatter from "@shared~helpers/textConverter";

export default {
    name: "ShowOrderDetail",
    components: {
        ViewDetailDialogBox: () => import("@shared~components/ViewDetailDialogBox"),
        Loading: () => import("@shared~components/Loading"),
        SnackBarMessage: () => import("@shared~components/error-flash/SnackBarMessage")
    },
    data() {
        return {
            showErrorAlert: false,
            success: false,
            showSaveAlertMessage: '',
            warehousePurchaseOrderDetail: null,
            loading: true,
            selectedPurchaseOrderDetail: null,
            showModal: false,
            selectedAction: '',
            selectedProductName: null,
        }
    },

    computed: {},

    beforeRouteLeave(to, from, next) {

        unregisterStoreModule('purchaseOrder');

        next();

    },

    watch: {

        $routes: {

            handler() {

                registerStoreModule('purchaseOrder', PurchaseOrder);

                this.getDetailOfSelectedPurchaseOrder();

            },

            immediate: true

        },

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

    methods: {

        //Api to get the detail of selected purchase order.
        getDetailOfSelectedPurchaseOrder() {

            let wh_purchase_order_code = fetchParamsFromUrl();

            this.$store.dispatch("purchaseOrder/getDetailOfSelectedWhPurchaseOrder", wh_purchase_order_code).then(response => {

                console.log(response.data, 'response');

                this.warehousePurchaseOrderDetail = response.data;

                this.loading = false;

            }).catch(e => {

                console.log(e.response);

                this.show422Error = false;

                this.success = false;

                this.showSaveAlertMessage = e.response.data.message;

                this.showErrorAlert = true;

                this.loading = false;

            })

        },

        //Make the first letter of the word capital.
        formatToTitleCase(name) {
            return stringFormatter.convertToTitleCase(name);
        },

        //Open Scheme Product Viewing Dialog Box.
        openDialogBoxForSchemeDetail(order, orderCode) {

            console.log(orderCode, 'product');

            this.$store.dispatch("purchaseOrder/getSchemeProductDetail", orderCode).then(response => {

                console.log(response.data, 'response');

                this.showModal = true;

                this.selectedPurchaseOrderDetail = response.data;

                this.selectedAction = 'view_scheme_for_purchase_order_detail';

                this.selectedProductName = order;

            }).catch(e => {

                console.log(e.response, 'error');

                this.show422Error = false;

                this.success = false;

                this.showSaveAlertMessage = e.response.data.message;

                this.showErrorAlert = true;

            })

        },

        //Open Remarks Dialog Box
        openDialogBoxForRemarks(remarks) {

            console.log(remarks, 'product');

            this.showModal = true;

            this.selectedPurchaseOrderDetail = remarks;

            this.selectedAction = 'show_remarks';

        },

    }

}
</script>

<style scoped>

</style>
