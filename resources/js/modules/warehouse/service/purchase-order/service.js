import Api from "@/Api.js";

//Create Purchase Order.

function getListOfFilteredDataToSelectProduct() {

    return Api.get('/warehouse/warehouse-purchase-orders/create');
}

function getDetailsOfProductWithSelectedPackageAndVariant(payload) {
    let url = '/warehouse/warehouse-purchase-orders/filter-vendor-products/' + payload['product_code'] + '/show';
    return Api.getResponseWithParams(url, payload.params, {});
}

function loadMoreProductsForSelection(payload) {
    let url = '/warehouse/warehouse-purchase-orders/filter-vendor-products';
    return Api.getResponseWithParams(url, payload.params, {});
}

function savePurchaseOrder(payload) {
    let url = '/warehouse/warehouse-purchase-orders/store';
    return Api.post(url, payload, {});
}


//List of Purchase Order
function getListOfAllPurchaseOrder(payload) {
    let url = '/warehouse/warehouse-purchase-orders';
    return Api.getResponseWithParams(url, payload, {});
}

function getListOfOptionsForFilter() {
    let url = '/warehouse/warehouse-purchase-orders/filter-parameters';
    return Api.get(url);
}


//Show Purchase Order Detail
function showPurchaseOrderDetail(wh_purchase_order_code) {
    let url = '/warehouse/warehouse-purchase-orders/show/' + wh_purchase_order_code;
    return Api.get(url);
}

function getSchemeProductDetailWhileViewingTheDetail(order_code) {
    let url = '/warehouse/warehouse-purchase-orders/show/' + order_code + '/scheme-detail'
    return Api.get(url);
}


export default {
    getListOfFilteredDataToSelectProduct,
    getDetailsOfProductWithSelectedPackageAndVariant,
    loadMoreProductsForSelection,
    savePurchaseOrder,
    getListOfAllPurchaseOrder,
    getListOfOptionsForFilter,
    showPurchaseOrderDetail,
    getSchemeProductDetailWhileViewingTheDetail
}
