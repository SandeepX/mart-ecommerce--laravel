import Service from "@warehouse~services/purchase-order/service";
import {getField, updateField} from "vuex-map-fields";

const state = {};

const mutations = {

    updateField,

    COMMIT_MESSAGE() {
    },

};

const getters = {

    getField,
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

    getTheDetailOfProductWithSelectedPackageAndVariant({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.getDetailsOfProductWithSelectedPackageAndVariant(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    loadMoreProductsListAccordingToTheSelectedVendor({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.loadMoreProductsForSelection(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    savePurchaseOrder({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.savePurchaseOrder(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },


    //List Of Purchase Order
    getListOfPurchaseOrder({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.getListOfAllPurchaseOrder(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    //List of Options For Purchase Order Filter
    getOptionsForFilter({commit}) {
        return new Promise((resolve, reject) => {
            return Service.getListOfOptionsForFilter().then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },


    //Show Purchase Order Detail
    getDetailOfSelectedWhPurchaseOrder({commit}, wh_purchase_order_code) {
        return new Promise((resolve, reject) => {
            return Service.showPurchaseOrderDetail(wh_purchase_order_code).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    getSchemeProductDetail({commit}, order_code) {
        return new Promise((resolve, reject) => {
            return Service.getSchemeProductDetailWhileViewingTheDetail(order_code).then(res => {
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
