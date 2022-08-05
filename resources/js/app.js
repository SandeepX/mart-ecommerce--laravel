/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import vSelect from 'vue-select';

import store from './store/index';

Vue.component('v-select', vSelect);

import 'vue-select/dist/vue-select.css';

//import BootstrapVue from 'bootstrap-vue';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.use(BootstrapVue);

Vue.component('add-purchase-order', require('./modules/warehouse/pages/AddPurchaseOrder.vue').default);
Vue.component('purchase-order-list', require('./modules/warehouse/pages/PurchaseOrderList.vue').default);
Vue.component('show-order-detail', require('./modules/warehouse/pages/ShowOrderDetail.vue').default);
Vue.component('warehouse-dashboard', require('./modules/warehouse/pages/WarehouseDashboard.vue').default)
Vue.component('dispatch-order-detail-page', require('./modules/warehouse/pages/DispatchOrderDetailPage.vue').default)
Vue.component('dispatch-order-list', require('./modules/warehouse/pages/DispatchRouteList.vue').default)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store
});
