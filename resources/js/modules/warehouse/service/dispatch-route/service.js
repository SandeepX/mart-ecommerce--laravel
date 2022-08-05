import Api from "@/Api.js";

function getListOfStoresThatHasOrder() {

    const url = '/warehouse/dispatchable-stores'
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.get(url, headers);
}

function getDispatchRouteDetail(dispatchOrderCode) {

    const url = `/warehouse/warehouse-dispatch-routes/${dispatchOrderCode}/detail`;
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.get(url, headers);
}

function getDispatchRouteLists() {

    const url = `/warehouse/warehouse-dispatch-routes?status=pending`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.get(url, headers);
}

function getListOfVerificationQuestions() {

    const url = `/verification/entity/orders/action/dispatch_route_verification/questions`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.get(url, headers);
}

function getListOfDispatchRoutesWithFilter(payload) {

    const url = '/warehouse/warehouse-dispatch-routes'
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.getResponseWithParams(url, payload, headers);
}

function saveDispatchRoutes(dispatch_route_detail) {

    const url = '/warehouse/warehouse-dispatch-route/store'
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, dispatch_route_detail, headers);
}

function savePinPointsBetweenDispatchRoutes(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload['dispatch_route_code']}/mass-add-markers`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, payload['pin_points'], headers);
}

function sortTheDispatchRouteStores(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload['dispatch_route_code']}/sort-stores-order`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, payload['store_sort_order'], headers);
}

function saveMassStoreToDispatchRoute(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload['dispatch_route_code']}/mass-add-stores`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, payload['new_added_stores'], headers);
}

function removeTheStoreFromTheDispatchedRoute(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload['dispatch_route_code']}/delete-stores`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, payload['stores'], headers);
}

function saveTheOrdersListForDispatch(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload['dispatch_route_code']}/mass-add-store-orders`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, payload['orders'], headers);
}

function removeFromDispatchList(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload['dispatch_route_code']}/delete-store-orders`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, payload['orders'], headers);
}

function removeTheStore(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload['dispatch_route_code']}/delete-stores`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, payload['stores'], headers);
}

function deleteDispatchList(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload}/delete`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.delete(url, {}, headers);
}

function saveTheDispatchRoute(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload['dispatch_route_code']}/final-update`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, payload['dispatchInfo'], headers);
}

function setTheDispatchName(payload) {

    const url = `/warehouse/warehouse-dispatch-route/${payload['dispatch_route_code']}/minimal-update`
    const headers = {
        'Accept': 'application/json',
        'X-Requested-with': 'XMLHttpRequest',
        'Authorization': 'Basic uRQ4MQ4keRDtdH6UW2aKnUcBHg6N9Illn7K1n3Po'
    }

    return Api.post(url, payload['route_name'], headers);
}


export default {
    getListOfStoresThatHasOrder,
    getDispatchRouteLists,
    saveDispatchRoutes,
    sortTheDispatchRouteStores,
    saveMassStoreToDispatchRoute,
    savePinPointsBetweenDispatchRoutes,
    removeTheStoreFromTheDispatchedRoute,
    getDispatchRouteDetail,
    saveTheOrdersListForDispatch,
    removeFromDispatchList,
    removeTheStore,
    saveTheDispatchRoute,
    deleteDispatchList,
    setTheDispatchName,
    getListOfDispatchRoutesWithFilter,
    getListOfVerificationQuestions
}
