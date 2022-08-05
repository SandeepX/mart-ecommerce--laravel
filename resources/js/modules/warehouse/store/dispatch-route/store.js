import Service from "@warehouse~services/dispatch-route/service";
import {getField, updateField} from "vuex-map-fields";

const state = {
    warehouse_location: null,
    color: ['red', 'green', 'purple', 'blue', 'pink', 'black'],
};

const mutations = {

    updateField,

    COMMIT_MESSAGE() {
    },

    WAREHOUSE_LOCATION(state, payload) {
        state.warehouse_location = payload;
    }

};

const getters = {

    getField,
};

const actions = {

    getTheListOfAssociatedStoreThatHasOrdersForTheWarehouse({commit}) {
        return new Promise((resolve, reject) => {
            return Service.getListOfStoresThatHasOrder().then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    getTheDispatchRouteDetails({commit}, dispatchOrderCode) {
        return new Promise((resolve, reject) => {
            return Service.getDispatchRouteDetail(dispatchOrderCode).then(res => {
                res.data.data.associated_stores.map(store => {
                    store['store_orders'].map(order => {
                        order['add_the_order'] = 0;
                        if (res.data.data.status === 'dispatched') {
                            order['status'] = 'dispatched';
                            order['has_been_added'] = 1;
                        }
                    })
                })
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    getListOfDispatchRoutes({commit}) {
        return new Promise((resolve, reject) => {
            return Service.getDispatchRouteLists().then(res => {

                if (res.data.data.length > 0) {

                    res.data.data.map((dispatchRoute, index) => {

                        dispatchRoute['has_dispatch_route_updated'] = 1;

                        dispatchRoute['route_color'] = state.color[index];

                        dispatchRoute['swap_mode_enabled'] = 0;

                        dispatchRoute['set_pin_points'] = 0;

                        dispatchRoute['set_dispatch_route_name'] = 0;

                        dispatchRoute['can_set_pin_point'] = 0;

                        if (dispatchRoute['associated_stores'].length > 0) {

                            dispatchRoute['associated_stores'].map(store => {

                                store['show_default_google_map'] = 0;

                                if (dispatchRoute['status'] === 'pending') {

                                    store['has_store_added'] = 1;

                                } else {

                                    store['has_store_added'] = 0;

                                }

                                store['dispatch_route_code'] = dispatchRoute['dispatch_route_code']

                            })

                        }

                    });
                }
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    getDispatchRouteList({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.getListOfDispatchRoutesWithFilter(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    getVerificationQuestionList({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.getListOfVerificationQuestions(payload).then(res => {
                console.log(res.data, 'questions');
                res.data.data.map(question => {
                    question['is_checked'] = false;
                })
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    saveTheDispatchRoutesAlongWithAssociatedStores({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.saveDispatchRoutes(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    removeTheStoreFromTheDispatchRouteList({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.removeTheStoreFromTheDispatchedRoute(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    saveThePinPointsForTheSelectedDispatchRoutes({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.savePinPointsBetweenDispatchRoutes(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    sortTheOrderOfTheDispatchRoute({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.sortTheDispatchRouteStores(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    saveTheMassStoreUpdateToTheDispatchRoute({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.saveMassStoreToDispatchRoute(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    saveTheOrdersForDispatch({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.saveTheOrdersListForDispatch(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    removeTheOrderFromTheDispatchList({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.removeFromDispatchList(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    removeTheStoreFromTheDispatchRoute({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.removeTheStore(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    deleteDispatchRoute({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.deleteDispatchList(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    saveTheDispatch({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.saveTheDispatchRoute(payload).then(res => {
                resolve(res.data);
                commit('COMMIT_MESSAGE')
            }).catch(err => {
                reject(err);
            });
        });
    },

    setTheDispatchRouteName({commit}, payload) {
        return new Promise((resolve, reject) => {
            return Service.setTheDispatchName(payload).then(res => {
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
