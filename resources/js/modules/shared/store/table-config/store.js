import Service from "@warehouse~services/purchase-order/service";
import {getField, updateField} from "vuex-map-fields";

const state = {
    table_meta_information: null,
    tableConfig: {
        page: 1,
        records_per_page: 10,
        search: '',
        vendor_code: null,
        order_source: null,
        payment_type: null,
        status: null,
        warehouse_purchase_order_code: null,
        order_date: null,
    }
};

const mutations = {

    updateField,

    COMMIT_MESSAGE() {
    },

    SET_META_FOR_TABLE_CONFIGURATION(state, payload) {
        state.table_meta_information = payload;
    },

    SET_RECORDS_PER_PAGE(state, payload) {
        state.table_meta_information['per_page'] = payload;
        state.tableConfig['records_per_page'] = payload;
    },

    SET_CURRENT_PAGE_NO(state, payload) {
        state.table_meta_information['current_page'] = payload;
        state.tableConfig['page'] = payload;
    },

    SET_VENDOR_NAME(state, payload) {
        state.tableConfig['vendor_code'] = payload;
    },

    SET_PAYMENT_TYPE(state, payload) {
        state.tableConfig['payment_type'] = payload;
    },

    SET_PAYMENT_STATUS(state, payload) {
        state.tableConfig['status'] = payload;
    },

    SET_ORDER_SOURCE(state, payload) {
        state.tableConfig['order_source'] = payload;
    },

    SET_PURCHASE_ORDER_CODE(state, payload) {
        state.tableConfig['warehouse_purchase_order_code'] = payload;
    },

    SET_ORDER_DATE(state, payload) {
        state.tableConfig['order_date'] = payload;
    },

};

const getters = {

    getField,

    GET_META_INFORMATION(state) {
        return state.table_meta_information;
    },

    GET_TABLE_CONFIGURATION(state) {
        return state.tableConfig;
    }

};

const actions = {

    //Create Purchase Order
    filterListOfVendorBrandsAndCategory({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.getListOfFilteredDataToSelectProduct(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
