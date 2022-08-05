import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import warehouseVuex from "@warehouse~store/index";
import sharedVuex from "@shared~store/index";

Vue.use(Vuex);

let allVuexStores = Object.assign({}, warehouseVuex.warehouseVuexState, sharedVuex.sharedVuexState);

const allPersistVuexStates = Object.keys(allVuexStores);

const store = new Vuex.Store({
    modules: allVuexStores,
    plugins: [createPersistedState({
        paths: allPersistVuexStates
    })],
});

export default store;
