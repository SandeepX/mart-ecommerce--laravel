export default function fetchParamsFromUrl() {

    let currentRoute = window.location.href;

    let routeArray = currentRoute.split("/");

    return routeArray[routeArray.length - 1];


}
