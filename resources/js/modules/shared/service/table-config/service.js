import Api from "@/Api.js";

//Create Purchase Order.

function getListOfFilteredDataToSelectProduct() {

    return Api.get('/warehouse/warehouse-purchase-orders/create');
}

export default {
    getListOfFilteredDataToSelectProduct,
}
