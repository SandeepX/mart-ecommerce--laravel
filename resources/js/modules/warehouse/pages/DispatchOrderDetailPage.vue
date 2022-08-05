<template>
    <div class="container-fluid">
        <div v-if="dispatchRouteInfo===null && loading">
            <Loading></Loading>
        </div>
        <div v-else-if="dispatchRouteDetail && !loading">
            <div v-if="dispatchRouteInfo">
                <div class="v-card pa-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div>Dispatch Route Name : {{ dispatchRouteDetail['dispatch_route_name'] }}</div>
                                    <div>Dispatch Route Code : {{ dispatchRouteDetail['dispatch_route_code'] }}</div>
                                </div>
                                <div class="col-lg-3">
                                    <div>Created By : {{ dispatchRouteDetail['created_by'] }}</div>
                                    <div>Created At : {{ dispatchRouteDetail['created_at'] }}</div>
                                </div>
                                <div class="col-lg-3">
                                    <div>Updated By : {{ dispatchRouteDetail['updated_by'] }}</div>
                                    <div>Updated At : {{ dispatchRouteDetail['updated_at'] }}</div>
                                </div>
                                <div class="col-lg-3 text-right">
                                    Status :<span class="label ml-2"
                                                  :class="`label-${deliveryStatus[dispatchRouteDetail['status']]}`">{{
                                        formatToTitleCase(dispatchRouteDetail['status'])
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="v-card pa-3 mt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row pa-3">
                                <div class="col-lg-12 white--text pa-2 mb-4" style="background-color: #0d578d">Dispatch
                                    Route Stores
                                </div>
                                <div style="background-color: #00a7d0" class="col-lg-12 mb-3 pa-2 white--text">
                                    Note : Only Orders with status <span
                                    class="font-weight-bold">Ready to Dispatch</span>
                                    can be dispatched.
                                </div>
                                <div v-for="(store,index) in dispatchRouteInfo['associated_stores']" :key="index"
                                     class="col-lg-3">
                                    <div class="v-card pa-2">
                                        <div class="row">
                                            <div class="col-lg-6">Store Name: {{ store['store_name'] }}</div>
                                            <div class="col-lg-6 text-right">Total : Rs.
                                                {{ store['total_amount'].toFixed(2) }}
                                            </div>
                                            <div class="col-lg-10">Location : {{ store['store_address'] }}</div>
                                            <div v-if="dispatchRouteDetail['status']==='pending'"
                                                 class="col-lg-2 text-right"><i
                                                @click="confirmDialogBoxOpen(store['wh_dispatch_route_store_code'])"
                                                style="color: red !important;"
                                                class="fa fa-trash cursor_hover"
                                                aria-hidden="true"></i></div>
                                            <div class="col-lg-11 ml-4 my-3 pa-1 white--text text-uppercase"
                                                 style="background-color: #0d578d">Orders Detail
                                            </div>
                                            <div v-for="(order,index) in store['store_orders']" :key="index"
                                                 class="col-lg-12 mb-2">
                                                <div class="row px-1">
                                                    <div class="col-lg-12">
                                                        <div class="alert my-0 pa-1 pb-2"
                                                             :class="order['has_been_added']?'alert-success':'alert-danger'"
                                                             role="alert">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div
                                                                        v-if="!order['has_been_added'] &&  dispatchRouteDetail['status']==='pending' && order['status']==='ready_to_dispatch'"
                                                                        class="checkbox pa-0 ma-0">
                                                                        <label><input @click=""
                                                                                      v-model="order['add_the_order']"
                                                                                      type="checkbox">
                                                                            {{ order['order_code'] }}</label>
                                                                    </div>
                                                                    <div v-else>
                                                                        {{ order['order_code'] }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 text-right">Rs.
                                                                    {{ order['total_amount'].toFixed(2) }}
                                                                </div>
                                                                <div class="col-lg-4 mt-1"><span
                                                                    class="label label-info">{{
                                                                        formatToTitleCase(order['status'])
                                                                    }}</span>
                                                                </div>
                                                                <div v-if="dispatchRouteDetail['status']==='pending'"
                                                                     class="col-lg-4 mt-1">
                                                                <span @click="doNotDispatchSelectedOrder(order)"
                                                                      v-if="order['has_been_added']"
                                                                      class="label label-danger cursor_hover">Do Not Dispatch</span>
                                                                </div>
                                                                <div class="col-lg-4 mt-1 text-right"><span
                                                                    class="label label-info">{{
                                                                        formatToTitleCase(order['order_type'])
                                                                    }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="dispatchRouteDetail['status']==='pending'" class="col-lg-12">
                                                <button @click="saveTheStoreOrdersForTheDispatch(store)" type="button"
                                                        class="btn btn-primary btn-block">Save Changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="v-card pa-3 mt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row pa-3">
                                <div class="col-lg-12 white--text pa-2 mb-4" style="background-color: #0d578d">Vehicle
                                    Information
                                </div>
                                <div class="col-lg-12">
                                    <div v-if="dispatchRouteDetail['status']==='pending'" class="row">
                                        <div class="col-lg-12 mb-3" style="color:red !important;">Asterick (*) field is
                                            required.
                                        </div>
                                        <div class="col-lg-2">
                                            Vehicle Name <span style="color:red;">*</span> :
                                            <input v-model="vehicleInformation['vehicle_name']" type="text"
                                                   class="form-control mt-2" id="vehicle_name">
                                        </div>
                                        <div class="col-lg-2">
                                            Vehicle Number <span style="color:red;">*</span> :
                                            <input v-model="vehicleInformation['vehicle_number']" type="text"
                                                   class="form-control mt-2" id="vehicle_number">
                                        </div>
                                        <div class="col-lg-2">
                                            Driver Name <span style="color:red;">*</span> :
                                            <input v-model="vehicleInformation['driver_name']" type="text"
                                                   class="form-control mt-2" id="driver_name">
                                        </div>
                                        <div class="col-lg-2">
                                            Driver Licence No. <span style="color:red;">*</span> :
                                            <input v-model="vehicleInformation['driver_license_number']" type="text"
                                                   class="form-control mt-2" id="driver_license_number">
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    Driver Number (Primary) <span style="color:red;">*</span> : <label
                                                    style="color:red;font-size: 0.8rem !important;"
                                                    v-if="invalidPrimaryNumber">Must be 10 digits</label>
                                                </div>
                                                <div
                                                    style="background-color: #e5e5e5;height: 34px;border:1px solid grey"
                                                    class="col-lg-2 px-0 mt-2">
                                                    <div class="text-center mt-1">+977</div>
                                                </div>
                                                <div class="col-lg-10 px-0">
                                                    <input
                                                        @input="checkForPrimaryNumberValidation(vehicleInformation['driver_number_primary'])"
                                                        v-model="vehicleInformation['driver_number_primary']"
                                                        type="number"
                                                        class="form-control mt-2" id="driver_number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    Driver Number (Secondary) : <label
                                                    style="color:red;font-size: 0.8rem !important;"
                                                    v-if="invalidSecondaryNumber">Must be 10 digits</label>
                                                </div>
                                                <div
                                                    style="background-color: #e5e5e5;height: 34px;border:1px solid grey"
                                                    class="col-lg-2 px-0 mt-2">
                                                    <div class="text-center mt-1">+977</div>
                                                </div>
                                                <div class="col-lg-10 px-0">
                                                    <input
                                                        @input="checkForSecondaryNumberValidation(vehicleInformation['driver_number_secondary'])"
                                                        v-model="vehicleInformation['driver_number_secondary']"
                                                        type="number"
                                                        class="form-control mt-2" id="driver_number_secondary">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>S.N</th>
                                                <th>Vehicle Name</th>
                                                <th>Vehicle Number</th>
                                                <th>Driver Name</th>
                                                <th>Driver Number</th>
                                                <th>Driver License No.</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>1.</td>
                                                <td>{{ dispatchRouteDetail['vehicle_name'] }}</td>
                                                <td>{{ dispatchRouteDetail['vehicle_number'] }}</td>
                                                <td>{{ dispatchRouteDetail['driver_name'] }}</td>
                                                <td>
                                                    <div>Primary : {{
                                                            dispatchRouteDetail['driver_contact_primary']
                                                        }}
                                                    </div>
                                                    <div v-if="dispatchRouteDetail['driver_contact_secondary']"
                                                         class="mt-1">Secondary :
                                                        {{ dispatchRouteDetail['driver_contact_secondary'] }}
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ dispatchRouteDetail['driver_license_number'] }}
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
                <div
                    v-if="dispatchRouteVerificationQuestions.length > 0 && dispatchRouteDetail['status']!=='dispatched'"
                    class="v-card pa-3 mt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row pa-3">
                                <div class="col-lg-12 white--text pa-2 mb-4" style="background-color: #0d578d">
                                    Verification Questions
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div v-for="(question,index) in dispatchRouteVerificationQuestions" :key="index"
                                 class="row px-3 mb-3">
                                <div class="checkbox">
                                    <label><input @change="selectVerificationQuestions(question)"
                                                  v-model="question['is_checked']"
                                                  type="checkbox">{{ question['question'] }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="verifiedQuestion.length>0 && dispatchRouteDetail['status']==='dispatched'"
                     class="v-card pa-3 mt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row pa-3">
                                <div class="col-lg-12 white--text pa-2 mb-4" style="background-color: #0d578d">
                                    Verification Questions
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div v-for="(question,index) in verifiedQuestion" :key="index"
                                 class="row px-3 mb-3">
                                <div class="checkbox">
                                    <label><input readonly checked disabled
                                                  type="checkbox">{{ question }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="dispatchRouteDetail['status']==='pending'" class="row mt-3">
                    <div class="col-lg-12 text-right">
                        <button v-if="enableDispatchButton" @click="openTheConfirmationDialogBox"
                                class="btn btn-success">Dispatch
                        </button>
                        <button v-else
                                class="btn btn-success disabled">Dispatch
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="snackBar">
                <snack-bar-message :success="snackBarSuccess" :message="snackBarMessage"></snack-bar-message>
            </div>
            <div v-if="confirmBox">
                <confirmation-box :message="dialogBoxMessage" v-on:close="closeConfirmationDialogBox"
                                  v-on:confirm="confirm"></confirmation-box>
            </div>
        </div>
    </div>
</template>

<script>
import SnackBarMessage from "../../shared/components/error-flash/SnackBarMessage";
import {registerStoreModule, unregisterStoreModule} from "@shared~helpers/checkStoreState";
import DispatchRoute from "@warehouse~store/dispatch-route/store";
import stringFormatter from "@shared~helpers/textConverter";
import ConfirmationBox from "../../../components/ConfirmationBox";
import Loading from "../../shared/components/Loading";
/* eslint-disable no-undef */
export default {

    name: "DispatchOrderDetailPage",

    components: {
        Loading,
        ConfirmationBox,
        SnackBarMessage,
    },

    data() {
        return {
            dispatchRouteCode: null,
            snackBarSuccess: false,
            snackBarMessage: null,
            snackBar: false,
            dispatchRouteDetail: null,
            orderStatus: {
                'accepted': 'success',
                'finalized': 'info',
                'processing': 'primary',
                'ready_to_dispatch': 'green',
                'dispatched': 'success',
                'pending': 'yellow'
            },
            deliveryStatus: {
                'dispatched': 'success',
                'pending': 'info'
            },
            dispatchOrderStoreCode: null,
            confirmBox: false,
            dialogBoxMessage: null,
            vehicleInformation: {
                vehicle_name: null,
                vehicle_number: null,
                driver_name: null,
                driver_number_primary: null,
                driver_number_secondary: null,
                driver_license_number: null,
            },
            enableDispatchButton: false,
            saveDispatchApi: false,
            invalidPrimaryNumber: false,
            invalidSecondaryNumber: false,
            loading: true,
            dispatchRouteVerificationQuestions: [],
            selectedQuestions: [],
            verifiedQuestion: null,
        }
    },

    created() {

        registerStoreModule('dispatchRoute', DispatchRoute);

        this.dispatchRouteCode = window.location.pathname.split('/')[3];

        this.getDispatchRouteDetailPage();

    },

    watch: {

        vehicleInformation: {

            handler() {

                if (this.vehicleInformation['vehicle_name'] && this.vehicleInformation['vehicle_number'] && this.vehicleInformation['driver_name'] && this.vehicleInformation['driver_number_primary'] && this.vehicleInformation['driver_license_number'] && this.checkIfAllQuestionsAreSelected) {

                    this.enableDispatchButton = true;

                } else {

                    this.enableDispatchButton = false;

                }

            },

            deep: true,

            immediate: true

        },

        selectedQuestions: {

            handler() {

                if (this.vehicleInformation['vehicle_name'] && this.vehicleInformation['vehicle_number'] && this.vehicleInformation['driver_name'] && this.vehicleInformation['driver_number_primary'] && this.vehicleInformation['driver_license_number'] && this.checkIfAllQuestionsAreSelected) {

                    this.enableDispatchButton = true;

                } else {

                    this.enableDispatchButton = false;

                }

            },

            deep: true,

            immediate: true

        },

    },

    beforeRouteLeave(to, from, next) {

        unregisterStoreModule('dispatchRoute');

        next();

    },

    computed: {

        dispatchRouteInfo() {

            return this.dispatchRouteDetail;

        },

        checkIfAllQuestionsAreSelected() {

            if (this.selectedQuestions.length === this.dispatchRouteVerificationQuestions.length || this.dispatchRouteVerificationQuestions.length === 0) {

                return true;

            } else {

                return false;

            }

        }

    },

    methods: {

        checkForPrimaryNumberValidation(primaryNumber) {

            console.log(primaryNumber, 'primary phone no.');

            console.log(parseInt(primaryNumber));

            console.log(primaryNumber.length);

            if (primaryNumber.length === 10) {

                this.invalidPrimaryNumber = false;

            } else {

                this.invalidPrimaryNumber = true;

            }

        },

        checkForSecondaryNumberValidation(secondaryNumber) {

            console.log(secondaryNumber, 'primary phone no.');

            console.log(parseInt(secondaryNumber));

            console.log(secondaryNumber.length);

            if (secondaryNumber.length === 10) {

                this.invalidSecondaryNumber = false;

            } else {

                this.invalidSecondaryNumber = true;

            }

        },

        //Api to get the dispatch route detail.
        getDispatchRouteDetailPage() {

            this.$store.dispatch("dispatchRoute/getTheDispatchRouteDetails", this.dispatchRouteCode).then(response => {

                console.log(response.data, 'response');

                this.dispatchRouteDetail = response.data;

                if (response.data.verification_question) {

                    this.verifiedQuestion = Object.keys(response.data.verification_question);

                }

                if (this.dispatchRouteDetail['status'] !== 'dispatched') {

                    this.getVerificationQuestions();

                } else {

                    this.loading = false;

                }

            }).catch(e => {

                this.snackBarMessage = e.response.data.message;

                this.snackBarSuccess = false;

                this.snackBar = true;

                this.loading = false;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            })

        },

        //Make the first letter of the word capital.
        formatToTitleCase(name) {
            return stringFormatter.convertToTitleCase(name);
        },

        //Function to save the store orders for dispatch that are clicked.
        saveTheStoreOrdersForTheDispatch(store) {

            console.log(store, 'store');

            let ordersThatAreSelectedForTheDispatch = store['store_orders'].filter(data => data['add_the_order']);

            let fd = new FormData();

            fd.append(`_method`, 'post');

            fd.append(`wh_dispatch_route_store_code`, store['wh_dispatch_route_store_code']);

            if (ordersThatAreSelectedForTheDispatch.length > 0) {

                for (let i = 0; i < ordersThatAreSelectedForTheDispatch.length; i++) {

                    let orderCode = ordersThatAreSelectedForTheDispatch[i]['order_code'];

                    console.log(orderCode, 'order code');

                    fd.append(`order_code[${i}]`, orderCode);

                }

                let payload = {dispatch_route_code: this.dispatchRouteCode, orders: fd};

                this.$store.dispatch("dispatchRoute/saveTheOrdersForDispatch", payload).then(response => {

                    console.log(response.message, 'response');

                    this.snackBarMessage = response.message;

                    this.snackBarSuccess = true;

                    this.snackBar = true;

                    setTimeout(() => {

                        this.snackBar = false;

                    }, 4000);

                    for (let i = 0; i < ordersThatAreSelectedForTheDispatch.length; i++) {

                        ordersThatAreSelectedForTheDispatch[i]['has_been_added'] = 1;

                    }

                }).catch(e => {

                    this.snackBarMessage = e.response.data.message;

                    this.snackBarSuccess = false;

                    this.snackBar = true;

                    setTimeout(() => {

                        this.snackBar = false;

                    }, 4000);

                })

            } else {

                this.snackBarMessage = 'Please Select the order to save';

                this.snackBarSuccess = false;

                this.snackBar = true;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            }

        },

        //Function to not dispatch the selected order.
        doNotDispatchSelectedOrder(order) {

            console.log(order, order['wh_dispatch_route_store_order_code']);

            let fd = new FormData();

            fd.append(`dispatch_route_store_order_code[0]`, order['wh_dispatch_route_store_order_code']);

            fd.append(`_method`, 'delete');

            let payload = {dispatch_route_code: this.dispatchRouteCode, orders: fd};

            this.$store.dispatch("dispatchRoute/removeTheOrderFromTheDispatchList", payload).then(response => {

                console.log(response.message, 'response');

                this.snackBarMessage = response.message;

                this.snackBarSuccess = true;

                this.snackBar = true;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

                order['add_the_order'] = 0;

                order['has_been_added'] = 0;

            }).catch(e => {

                this.snackBarMessage = e.response.data.message;

                this.snackBarSuccess = false;

                this.snackBar = true;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            })

        },

        //Function to open the confirm dialog box.
        confirmDialogBoxOpen(dispatchOrderStoreCode) {

            console.log(dispatchOrderStoreCode, 'store order code');

            this.dialogBoxMessage = 'remove the store from the dispatch route';

            this.confirmBox = true;

            this.dispatchOrderStoreCode = dispatchOrderStoreCode;

            this.saveDispatchApi = false;

        },

        //Function to open confirm dialog box to save dispatch.
        openTheConfirmationDialogBox() {

            this.dialogBoxMessage = 'save the changes.You cannot add other orders to this dispatch route again. Please check your changes';

            this.confirmBox = true;

            this.saveDispatchApi = true;

        },

        //Function to close the dialog box.
        closeConfirmationDialogBox() {

            this.confirmBox = false;

        },

        //Function to hit api according to the action.
        confirm() {

            if (!this.saveDispatchApi) {

                let fd = new FormData();

                fd.append(`dispatch_route_store_code[0]`, this.dispatchOrderStoreCode);

                fd.append(`_method`, 'delete');

                let payload = {dispatch_route_code: this.dispatchRouteCode, stores: fd};

                this.$store.dispatch("dispatchRoute/removeTheStoreFromTheDispatchRoute", payload).then(response => {

                    console.log(response.message, 'response');

                    let removeTheStoreFromTheDispatchRoutesIndex = this.dispatchRouteDetail['associated_stores'].findIndex(data => data['wh_dispatch_route_store_code'] === this.dispatchOrderStoreCode);

                    this.dispatchRouteDetail['associated_stores'].splice(removeTheStoreFromTheDispatchRoutesIndex, 1);

                    this.confirmBox = false;

                    this.snackBarMessage = response.message;

                    this.snackBarSuccess = true;

                    this.snackBar = true;

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

                })

            } else {

                let fd = new FormData();

                fd.append('_method', 'put');

                fd.append('route_name', this.dispatchRouteDetail['dispatch_route_name']);

                fd.append('vehicle_name', this.vehicleInformation['vehicle_name']);

                fd.append('vehicle_number', this.vehicleInformation['vehicle_number']);

                fd.append('driver_name', this.vehicleInformation['driver_name']);

                fd.append('driver_contact_primary', this.vehicleInformation['driver_number_primary']);

                fd.append('driver_license_number', this.vehicleInformation['driver_license_number']);

                if (this.vehicleInformation['driver_number_secondary']) {

                    fd.append('driver_contact_secondary', this.vehicleInformation['driver_number_secondary']);

                } else {

                    fd.append('driver_contact_secondary', '');

                }

                if (this.dispatchRouteVerificationQuestions.length > 0) {

                    for (let i = 0; i < this.dispatchRouteVerificationQuestions.length; i++) {

                        fd.append(`avq_code[${i}]`, this.dispatchRouteVerificationQuestions[i]['avq_code']);

                        if (this.dispatchRouteVerificationQuestions[i]['is_checked']) {

                            fd.append(`answers[${i}]`, 1);

                        } else {

                            fd.append(`answers[${i}]`, 0);

                        }

                    }

                }

                console.log('save dispatch ko api hann');

                let payload = {dispatch_route_code: this.dispatchRouteCode, dispatchInfo: fd};

                this.$store.dispatch("dispatchRoute/saveTheDispatch", payload).then(response => {

                    console.log(response.message, 'response');

                    this.dispatchRouteDetail['status'] = 'dispatched';

                    this.dispatchRouteDetail['vehicle_name'] = this.vehicleInformation['vehicle_name'];

                    this.dispatchRouteDetail['vehicle_number'] = this.vehicleInformation['vehicle_number'];

                    this.dispatchRouteDetail['driver_name'] = this.vehicleInformation['driver_name'];

                    this.dispatchRouteDetail['driver_contact_primary'] = this.vehicleInformation['driver_number_primary'];

                    this.dispatchRouteDetail['driver_contact_secondary'] = this.vehicleInformation['driver_number_secondary'];

                    this.dispatchRouteDetail['driver_license_number'] = this.vehicleInformation['driver_license_number'];

                    this.confirmBox = false;

                    this.snackBarMessage = response.message;

                    this.snackBarSuccess = true;

                    this.snackBar = true;

                    window.location.reload();

                    setTimeout(() => {

                        this.snackBar = false;

                    }, 4000);

                }).catch(e => {

                    this.confirmBox = false;

                    this.snackBarMessage = e.response.data.message;

                    this.snackBarSuccess = false;

                    this.snackBar = true;

                    setTimeout(() => {

                        this.snackBar = false;

                    }, 4000);

                })

            }

        },

        //Function to get the list of verification question.
        getVerificationQuestions() {

            this.$store.dispatch("dispatchRoute/getVerificationQuestionList").then(response => {

                console.log(response.data, 'response');

                // this.dispatchRouteDetail = response.data;

                this.dispatchRouteVerificationQuestions = response.data;

                this.loading = false;

            }).catch(e => {

                this.snackBarMessage = e.response.data.message;

                this.snackBarSuccess = false;

                this.snackBar = true;

                this.loading = false;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            })

        },

        selectVerificationQuestions(question) {

            if (!question['is_checked']) {

                let selectedIndexCode = this.selectedQuestions.findIndex(data => data['avq_code'] === question['avq_code']);

                this.selectedQuestions.splice(selectedIndexCode, 1);

            } else {

                this.selectedQuestions.push(question);

            }

            console.log(question, 'selected question', this.selectedQuestions);

        }

    }

}
</script>

<style scoped>

</style>
