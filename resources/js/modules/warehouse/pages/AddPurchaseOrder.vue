<template>
    <div class="container-fluid subtitle-1 pa-3" style="position: relative !important;">
        <div v-if="loading">
            <Loading></Loading>
        </div>
        <div v-else>
            <div v-if="filterList">
                <div class="row pa-5 grey_background">
                    <div v-if="error" class="col-lg-12">
                        <div class="alert alert-info alert-dismissible fade in">
                            <span class="glyphicon glyphicon-info-sign mr-3" style="font-size: 18px;"></span><strong>Note
                            :</strong>{{ errorMessage }}
                        </div>
                    </div>
                    <div v-if="show422Error" class="col-lg-12">
                        <ErrorMessage422 :errors="show422ErrorMessage"></ErrorMessage422>
                    </div>
                    <div class="col-lg-5 v-card pa-3">
                        <div class="mb-5 pa-2 subtitle-1 text-uppercase grey_background">Filter</div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-2 subtitle-2 font-weight-bold">Select Vendor :</div>
                                <v-select
                                    v-model="selectedVendor"
                                    :options="filterList['vendors']"
                                    label="vendor_name"
                                    :reduce="vendor_name =>vendor_name['vendor_code']"
                                    @input="currentPage=1"
                                />
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-2 subtitle-2 font-weight-bold">Select Brand :</div>
                                <v-select
                                    v-model="selectedBrand"
                                    :options="filterList['brands']"
                                    label="brand_name"
                                    @input="currentPage=1"
                                    :reduce="brand_name =>brand_name['brand_code']"
                                />
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-2 subtitle-2 font-weight-bold">Select Category :</div>
                                <v-select
                                    v-model="selectedCategory"
                                    :options="filterList['categories']"
                                    label="category_name"
                                    @input="currentPage=1"
                                    :reduce="category_name =>category_name['category_code']"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1"></div>
                    <div class="col-lg-6 v-card pa-3">
                        <div class="mb-5 pa-2 subtitle-1 text-uppercase grey_background">
                            <div class="row">
                                <div class="col-lg-10">Product Selection</div>
                                <div class="col-lg-2" style="margin-top: -2px !important;">
                                    <button @click="createPurchaseOrderList" type="button"
                                            class="btn btn-primary btn-xs">+
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="mb-2 subtitle-2 font-weight-bold">Product Name :</div>
                                <v-select
                                    v-model="selectedProductName"
                                    label="product_name"
                                    :options="productList"
                                    :filterable="false"
                                    @input="setProductVariantAndPackagingUnit(selectedProductName)"
                                    @search="searchProductList">
                                    <template slot="no-options">
                                        Type to search....
                                    </template>
                                    <template slot="option" slot-scope="option">
                                        <div class="d-center">
                                            <img width="30" height="30" :src='option.product_image'/>
                                            {{ option.product_name }}
                                        </div>
                                    </template>
                                    <li slot="list-footer" class="pagination">
                                        <button @click="loadMoreProductList" :disabled="!hasMoreProducts">Load More
                                        </button>
                                    </li>
                                </v-select>
                            </div>
                            <div class="col-lg-3">
                                <div v-if="showVariantList" class="mb-2 subtitle-2 font-weight-bold">Variant Name :
                                </div>
                                <v-select
                                    v-if="showVariantList"
                                    :options="variantList"
                                    v-model="selectedVariantName"
                                    label="product_variant_name"
                                    @input="getSelectedVariantPackagingUnit(selectedVariantName)"
                                />
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-2 subtitle-2 font-weight-bold">Package Type :</div>
                                <v-select
                                    :options="packagingTypeList"
                                    v-model="selectedPackagingTypeName"
                                    label="package_name"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="v-card ml-1 mr-1 row subtitle-2 pa-3">
                <div class="col-lg-3 pa-0">
                    <div class="pa-3 text-center" v-if="purchaseOrder.length>0"
                         style="background-color:#3c8dbc !important;color: white !important;">
                        <strong class="mr-3">Payment method:</strong>
                        <label class="radio-inline"><input @click="setPaymentMethod('cash')" type="radio"
                                                           name="payment_method" checked
                                                           style="margin-top: 0 !important;">Cash</label>
                        <label class="radio-inline"><input @click="setPaymentMethod('credit')" type="radio"
                                                           name="payment_method"
                                                           style="margin-top: 0 !important;">Credit</label>
                    </div>
                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-3">
                    <input class="form-control" @input="searchProductName(searchProductNameInPurchaseOrderList)"
                           v-model="searchProductNameInPurchaseOrderList"
                           placeholder="Search Product Name....">
                </div>
            </div>
            <table v-if="purchaseOrder.length>0" class="table v-card">
                <thead>
                <tr>
                    <th class="subtitle-2 font-weight-bold text-center" style="width: 3%">S.N</th>
                    <th class="subtitle-2 font-weight-bold text-center" style="width: 16%">Product</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 7%">Variant</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 5%">Packaging</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 5%">Quantity</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 5%">CP(/1)</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 7%">Sub Total</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 9%">Scheme</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 10%">Gross Cp</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 10%">Discount</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 5%">ECS</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 6%">Taxable CP</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 3%">VAT</th>
                    <th class="subtitle-2  font-weight-bold text-center" style="width: 6%">NET CP</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in purchaseOrder" :key="product['purchase_order_code']">
                    <td class="subtitle-2 text-center">
                        <div>{{ product['serial_no'] }}.</div>
                        <div class="mt-1">
                            <button @click="removePurchaseOrderFromTheList(product['purchase_order_code'])"
                                    type="button"
                                    class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </div>
                    </td>
                    <td class="subtitle-2">
                        <div class="row">
                            <div class="col-lg-3">
                                <img height="60" :src="product['product_name']['product_image']">
                            </div>
                            <div class="col-lg-9 mt-1">
                                {{ product['product_name']['product_name'] }}
                            </div>
                        </div>
                    </td>
                    <td class="subtitle-2 text-center">
                        <div v-if="product['variant_name']">{{ product['variant_name']['product_variant_name'] }}</div>
                        <div v-else>N/A</div>
                    </td>
                    <td class="subtitle-2 text-center">{{ product['package_name']['package_name'] }}</td>
                    <td class="subtitle-2 text-center">
                        <input v-model="product.quantity"
                               @input="changeSubTotalAccordingToQuantity(product)"
                               type="number" class="form-control">
                        <div
                            v-if="product['scheme']['selected_same_item_scheme']==='stock' && product['scheme']['is_scheme_enabled']"
                            class="small mt-3 text-left font-weight-bold"
                            style="color:green !important;">
                            Free : {{ product['free_stock'] }}
                        </div>
                        <div
                            v-if="product['scheme']['selected_same_item_scheme']==='stock' && product['scheme']['is_scheme_enabled']"
                            class="small mt-1 text-left font-weight-bold"
                            style="color:green !important;">
                            Total : {{ product['new_quantity'] }}
                        </div>
                    </td>
                    <td class="subtitle-2 text-center">
                        <div>Rs. {{ product['cost_price_per_unit'].toFixed(2) }}</div>
                        <div class="text-right"
                             v-if="product['scheme']['selected_same_item_scheme'] && product['scheme']['is_scheme_enabled']">
                            <a href="#" data-toggle="tooltip" data-placement="bottom"
                               :title="`Actual Cost Price :Rs . ${product['before_scheme_cost_price']}`"><span
                                class="glyphicon glyphicon-info-sign"></span></a>
                        </div>
                    </td>
                    <td class="subtitle-2 text-center">Rs. {{ product['sub_total'].toFixed(2) }}</td>
                    <td class="subtitle-2">
                        <div v-if="product['scheme']['scheme_name']==='Cash Scheme'">
                            <div class="font-weight-bold">{{ product['scheme']['scheme_name'] }}</div>
                            <div class="small" v-if="product['scheme']['cash_discount_type']==='p'">( {{
                                    product['scheme']['cash_discount_value']
                                }} % off )
                            </div>
                            <div class="mt-2 mb-2 font-weight-bold">Rs. {{ product['scheme']['scheme_value'] }}</div>
                            <div class="mt-2 mb-2 small font-weight-bold">Target Qty. : {{
                                    product['scheme']['target_quantity']
                                }}
                            </div>
                            <div v-if="product['scheme']['is_scheme_enabled']">
                                <div class="small font-weight-bold">
                                    <span style="color:green !important;">Enabled </span>
                                </div>
                            </div>
                            <div v-else>
                                <div class="small font-weight-bold">
                                    <span style="color:red !important;">Not Enabled</span>
                                    <a href="#" data-toggle="tooltip" data-placement="bottom"
                                       title="Note : Scheme is Disabled. To enable, please select quantity greater than or multiple of target quantity."><span
                                        class="glyphicon glyphicon-info-sign"></span></a>
                                </div>
                                <div class="text-right">
                                </div>
                            </div>
                        </div>
                        <div v-else-if="product['scheme']['scheme_name']==='Item Scheme'">
                            <div v-if="product['scheme']['scheme_type']==='same'">
                                <div class="font-weight-bold mb-2">Same Item Scheme</div>
                                <div v-if="product['scheme']['same_item_scheme_information']"
                                     class="mb-2 small font-weight-bold" style="color:green !important;">(
                                    {{ product['scheme']['same_item_scheme_information'] }}
                                    )
                                </div>
                                <div class="small font-weight-bold mb-2"
                                     v-if="product['scheme']['selected_same_item_scheme']">
                                    Target Qty. : {{ product['scheme']['package_quantity'] }}
                                </div>
                                <!--                            <label class="radio-inline"><input @change="getCashDiscountFromTheSameItemScheme(product)"
                                                                                               type="radio" name="cash">Cash</label>-->
                                <label class="radio-inline"><input checked readonly
                                                                   @change="getStockDiscountFromTheSameItemScheme(product)"
                                                                   type="radio" name="cash">Stock</label>
                                <div class="mt-2 text-right"
                                     v-if="product['scheme']['selected_same_item_scheme']===null">
                                    <a href="#" data-toggle="tooltip" data-placement="bottom"
                                       title="Note : Please Select Scheme to see the scheme offer."><span
                                        class="glyphicon glyphicon-info-sign"></span></a>
                                </div>
                                <div
                                    v-if="product['scheme']['is_scheme_enabled'] && product['scheme']['selected_same_item_scheme']"
                                    class="mt-2">
                                    <div class="small font-weight-bold">
                                        <span style="color:green !important;">Enabled </span>
                                    </div>
                                </div>
                                <div
                                    v-else-if="!product['scheme']['is_scheme_enabled'] && product['scheme']['selected_same_item_scheme']"
                                    class="mt-2">
                                    <div class="small font-weight-bold">
                                        <span style="color:red !important;">Not Enabled</span>
                                        <a href="#" data-toggle="tooltip" data-placement="bottom"
                                           title="Note : Scheme is Disabled. To enable, please select quantity greater than or multiple of target quantity."><span
                                            class="glyphicon glyphicon-info-sign"></span></a>
                                    </div>
                                    <div class="text-right">
                                    </div>
                                </div>
                            </div>
                            <div class="font-weight-bold" v-else>
                                <div>Different Item Scheme</div>
                                <button
                                    id="show-modal" @click="openDialogBox(product)"
                                    type="button" class="btn btn-info btn-xs mt-3">View Item
                                </button>
                            </div>
                        </div>
                        <div class="subtitle-2 font-weight-bold text-center"
                             v-else-if="product['scheme']['scheme_name']===''">
                            N / A
                        </div>
                    </td>
                    <td class="subtitle-2">
                        <div>
                            <strong>Per Unit :</strong> Rs. {{ product['gross_cost_price_per_unit'].toFixed(2) }}
                        </div>
                        <div class="mt-2 ml-0 pl-0">
                            <strong>Total :</strong> Rs. {{ product['gross_cost_price'].toFixed(2) }}
                        </div>
                    </td>
                    <td class="subtitle-2">
                        <div v-if="product['bulk_discount_detail']">
                            <strong>Bulk :</strong> Rs. {{ product['bulk_discount_amount'].toFixed(2) }}
                        </div>
                        <div class="mt-2 ml-0 pl-0" v-if="product['cash_discount_detail']">
                            <strong>Cash :</strong> Rs. {{ product['cash_discount_amount'].toFixed(2) }}
                            <button v-if="product['payment_method']==='cash'" type="button"
                                    class="btn-xs btn-success mt-1">Activated
                            </button>
                            <button v-if="product['payment_method']==='credit'" type="button"
                                    class="btn-xs btn-danger mt-2">Deactivated
                            </button>
                            <a v-if="product['payment_method']==='credit'" href="#" data-toggle="tooltip"
                               class="ml-3 mt-2"
                               data-placement="bottom"
                               title="Cash Discount is enable only when the payment method Cash is selected."><span
                                class="glyphicon glyphicon-info-sign"></span></a>
                        </div>
                    </td>
                    <td class="subtitle-2 text-center">
                        <div v-if="product['ecs_amount']">Rs. {{ product['ecs_amount'].toFixed(2) }}</div>
                        <div v-else>Rs. {{ product['ecs_amount'] }}</div>
                    </td>
                    <td class="subtitle-2 text-center">Rs. {{ product['taxable_cost_price'].toFixed(2) }}</td>
                    <td class="subtitle-2 text-center">
                        <div v-if="product['is_taxable']">{{ product['vat'] }} %</div>
                        <div v-else>---</div>
                    </td>
                    <td class="subtitle-2 text-center">Rs. {{ product['net_cost_price'].toFixed(2) }}</td>
                </tr>
                </tbody>
            </table>
            <div v-if="purchaseOrder.length===0" class="v-card pa-10 subtitle-2 text-center">No Purchase Order
                Available.
            </div>
            <div v-if="this.addNewPurchaseOrder.length>0"
                 class="subtitle-1 grey_background v-card pa-3 font-weight-bold">
                <div class="row">
                    <div class="col-lg-10 mt-2 text-center"> Total Cost Price : Rs. {{ totalNetCP.toFixed(2) }}</div>
                    <div class="col-lg-1 text-right">
                        <button @click="savePurchaseOrder('draft')" type="button" class="btn btn-primary btn-sm">Save as
                            Draft
                        </button>
                    </div>
                    <div class="col-lg-1 text-right">
                        <button @click="savePurchaseOrder('send')" type="button" class="btn btn-primary btn-sm">Save
                            Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showSaveAlert" class="col-lg-12">
            <SnackBarMessage :success="success" :message="showSaveAlertMessage"></SnackBarMessage>
        </div>
        <ViewDetailDialogBox :detail_to_show="offeredSelectedDifferentItem"
                             :action="selectedAction"
                             v-if="showModal && offeredSelectedDifferentItem"
                             @close="showModal = false">
            <h3 slot="header" class="teal-color-text">Different Item Scheme</h3>
        </ViewDetailDialogBox>
    </div>
</template>

<script>
import Vue from "vue";
import {registerStoreModule, unregisterStoreModule} from "@shared~helpers/checkStoreState";
import PurchaseOrder from "@warehouse~store/purchase-order/store";

export default {
    name: 'AddPurchaseOrder',
    components: {
        Loading: () => import("@shared~components/Loading"),
        ErrorMessage422: () => import("@shared~components/error-flash/ErrorMessage422"),
        SnackBarMessage: () => import("@shared~components/error-flash/SnackBarMessage"),
        ViewDetailDialogBox: () => import("@shared~components/ViewDetailDialogBox")
    },
    data() {
        return {
            addNewPurchaseOrder: [],
            observer: null,
            limit: 4,
            search: '',
            productLists: [],
            currentPage: 1,
            nextPage: false,
            paginationDetail: null,
            searchText: null,
            filterDetail: null,
            selectedVendor: null,
            selectedBrand: null,
            selectedCategory: null,
            error: false,
            selectedProductName: null,
            selectedVariantList: [],
            selectedVariantName: null,
            selectedPackagingTypeList: [],
            selectedPackagingTypeName: null,
            errorMessage: '',
            showVariantList: true,
            netCostPrice: 0,
            showSaveAlert: false,
            showSaveAlertMessage: '',
            success: false,
            show422Error: false,
            show422ErrorMessage: [],
            selectedProductSelectionInformation: null,
            searchProductNameInPurchaseOrderList: null,
            showModal: false,
            offeredSelectedDifferentItem: null,
            searchListOfPurchaseOrderList: [],
            selectedPaymentMethod: null,
            loading: true,
            selectedAction: '',
        }
    },

    created() {

        registerStoreModule('purchaseOrder', PurchaseOrder);

        // To get the filter vendors,brands and category lists.
        this.getFilterDetailForPurchaseOrder();

    },

    beforeRouteLeave(to, from, next) {

        unregisterStoreModule('purchaseOrder');

        next();

    },

    watch: {

        // Disable Alert after 1 sec every time the alert is shown.
        error: {

            handler() {

                setTimeout(() => {

                    this.error = false;

                }, 3000);

            },

            immediate: true

        },

        // Disable Save Alert after 1 sec every time the alert is shown.
        showSaveAlert: {

            handler() {

                setTimeout(() => {

                    this.showSaveAlert = false;

                }, 3000);

            },

            immediate: true

        },

        // To Calculate Total Net Cost Price.
        addNewPurchaseOrder: {

            deep: true,

            handler() {

                if (this.addNewPurchaseOrder.length > 0) {

                    this.netCostPrice = 0;

                    this.addNewPurchaseOrder.map((data, index) => {

                        data['serial_no'] = index + 1;

                        this.netCostPrice += data['net_cost_price'];

                    });

                }

            },

            immediate: true

        }

    },

    computed: {

        // Product List pagination details.
        pagination() {
            return this.paginationDetail;
        },

        // Purchase Order Lists.
        purchaseOrder() {
            return this.addNewPurchaseOrder;
        },

        // Product Lists to be displayed in the v-select.
        productList() {
            return this.productLists;
        },

        // Check if the list of product api has another page to hit api.
        hasMoreProducts() {
            return this.nextPage;
        },

        //Selected Product variant list.
        variantList() {
            return this.selectedVariantList;
        },

        //Selected Variant Packaging Detail list.
        packagingTypeList() {
            return this.selectedPackagingTypeList;
        },

        //Total Net Cost Price
        totalNetCP() {
            return this.netCostPrice;
        },

        filterList() {
            return this.filterDetail;
        }

    },

    methods: {

        //Function to search for the product in the purchase order list.
        searchProductName(search) {

            let searchResultForPurchaseOrder = search => this.searchListOfPurchaseOrderList.filter(({product_name}) => product_name['product_name'].toLowerCase().includes(search));

            this.addNewPurchaseOrder = searchResultForPurchaseOrder(search);

        },

        //Function to set the payment method for all the purchase order.
        setPaymentMethod(selectedPayment) {

            this.selectedPaymentMethod = selectedPayment;

            this.addNewPurchaseOrder.map(data => {

                data['payment_method'] = selectedPayment;

                this.convertBulkCashAndECSToAmount(data);

            });

            console.log(this.addNewPurchaseOrder, 'purchase order');

        },

        //Function to open dialog box and pass data as props.
        openDialogBox(product) {

            console.log(product, 'product');

            this.showModal = true;

            this.offeredSelectedDifferentItem = product['different_item_scheme'];

            this.selectedAction = 'view_add_purchase_schemeable_product';

        },

        // Function to calculate Stock Discount and other Gross Cost Price and Net CP.
        getStockDiscountFromTheSameItemScheme(product) {

            product['cost_price_per_unit'] = product['before_scheme_cost_price'];

            product['scheme']['selected_same_item_scheme'] = 'stock';

            console.log('stock discount ko lagi');

            let selectedPurchaseOrderIndex = this.addNewPurchaseOrder.findIndex(data => data['purchase_order_code'] === product['purchase_order_code']);

            // To show information regarding the same item cash scheme.
            product['scheme']['same_item_scheme_information'] = `Buy ${product['scheme']['package_quantity']} ${product['package_name']['package_name']} get ${product['scheme']['scheme_item_quantity']} ${product['scheme']['scheme_packaging']} free.`

            Vue.set(this.addNewPurchaseOrder, selectedPurchaseOrderIndex, product);

            product['before_scheme_cost_price'] = product['cost_price_per_unit'];

            this.changeSubTotalAccordingToQuantity(product);

        },

        // Function to calculate cash discount and other Gross Cost Price and Net CP.
        getCashDiscountFromTheSameItemScheme(product) {

            product['cost_price_per_unit'] = product['before_scheme_cost_price'];

            product['scheme']['selected_same_item_scheme'] = 'cash';

            let selectedPurchaseOrderIndex = this.addNewPurchaseOrder.findIndex(data => data['purchase_order_code'] === product['purchase_order_code']);

            // To show information regarding the same item cash scheme.
            product['scheme']['same_item_scheme_information'] = `Buy ${product['scheme']['package_quantity']} ${product['package_name']['package_name']} get ${product['scheme']['scheme_item_quantity']} ${product['scheme']['scheme_packaging']} free.`

            Vue.set(this.addNewPurchaseOrder, selectedPurchaseOrderIndex, product);

            product['before_scheme_cost_price'] = product['cost_price_per_unit'];

            this.changeSubTotalAccordingToQuantity(product);

        },

        //Function to remove the Purchase Order from the list.
        removePurchaseOrderFromTheList(purchaseOrderCode) {

            let selectedPurchaseOrderIndex = this.addNewPurchaseOrder.findIndex(data => data['purchase_order_code'] === purchaseOrderCode);

            this.addNewPurchaseOrder.splice(selectedPurchaseOrderIndex, 1);

        },

        // Function to evaluate Net CP when the quantity is automatically 1 when first loaded.
        evaluateNetCPBasedOnQuantityOnFirstLoad() {

            this.purchaseOrder.map(data => {

                this.changeSubTotalAccordingToQuantity(data);

                if (data['scheme']['scheme_type'] === 'same') {

                    this.getStockDiscountFromTheSameItemScheme(data);

                }

            });

        },

        // Function to convert the Bulk, Cash and ECS percentage to amount.
        convertBulkCashAndECSToAmount(product) {

            let GrossCostPrice = product['gross_cost_price'];

            let bulkDiscountPercentage = null;

            let cashDiscountPercentage = null;

            let ecsPercentage = product['ecs'];

            if (product['bulk_discount_detail']) {

                if (product['bulk_discount_detail']['discount_type'] === 'p') {

                    bulkDiscountPercentage = product['bulk_discount_detail']['discount_value'];

                    product['bulk_discount_amount'] = (bulkDiscountPercentage / 100) * GrossCostPrice;

                } else {

                    product['bulk_discount_amount'] = product['bulk_discount_detail']['discount_value'];

                }

            } else {

                product['bulk_discount_amount'] = 0;

            }

            if (product['cash_discount_detail']) {

                if (product['cash_discount_detail']['discount_type'] === 'p') {

                    cashDiscountPercentage = product['cash_discount_detail']['discount_value'];

                    product['cash_discount_amount'] = (cashDiscountPercentage / 100) * GrossCostPrice;

                } else {

                    product['cash_discount_amount'] = product['cash_discount_detail']['discount_value'];

                }

            } else {

                product['cash_discount_amount'] = 0;

            }

            if (product['ecs']) {

                product['ecs_amount'] = (ecsPercentage / 100) * GrossCostPrice;

            } else {

                product['esc_amount'] = 0;

            }

            if (product['payment_method'] === 'cash') {

                product['taxable_cost_price'] = GrossCostPrice - product['bulk_discount_amount'] - product['cash_discount_amount'] + product['ecs_amount'];

            } else if (product['payment_method'] === 'credit') {

                product['taxable_cost_price'] = GrossCostPrice - product['bulk_discount_amount'] + product['ecs_amount'];

            }

            //Add 13% VAT if product is taxable.
            if (product['is_taxable']) {

                product['net_cost_price'] = (113 / 100) * product['taxable_cost_price'];

            }

            // Show taxable Cost Price as Net Cost Price.
            else {

                product['net_cost_price'] = product['taxable_cost_price'];

            }

        },

        // Function to update subtotal, Gross CP and others based on quantity update.
        changeSubTotalAccordingToQuantity(product) {

            if (parseInt(product.quantity) <= 0) {

                product['quantity'] = 1;

            }

            let Quantity = parseInt(product.quantity);

            let costPerUnit = product.cost_price_per_unit;

            product['sub_total'] = Quantity * costPerUnit;

            if (product['scheme']['scheme_name'] !== '') {

                // For Cash Scheme
                if (product['scheme']['scheme_name'] === 'Cash Scheme') {

                    this.calculateCashSchemeDiscountAlongWithNetCp(Quantity, product);

                }

                //For Item Scheme
                else {

                    if (product['scheme']['selected_same_item_scheme']) {

                        this.calculateSameItemSchemeAfterSameItemSchemeIsSelected(Quantity, product);

                    } else {

                        this.calculateSameItemSchemeWhenFirstLoading(Quantity, product);

                    }

                }

            } else {

                product['gross_cost_price'] = product['sub_total'];

                product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

            }

            this.convertBulkCashAndECSToAmount(product);


        },

        calculateSameItemSchemeAfterSameItemSchemeIsSelected(Quantity, product) {

            if (product['scheme']['selected_same_item_scheme'] === 'cash') {

                this.updateNetCPBasedOnSameItemSchemeCashConversion(Quantity, product);

            } else {

                this.updateNetCPBasedOnSameItemSchemeStockConversion(Quantity, product);

            }

        },

        // Function to calculate Net CP based on cash conversion.
        updateNetCPBasedOnSameItemSchemeCashConversion(Quantity, product) {

            //If package quantity matches the purchase quantity.
            if (Quantity >= product['scheme']['package_quantity']) {

                if (!product['scheme']['is_scheme_enabled']) {

                    product['sub_total'] = product['sub_total'] - (product['scheme']['scheme_item_quantity'] * product['warehouse_cost_price_per_packaging']);

                    product['cost_price_per_unit'] = product['sub_total'] / Quantity;

                } else {

                    product['sub_total'] = Quantity * product['cost_price_per_unit'];

                    product['cost_price_per_unit'] = product['sub_total'] / Quantity;

                }

                console.log(product['sub_total'], 'total price', product['before_scheme_cost_price'], 'before ko cost price');

                product['gross_cost_price'] = product['sub_total'];

                product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

                product['scheme']['is_scheme_enabled'] = true;

                this.convertBulkCashAndECSToAmount(product);

            }

            // If packaging quantity does not match the quantity.
            else {

                product['scheme']['is_scheme_enabled'] = false;

                if (product['before_scheme_cost_price']) {

                    product['cost_price_per_unit'] = product['before_scheme_cost_price'];

                }

                let costPerUnit = product['cost_price_per_unit'];

                product['sub_total'] = Quantity * costPerUnit;

                product['gross_cost_price'] = product['sub_total'];

                product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

                this.convertBulkCashAndECSToAmount(product);

            }


        },

        // Function to calculate Net CP based on stock conversion.
        updateNetCPBasedOnSameItemSchemeStockConversion(Quantity, product) {

            console.log('stock conversion', product, Quantity);

            let howManyTimesTheQuantityIsGreaterThanPackageQuantity = Math.floor(Quantity / product['scheme']['package_quantity']);

            //If purchase quantity matches the package  quantity.
            if (Quantity === (howManyTimesTheQuantityIsGreaterThanPackageQuantity * product['scheme']['package_quantity'])) {

                console.log('match vayo exactly');

                product['new_quantity'] = Quantity + (Quantity / product['scheme']['package_quantity']) * product['scheme']['scheme_item_quantity'];

                product['free_stock'] = product['new_quantity'] - Quantity;

                if (!product['scheme']['is_scheme_enabled']) {

                    product['cost_price_per_unit'] = product['sub_total'] / product['new_quantity'];

                    product['gross_cost_price'] = product['sub_total'];

                    product['gross_cost_price_per_unit'] = product['gross_cost_price'] / product['new_quantity'];

                    product['scheme']['is_scheme_enabled'] = true;

                } else {

                    product['cost_price_per_unit'] = product['sub_total'] / Quantity;

                    product['gross_cost_price'] = product['sub_total'];

                    product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

                    product['scheme']['is_scheme_enabled'] = true;

                }

                this.convertBulkCashAndECSToAmount(product);

            }

            // If purchase quantity is greater than package quantity.
            else if (Quantity > product['scheme']['package_quantity']) {

                console.log('match vayo but badi pani xa');

                product['scheme']['is_scheme_enabled'] = true;

                product['new_quantity'] = Quantity + Math.floor(Quantity / product['scheme']['package_quantity']) * product['scheme']['scheme_item_quantity'];

                product['free_stock'] = product['new_quantity'] - Quantity;

                let costPerUnit = product['cost_price_per_unit'];

                product['sub_total'] = Quantity * costPerUnit;

                product['gross_cost_price'] = product['sub_total'];

                product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

                this.convertBulkCashAndECSToAmount(product);

            }

            // If purchase quantity is less than package quantity.
            else {

                console.log('less xa');

                product['scheme']['is_scheme_enabled'] = false;

                let costPerUnit = product['cost_price_per_unit'];

                product['sub_total'] = Quantity * costPerUnit;

                product['gross_cost_price'] = product['sub_total'];

                product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

                this.convertBulkCashAndECSToAmount(product);

            }

        },

        // Function to show default Gross CP,Discount and Net CP when item scheme is not selected.
        calculateSameItemSchemeWhenFirstLoading(Quantity, product) {

            product['scheme']['is_scheme_enabled'] = false;

            product['gross_cost_price'] = product['sub_total'];

            product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

        },

        // Function to calculate the cash scheme Gross CP, Discounts and Net CP.
        calculateCashSchemeDiscountAlongWithNetCp(Quantity, product) {

            let NumberWhenQuantityGreaterAndMultipleOfTargetQuantity = (Quantity / product['scheme']['target_quantity']);

            //Check if the scheme is ready to be activated by comparing the target quantity.

            let remainderToBeCalculatedWhenQuantityDividedByTargetQuantity = (Quantity % product['scheme']['target_quantity']);

            //If no remainder, minus with the gross cost price.

            if (!remainderToBeCalculatedWhenQuantityDividedByTargetQuantity) {

                product['scheme']['is_scheme_enabled'] = true;

                product['scheme']['scheme_value'] = product['scheme']['cash_discount_value'] * NumberWhenQuantityGreaterAndMultipleOfTargetQuantity;

                product['gross_cost_price'] = product['sub_total'] - product['scheme']['scheme_value'];

                product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

            }

            // If target quantity does not match the quantity.
            else {

                if (Math.floor(NumberWhenQuantityGreaterAndMultipleOfTargetQuantity)) {

                    product['scheme']['is_scheme_enabled'] = true;

                    product['scheme']['scheme_value'] = product['scheme']['cash_discount_value'] * Math.floor(NumberWhenQuantityGreaterAndMultipleOfTargetQuantity);

                    product['gross_cost_price'] = product['sub_total'] - product['scheme']['scheme_value'];

                    product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

                } else {

                    product['scheme']['is_scheme_enabled'] = false;

                    product['gross_cost_price'] = product['sub_total'];

                    product['gross_cost_price_per_unit'] = product['gross_cost_price'] / Quantity;

                }

            }

        },

        // Function to show the filter detail for vendors,brands and categories from the api.
        getFilterDetailForPurchaseOrder() {

            this.$store.dispatch('purchaseOrder/filterListOfVendorBrandsAndCategory')

                .then(response => {

                    console.log(response, 'response');

                    this.filterDetail = response.data;

                    this.loading = false;

                })

                .catch((e) => {

                    console.log(e, 'error');

                    this.showSaveAlertMessage = e.response.data.message;

                    this.showSaveAlert = true;

                    this.loading = false;

                });

        },

        // Function to Add Purchase Order Based on Product Selection.
        createPurchaseOrderList() {

            this.searchProductNameInPurchaseOrderList = '';

            this.searchProductName(this.searchProductNameInPurchaseOrderList);

            // If product name, variant name and package type is selected.
            if (this.selectedProductName && this.selectedVariantName && this.selectedPackagingTypeName || !this.selectedProductName['has_variants']) {

                // Parameters to pass for the api.
                let params = null;

                if (this.selectedProductName['has_variants']) {

                    params = {
                        'product_variant_code': this.selectedVariantName['product_variant_code'],
                        'package_code': this.selectedPackagingTypeName['package_code'],
                        'vendor_code': this.selectedVendor,
                        'brand_codes': this.selectedBrand,
                        'category_codes': this.selectedCategory
                    }

                } else {

                    params = {
                        'package_code': this.selectedPackagingTypeName['package_code'],
                        'vendor_code': this.selectedVendor,
                        'brand_codes': this.selectedBrand,
                        'category_codes': this.selectedCategory
                    }

                }

                let payload = {
                    product_code: this.selectedProductName['product_code'],
                    params: params
                }

                //Hit The Api To Get The Cost Price Details along with discount amount.

                this.$store.dispatch('purchaseOrder/getTheDetailOfProductWithSelectedPackageAndVariant', payload)
                    .then((response) => {

                        this.selectedProductSelectionInformation = response.data;

                        // Create purchase order based on packaging type selected.
                        // Create purchase order code based on product code , variant code and package code.
                        let purchaseOrderCode = '';

                        //If has variants.
                        if (this.selectedProductName['has_variants']) {

                            purchaseOrderCode = this.selectedProductName['product_code'] + '-' + this.selectedVariantName['product_variant_code'] + '-' + this.selectedPackagingTypeName['package_code'];

                        } else {

                            purchaseOrderCode = this.selectedProductName['product_code'] + '-' + this.selectedPackagingTypeName['package_code'];

                        }

                        // Checking if the purchase order is already created. If created show already and do not push the object to the array.
                        let checkIfThePurchaseOrderIsAlreadyCreated = this.addNewPurchaseOrder.some(data => data['purchase_order_code'] === purchaseOrderCode);

                        // Show Alert and do not push the object.
                        if (checkIfThePurchaseOrderIsAlreadyCreated) {

                            this.error = true;

                            this.errorMessage = 'Purchase Order Already Exists.';

                        }

                        // Create New Purchase Order
                        else {

                            // Check which scheme is availabe.

                            let availableSchemeForProduct = {

                                scheme_name: '',
                                scheme_value: 0,
                                is_scheme_enabled: false,
                                same_item_scheme_information: null,

                            };

                            let availableDifferentScheme = null;

                            if (this.selectedProductSelectionInformation['product_cash_scheme']) {

                                availableSchemeForProduct = this.selectedProductSelectionInformation['product_cash_scheme'];

                                availableSchemeForProduct['scheme_name'] = 'Cash Scheme';

                                if (availableSchemeForProduct['cash_discount_type'] === 'f') {

                                    availableSchemeForProduct['scheme_value'] = availableSchemeForProduct['cash_discount_value'];

                                } else {

                                    let discountValueWhilePercentage = (availableSchemeForProduct['cash_discount_value'] / 100) * this.selectedProductSelectionInformation['price_setting']['warehouse_cost_price_per_packaging'];

                                    availableSchemeForProduct['scheme_value'] = this.selectedProductSelectionInformation['price_setting']['warehouse_cost_price_per_packaging'] - discountValueWhilePercentage;

                                    availableSchemeForProduct['cash_discount_value'] = discountValueWhilePercentage;

                                }

                            } else if (this.selectedProductSelectionInformation['product_item_scheme']) {

                                availableSchemeForProduct = this.selectedProductSelectionInformation['product_item_scheme'];

                                availableDifferentScheme = this.selectedProductSelectionInformation['product_item_scheme']['schemeable_product_detail'];

                                availableSchemeForProduct['scheme_name'] = 'Item Scheme';

                                availableSchemeForProduct = this.selectedProductSelectionInformation['product_item_scheme'];

                                availableSchemeForProduct['scheme_value'] = 0;

                                availableSchemeForProduct['same_item_scheme_information'] = null;

                                if (availableSchemeForProduct['scheme_type'] === 'same') {

                                    availableSchemeForProduct['selected_same_item_scheme'] = null;

                                } else {

                                    console.log('different item scheme');

                                }

                            } else {

                                availableSchemeForProduct = {

                                    scheme_name: '',
                                    scheme_value: 0,
                                    is_scheme_enabled: false,
                                    is_same_item_scheme_enabled: false,
                                    same_item_scheme_information: null,

                                };

                            }

                            // Check If Discount is available for the product.

                            let bulkDiscountForProduct = null;

                            let cashDiscountForProduct = null;

                            if (this.selectedProductSelectionInformation['vendor_global_discounts']) {

                                if (this.selectedProductSelectionInformation['vendor_global_discounts']['bulk-discount']) {

                                    bulkDiscountForProduct = this.selectedProductSelectionInformation['vendor_global_discounts']['bulk-discount'];

                                }

                                if (this.selectedProductSelectionInformation['vendor_global_discounts']['cash-discount']) {

                                    cashDiscountForProduct = this.selectedProductSelectionInformation['vendor_global_discounts']['cash-discount'];

                                }

                            }

                            this.addNewPurchaseOrder.push({
                                'serial_no': this.addNewPurchaseOrder.length + 1,
                                'purchase_order_code': purchaseOrderCode,
                                'product_name': this.selectedProductName,
                                'variant_name': this.selectedVariantName,
                                'package_name': this.selectedPackagingTypeName,
                                'quantity': 1,
                                'new_quantity': availableSchemeForProduct['package_quantity'],
                                'free_stock': null,
                                'cost_price_per_unit': this.selectedProductSelectionInformation['price_setting']['warehouse_cost_price_per_packaging'],
                                'before_scheme_cost_price': this.selectedProductSelectionInformation['price_setting']['warehouse_cost_price_per_packaging'],
                                'warehouse_cost_price_per_packaging': this.selectedProductSelectionInformation['price_setting']['warehouse_cost_price_per_packaging'],
                                'sub_total': 0,
                                'scheme': availableSchemeForProduct,
                                'gross_cost_price_per_unit': 0,
                                'gross_cost_price': 0,
                                'bulk_discount_detail': bulkDiscountForProduct,
                                'bulk_discount': bulkDiscountForProduct ? bulkDiscountForProduct['discount_value'] : 'N/A',
                                'bulk_discount_amount': null,
                                'cash_discount_detail': cashDiscountForProduct,
                                'cash_discount': cashDiscountForProduct ? cashDiscountForProduct['discount_value'] : 'N/A',
                                'cash_discount_amount': null,
                                'different_item_scheme': availableDifferentScheme,
                                'ecs': this.selectedProductSelectionInformation['price_setting']['ecs_percentage'],
                                'ecs_amount': null,
                                'taxable_cost_price': 0,
                                'is_taxable': this.selectedProductName['is_taxable'],
                                'vat': 13,
                                'net_cost_price': 0,
                            });

                            this.searchListOfPurchaseOrderList = [...this.addNewPurchaseOrder];

                            this.evaluateNetCPBasedOnQuantityOnFirstLoad();

                            this.setPaymentMethod('cash');

                        }

                        this.addNewPurchaseOrder.sort((a, b) => (a.serial_no > b.serial_no && a.product_name['product_code'] > b.product_name['product_code']) ? 1 : -1);

                        // Reset all the Product selection values.
                        this.selectedProductName = null;

                        this.selectedVariantName = null;

                        this.selectedPackagingTypeName = null;

                    }).catch((e) => {

                    this.showSaveAlertMessage = e.response.data.message;

                    this.showSaveAlert = true;

                });

            }

            // If product name, variant name and package type not selected.
            else {

                this.error = true;

                this.errorMessage = "Please Select Product Name, Variant Name & Package Type to add Purchase Order."

            }

        },

        // Function to load more product in a list when scroll down in the v-select.
        loadMoreProductList() {

            this.currentPage = this.currentPage + 1;

            if (this.selectedVendor) {

                this.error = false;

                if (this.currentPage < this.pagination['last_page']) {

                    let params = {

                        vendor_code: this.selectedVendor,

                        page: this.currentPage,

                        search: escape(this.searchText),

                        brand_codes: this.selectedBrand,

                        category_codes: this.selectedCategory
                    }

                    this.$store.dispatch('purchaseOrder/loadMoreProductsListAccordingToTheSelectedVendor', params)

                        .then(response => {

                            this.productLists = this.productLists.concat(response.data);

                            this.paginationDetail = response['meta'];

                            if (response['links']['next']) {

                                this.nextPage = true;

                            }

                        })

                        .catch((e) => {

                            console.log(e, 'error');

                            this.showSaveAlertMessage = e.response.data.message;

                            this.showSaveAlert = true;

                        })

                }

            } else {

                this.error = true;

            }

        },

        // Function to search for the product.
        searchProductList(search, loading) {

            this.searchText = escape(search);

            if (search.length) {

                loading(true);

                this.getProductList(loading, search);

            }

        },

        // Function to hit the Api for searching the product list.
        getProductList(loading, search) {

            if (this.selectedVendor) {

                this.error = false;

                let params = {

                    vendor_code: this.selectedVendor,

                    page: this.currentPage,

                    search: escape(search),

                    brand_codes: this.selectedBrand,

                    category_codes: this.selectedCategory
                }

                this.$store.dispatch('purchaseOrder/loadMoreProductsListAccordingToTheSelectedVendor', params)

                    .then(response => {

                        this.productLists = response.data;

                        this.paginationDetail = response['meta'];

                        if (response['links']['next']) {

                            this.nextPage = true;

                        }

                        loading(false);

                    })

                    .catch((e) => {

                        console.log(e, 'error');

                        this.showSaveAlertMessage = e.response.data.message;

                        this.showSaveAlert = true;

                        loading(false);

                    })

            } else {

                this.error = true;

                this.errorMessage = 'Please select vendor to search for the products.';

                loading(false);

            }

        },

        //Function to show the product variant list and unit packaging list for selection.
        setProductVariantAndPackagingUnit(product) {

            // Reset selected variant and package name.
            this.selectedVariantName = null;

            this.selectedPackagingTypeName = null;

            //If the product has variants.
            if (product['product_variants'].length > 0) {

                this.showVariantList = true;

                this.selectedVariantList = product['product_variants'];

            }

            //If the product has no variant.
            else {

                this.showVariantList = false;

                this.selectedPackagingTypeList = product['unit_packaging_detail'];

            }

        },

        //Function to Get The selected Variant Packaging Unit.
        getSelectedVariantPackagingUnit(variant) {

            // Reset selected variant and package name.
            this.selectedPackagingTypeName = null;

            this.selectedPackagingTypeList = variant['unit_packaging_detail'];

        },

        //Function to save Purchase Order List.
        savePurchaseOrder(saveType) {

            let fd = new FormData();

            fd.append(`vendor_code`, this.selectedVendor);

            fd.append(`payment_type`, this.selectedPaymentMethod);

            fd.append(`submit_type`, saveType);

            for (let i = 0; i < this.purchaseOrder.length; i++) {

                fd.append(`product_code[${i}]`, this.purchaseOrder[i]['product_name']['product_code'])

                console.log(this.purchaseOrder[i]);

                if (this.purchaseOrder[i]['variant_name']) {

                    fd.append(`product_variant_code[${i}]`, this.purchaseOrder[i]['variant_name']['product_variant_code'])

                } else {

                    fd.append(`product_variant_code[${i}]`, '');

                }

                fd.append(`package_code[${i}]`, this.purchaseOrder[i]['package_name']['package_code'])

                fd.append(`quantity[${i}]`, this.purchaseOrder[i]['quantity'])

            }

            this.$store.dispatch("purchaseOrder/savePurchaseOrder", fd)

                .then(response => {

                    console.log(response, 'response');

                    this.show422Error = false;

                    this.show422ErrorMessage = [];

                    this.success = true;

                    this.showSaveAlertMessage = response.data;

                    this.showSaveAlert = true;

                }).catch((e) => {

                this.success = false;

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

                    this.showSaveAlert = true;

                }

                console.log(e, e.response, 'error');

            });

        }

    }
}
</script>

<style>

.hover_icon:hover {
    cursor: pointer !important;
}

.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
    display: table;
    transition: opacity .3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 300px;
    margin: 0 auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
    transition: all .3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.modal-body {
    margin: 20px 0;
}

.modal-default-button {
    float: right;
}

/*
 * the following styles are auto-applied to elements with
 * v-transition="modal" when their visiblity is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter, .modal-leave {
    opacity: 0;
}

.modal-enter .modal-container,
.modal-leave .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}

.v-card {
    box-shadow: 0 3px 1px -2px rgb(0 0 0 / 20%), 0 2px 2px 0 rgb(0 0 0 / 14%), 0 1px 5px 0 rgb(0 0 0 / 12%);
    background-color: #fff;
    color: rgba(0, 0, 0, .87);
    border-width: thin;
    display: block;
    max-width: 100%;
    outline: none;
    text-decoration: none;
    transition-property: box-shadow, opacity;
    overflow-wrap: break-word;
    position: relative;
    white-space: normal;
}

.v-card-border {
    background-color: #fff;
    color: rgba(0, 0, 0, .87);
    border-width: thin;
    display: block;
    max-width: 100%;
    outline: none;
    border: 1px solid #337ab7;
    text-decoration: none;
    transition-property: box-shadow, opacity;
    overflow-wrap: break-word;
    position: relative;
    white-space: normal;
}

.subtitle-2 {
    font-size: 14px !important;
    font-weight: 500 !important;
    line-height: 1.375rem;
    letter-spacing: .0071428571em !important;
    font-family: Roboto, sans-serif !important;
}

.subtitle-1 {
    font-size: 16px !important;
    font-weight: 400;
    line-height: 1.75rem;
    letter-spacing: .009375em !important;
    font-family: Roboto, sans-serif !important;
}

.body-1 {
    font-size: 16px !important;
    font-weight: 400;
    line-height: 1.5rem;
    letter-spacing: .03125em !important;
    font-family: Roboto, sans-serif !important;
}

.body-2 {
    font-size: 14px !important;
    font-family: Roboto, sans-serif !important;
    font-weight: 400;
    line-height: 1.25rem;
    letter-spacing: .0178571429em !important;
}

.caption {
    font-size: 12px !important;
    font-family: Roboto, sans-serif !important;
    font-weight: 400;
    line-height: 1.25rem;
    letter-spacing: .0333333333em !important;
}

.overline {
    font-size: 12rem !important;
    font-family: Roboto, sans-serif !important;
}

.font-weight-medium {
    font-weight: 500 !important;
}

.font-weight-bold {
    font-weight: bold !important;
}

.font-weight-regular {
    font-weight: 400;
}

.mt-1 {
    margin-top: 4px;
}

.mt-2 {
    margin-top: 8px;
}

.mt-3 {
    margin-top: 12px;
}

.mt-4 {
    margin-top: 16px;
}

.mt-5 {
    margin-top: 20px;
}

.mt-6 {
    margin-top: 24px;
}

.mt-7 {
    margin-top: 28px;
}

.mt-8 {
    margin-top: 32px;
}

.mt-9 {
    margin-top: 36px;
}

.mt-10 {
    margin-top: 40px;
}


.mb-1 {
    margin-bottom: 4px;
}

.mb-2 {
    margin-bottom: 8px;
}

.mb-3 {
    margin-bottom: 12px;
}

.mb-4 {
    margin-bottom: 16px;
}

.mb-5 {
    margin-bottom: 20px;
}

.mb-6 {
    margin-bottom: 24px;
}

.mb-7 {
    margin-bottom: 28px;
}

.mb-8 {
    margin-bottom: 32px;
}

.mb-9 {
    margin-bottom: 36px;
}

.mb-10 {
    margin-bottom: 40px;
}


.my-1 {
    margin-top: 4px;
    margin-bottom: 4px;
}

.my-2 {
    margin-top: 8px;
    margin-bottom: 8px;
}

.my-3 {
    margin-top: 12px;
    margin-bottom: 12px;
}

.my-4 {
    margin-top: 16px;
    margin-bottom: 16px;
}

.my-5 {
    margin-top: 20px;
    margin-bottom: 20px;
}

.my-6 {
    margin-top: 24px;
    margin-bottom: 24px;
}

.my-7 {
    margin-top: 28px;
    margin-bottom: 28px;
}

.my-8 {
    margin-top: 32px;
    margin-bottom: 32px;
}

.my-9 {
    margin-top: 36px;
    margin-bottom: 36px;
}

.my-10 {
    margin-top: 40px;
    margin-bottom: 40px;
}

.my-0 {
    margin-top: 0;
    margin-bottom: 0;
}


.ml-1 {
    margin-left: 4px;
}

.ml-2 {
    margin-left: 8px;
}

.ml-3 {
    margin-left: 12px;
}

.ml-4 {
    margin-left: 16px;
}

.ml-5 {
    margin-left: 20px;
}

.ml-6 {
    margin-left: 24px;
}

.ml-7 {
    margin-left: 28px;
}

.ml-8 {
    margin-left: 32px;
}

.ml-9 {
    margin-left: 36px;
}

.ml-10 {
    margin-left: 40px;
}

.mx-0 {
    margin-left: 0;
    margin-right: 0;
}

.mx-1 {
    margin-left: 4px;
    margin-right: 4px;
}

.mx-2 {
    margin-left: 8px;
    margin-right: 8px;
}

.mx-3 {
    margin-left: 12px;
    margin-right: 12px;
}

.mx-4 {
    margin-left: 16px;
    margin-right: 16px;
}

.mx-5 {
    margin-left: 20px;
    margin-right: 20px;
}

.mx-6 {
    margin-left: 24px;
    margin-right: 24px;
}

.mx-7 {
    margin-left: 28px;
    margin-right: 28px;
}

.mx-8 {
    margin-left: 32px;
    margin-right: 32px;
}

.mx-9 {
    margin-left: 36px;
    margin-right: 36px;
}

.mx-10 {
    margin-left: 40px;
    margin-right: 40px;
}


.mr-1 {
    margin-right: 4px;
}

.mr-2 {
    margin-right: 8px;
}

.mr-3 {
    margin-right: 12px;
}

.mr-4 {
    margin-right: 16px;
}

.mr-5 {
    margin-right: 20px;
}

.mr-6 {
    margin-right: 24px;
}

.mr-7 {
    margin-right: 28px;
}

.mr-8 {
    margin-right: 32px;
}

.mr-9 {
    margin-right: 36px;
}

.mr-10 {
    margin-right: 40px;
}


.ma-0 {
    margin: 0 0 0 0;
}

.ma-1 {
    margin: 4px 4px 4px 4px;
}

.ma-2 {
    margin: 8px 8px 8px 8px;
}

.ma-3 {
    margin: 12px 12px 12px 12px;
}

.ma-4 {
    margin: 16px 16px 16px 16px;
}

.ma-5 {
    margin: 20px 20px 20px 20px;
}

.ma-6 {
    margin: 24px 24px 24px 24px;
}

.ma-7 {
    margin: 28px 28px 28px 28px;
}

.ma-8 {
    margin: 32px 32px 32px 32px;
}

.ma-9 {
    margin: 36px 36px 36px 36px;
}

.ma-10 {
    margin: 40px 40px 40px 40px;
}


.pa-0 {
    padding: 0 0 0 0;
}

.pa-1 {
    padding: 4px 4px 4px 4px;
}

.pa-2 {
    padding: 8px 8px 8px 8px;
}

.pa-3 {
    padding: 12px 12px 12px 12px;
}

.pa-4 {
    padding: 16px 16px 16px 16px;
}

.pa-5 {
    padding: 20px 20px 20px 20px;
}

.pa-6 {
    padding: 24px 24px 24px 24px;
}

.pa-7 {
    padding: 28px 28px 28px 28px;
}

.pa-8 {
    padding: 32px 32px 32px 32px;
}

.pa-9 {
    padding: 36px 36px 36px 36px;
}

.pa-10 {
    padding: 40px 40px 40px 40px;
}


.pl-1 {
    padding-left: 4px;
}

.pl-2 {
    padding-left: 8px;
}

.pl-3 {
    padding-left: 12px;
}

.pl-4 {
    padding-left: 16px;
}

.pl-5 {
    padding-left: 20px;
}

.pl-6 {
    padding-left: 24px;
}

.pl-7 {
    padding-left: 28px;
}

.pl-8 {
    padding-left: 32px;
}

.pl-9 {
    padding-left: 36px;
}

.pl-10 {
    padding-left: 40px;
}


.pr-1 {
    padding-right: 4px;
}

.pr-2 {
    padding-right: 8px;
}

.pr-3 {
    padding-right: 12px;
}

.pr-4 {
    padding-right: 16px;
}

.pr-5 {
    padding-right: 20px;
}

.pr-6 {
    padding-right: 24px;
}

.pr-7 {
    padding-right: 28px;
}

.pr-8 {
    padding-right: 32px;
}

.pr-9 {
    padding-right: 36px;
}

.pr-10 {
    padding-right: 40px;
}

.px-0 {
    padding-left: 0;
    padding-right: 0;
}

.px-1 {
    padding-left: 4px;
    padding-right: 4px;
}

.px-2 {
    padding-left: 8px;
    padding-right: 8px;
}

.px-3 {
    padding-left: 12px;
    padding-right: 12px;
}

.px-4 {
    padding-left: 16px;
    padding-right: 16px;
}

.px-5 {
    padding-left: 20px;
    padding-right: 20px;
}

.px-6 {
    padding-left: 24px;
    padding-right: 24px;
}

.px-7 {
    padding-left: 28px;
    padding-right: 28px;
}

.px-8 {
    padding-left: 32px;
    padding-right: 32px;
}

.px-9 {
    padding-left: 36px;
    padding-right: 36px;
}

.px-10 {
    padding-left: 40px;
    padding-right: 40px;
}


.pt-1 {
    padding-top: 4px;
}

.pt-2 {
    padding-top: 8px;
}

.pt-3 {
    padding-top: 12px;
}

.pt-4 {
    padding-top: 16px;
}

.pt-5 {
    padding-top: 20px;
}

.pt-6 {
    padding-top: 24px;
}

.pt-7 {
    padding-top: 28px;
}

.pt-8 {
    padding-top: 32px;
}

.pt-9 {
    padding-top: 36px;
}

.pt-10 {
    padding-top: 40px;
}


.pb-1 {
    padding-bottom: 4px;
}

.pb-2 {
    padding-bottom: 8px;
}

.pb-3 {
    padding-bottom: 12px;
}

.pb-4 {
    padding-bottom: 16px;
}

.pb-5 {
    padding-bottom: 20px;
}

.pb-6 {
    padding-bottom: 24px;
}

.pb-7 {
    padding-bottom: 28px;
}

.pb-8 {
    padding-bottom: 32px;
}

.pb-9 {
    padding-bottom: 36px;
}

.pb-10 {
    padding-bottom: 40px;
}

.py-0 {
    padding-bottom: 0;
    padding-top: 0;
}

.py-1 {
    padding-bottom: 4px;
    padding-top: 4px;
}

.py-2 {
    padding-bottom: 8px;
    padding-top: 8px;
}

.py-3 {
    padding-bottom: 12px;
    padding-top: 12px;
}

.py-4 {
    padding-bottom: 16px;
    padding-top: 16px;
}

.py-5 {
    padding-bottom: 20px;
    padding-top: 20px;
}

.py-6 {
    padding-bottom: 24px;
    padding-top: 24px;
}

.py-7 {
    padding-bottom: 28px;
    padding-top: 28px;
}

.py-8 {
    padding-bottom: 32px;
    padding-top: 32px;
}

.py-9 {
    padding-bottom: 36px;
    padding-top: 36px;
}

.py-10 {
    padding-bottom: 40px;
    padding-top: 40px;
}

.teal-color-background {
    background-color: #009688;
}

.teal-color-text {
    color: #05796a;
}

.light-blue-background {
    background-color: #e7f2ff;
}

.light-teal-background {
    background-color: #d8efec;
}

.light-teal-title-background {
    background-color: #a1d6cf;
}

.grey_background {
    background-color: #e5e5e5 !important;
}

.pagination {
    display: flex;
    margin: .25rem .25rem 0;
}

.pagination button {
    flex-grow: 1;
}

.pagination button:hover {
    cursor: pointer;
}

.white--text {
    color: white !important;
}

.black--text {
    color: black !important;
}

.grey-text {
    color: grey !important;
}

.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border-top: 1px solid #cecece !important;
    border-right: 1px solid #cecece !important;
}

.table > thead > tr > th {
    border-bottom: 2px solid #f4f4f4;
    background: #e5e5e5;
}

.border-radius {
    border-top-right-radius: 5px !important;
    border-top-left-radius: 5px !important;
}
</style>
