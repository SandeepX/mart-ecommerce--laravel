<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div id="pac-container">
                    Search For Location : <input style="width: 30%;" id="pac-input" type="text"
                                                 placeholder="Enter a location"/>
                </div>
            </div>
            <div class="col-lg-2"></div>
            <div class="col-lg-4 text-right">
                <div id="location-search">
                    <div class="row">
                        <div class="col-lg-3">Search For Stores:</div>
                        <div class="col-lg-9">
                            <select v-model="selectedStoreToBeSearchInTheMap" @change="searchTheStoreInTheMap"
                                    class="form-control" id="sel1">
                                <option :value="store['store_code']" v-for="(store,index) in allStores" :key="index">
                                    {{ store['store_code'] }} - {{ store['store_name'] }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            id="map"
            class="my-4"
            style=" height: 700px;">
        </div>
        <div class="pa-3 mt-3 pt-0 px-0 pb-3 mb-3 card">
            <div class="row px-0">
                <div style="background-color: #003574" class="col-lg-12 px-0 py-1 mb-4">
                    <div class="row">
                        <div class="col-lg-9">
                            <span class="white--text ml-3 cursor_hover"
                                  @click="addNewDispatchRoute">Add New Route</span>
                        </div>
                        <div v-if="dispatchingRoutesForWarehouse.length>0" class="col-lg-1 white--text">
                            <label class="switch">
                                <input @click="showGoogleDirectionServiceForTheRoute"
                                       v-model="showGoogleDirectionService" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div v-if="dispatchingRoutesForWarehouse.length>0" class="col-lg-2 white--text">
                            Google Default Route
                        </div>
                    </div>
                </div>

                <div class="card-carousel-wrapper row" style="width: 100%">
                    <div class="card-carousel--nav__left" :disabled="currentOffset>=0"
                         @click="moveCarousel(-1)"></div>
                    <div class="card-carousel">
                        <div class="card-carousel--overflow-container">
                            <div class="card-carousel-cards"
                                 :style="{ transform: 'translateX' + '(' + currentOffset + 'px' + ')'}">
                                <div class="mb-4 pr-3 px-3"
                                     v-for="(route,routeIndex) in dispatchingRoutesForWarehouse"
                                     :key="routeIndex">
                                    <div class="caption font-weight-regular card-carousel--card v-card"
                                         style="width: 350px!important;">
                                        <div class="pa-2 background_color_grey row text-uppercase">
                                            <div class="col-lg-8" style="font-size: 0.95rem !important">
                                                <i :style="{color:route['route_color']}" class="fa fa-circle mr-1"
                                                   aria-hidden="true"></i> {{ route['dispatch_route_name'] }}<span
                                                class="label ml-3"
                                                :class="route['status']==='pending'?'label-warning':'label-success'">{{
                                                    formatToTitleCase(route['status'])
                                                }}</span>
                                            </div>
                                            <div
                                                v-if="route['status']==='pending' &&  route['associated_stores'].length>1"
                                                class="col-lg-4 text-right">
                                                <button v-if="!route['set_dispatch_route_name']"
                                                        @click="setRouteNameForTheDispatchRoute(route)"
                                                        class="btn btn-xs btn-info">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>
                                                <button class="cursor_hover btn-xs btn-info"
                                                        @click="route['swap_mode_enabled']=1"
                                                        v-if="!route['swap_mode_enabled']">SORT
                                                </button>
                                                <button class="cursor_hover btn-xs btn-info"
                                                        @click="swapTheStoreCodeAndSaveTheChangeToHitTheApi(route)"
                                                        v-else>DONE
                                                </button>
                                                <button @click="addStoreToSelectedDispatchRoute(routeIndex)"
                                                        class="btn btn-xs btn-info">+ Store
                                                </button>
                                            </div>
                                            <div v-else-if="route['status']==='pending'"
                                                 class="col-lg-4 text-right">
                                                <i
                                                    v-if="route['has_dispatch_route_updated']"
                                                    @click="openDialogBoxToDeleteDispatchRoute(routeIndex,route['dispatch_route_code'],route)"
                                                    style="color: red !important;"
                                                    class="fa fa-trash cursor_hover"
                                                    aria-hidden="true"></i>
                                                <button @click="addStoreToSelectedDispatchRoute(routeIndex)"
                                                        class="btn btn-xs btn-info">+ Store
                                                </button>
                                            </div>
                                            <div v-if="route['set_dispatch_route_name']" class="col-lg-12 mt-2">
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <input v-model="dispatchRouteChangedName"
                                                               @input="checkForDispatchRouteName"
                                                               class="form-control"
                                                               type="text">
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <button @click="saveTheDispatchRouteName(route)"
                                                                class="btn btn-xs btn-info"
                                                                :disabled="disableDispatchRouteName">
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="caption pa-2" style="color:green !important;">Note : Warehouse
                                            should always be at the top.
                                        </div>
                                        <div
                                            v-if="route['swap_mode_enabled'] && route['associated_stores'].length>1"
                                            class="row ml-6">
                                            <draggable group="kanban" style="width: 90%;"
                                                       :list="route['associated_stores']"
                                                       v-model="myArray"
                                                       @start="drag=true"
                                                       @end="drag=false">
                                                <transition-group>
                                                    <div class="py-1 px-2 col-lg-12"
                                                         v-for="(store,index) in route['associated_stores']"
                                                         :key="index">
                                                        <div
                                                            class="border_bottom row pa-1">
                                                            <div class="col-lg-9">{{ index + 1 }}. {{
                                                                    store['store_name']
                                                                }}
                                                            </div>
                                                            <div v-if="index" class="col-lg-3">
                                                                <i
                                                                    @click="openDialogBoxToDeleteTheStoreFromTheDispatchRoute(store,routeIndex,route['dispatch_route_code'],route)"
                                                                    style="color: red !important;"
                                                                    class="fa fa-trash cursor_hover"
                                                                    aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </transition-group>
                                            </draggable>
                                        </div>
                                        <div
                                            v-else-if="!route['swap_mode_enabled'] &&  route['associated_stores'].length>1"
                                            class="row ml-6">
                                            <div class="py-1 px-2 col-lg-10"
                                                 v-for="(store,index) in route['associated_stores']" :key="index">
                                                <div class="border_bottom row pa-1">
                                                    <div class="col-lg-9">{{ index + 1 }}. {{
                                                            store['store_name']
                                                        }}
                                                    </div>
                                                    <div v-if="route['status']==='pending'"
                                                         class="col-lg-3 text-right">
                                                        <i v-if="index"
                                                           @click="openDialogBoxToDeleteTheStoreFromTheDispatchRoute(store,routeIndex,route['dispatch_route_code'],route)"
                                                           style="color: red !important;"
                                                           class="fa fa-trash cursor_hover"
                                                           aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if=" route['associated_stores'].length>1"
                                             class="px-5 pr-0 pb-1 py-3 row">
                                            <div v-if="route['status']==='pending'" class="text-left col-lg-4">
                                                <div class="cursor_hover">
                                                    <button class=" btn btn-xs btn-info"
                                                            @click="setIndividualPinPoints(route['dispatch_route_code'],route['route_color'],route)"
                                                            v-if="!route['set_pin_points'] && route['has_dispatch_route_updated']">
                                                        Set
                                                        Pin Points
                                                    </button>
                                                    <button class=" btn btn-xs btn-info"
                                                            @click="savePinPointsForTheSelectedDispatchRoute(route)"
                                                            v-else-if="route['set_pin_points'] && route['has_dispatch_route_updated']">
                                                        Save Pin Points
                                                    </button>
                                                </div>
                                            </div>
                                            <div v-if="route['status']==='pending'" class="text-left col-lg-4">
                                                <button
                                                    @click="openDialogBoxToDeleteDispatchRoute(routeIndex,route['dispatch_route_code'],route)"
                                                    class="btn btn-danger btn-xs">
                                                    Delete Route
                                                </button>
                                            </div>
                                            <div v-if="route['status']==='pending'" class="text-right col-lg-4">
                                                <button class="cursor_hover btn btn-xs btn-info"
                                                        @click="saveTheChangesToAddDispatchRoute(route)">Save
                                                    Changes
                                                </button>
                                            </div>
                                            <div v-if="route['associated_stores'].length>1" class="col-lg-2 mt-3">
                                                <label class="switch">
                                                    <input
                                                        v-model="route['associated_stores'][1]['show_default_google_map']"
                                                        @click="showIndividualDefaultGoogleRoute(routeIndex,route['associated_stores'][1]['show_default_google_map'])"
                                                        type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-lg-6 mt-3">Default Google Route</div>

                                            <button
                                                @click="goToDispatchOrderDetailPage(route['dispatch_route_code'])"
                                                v-if="route['has_dispatch_route_updated'] && route['status']==='pending'"
                                                class="col-lg-4 mt-3 cursor_hover btn btn-xs btn-info">Proceed
                                                Dispatch
                                            </button>
                                            <button
                                                @click="goToDispatchOrderDetailPage(route['dispatch_route_code'])"
                                                v-else-if="route['has_dispatch_route_updated'] && route['status']==='dispatched'"
                                                class="col-lg-4 mt-3 cursor_hover btn btn-xs btn-info">View Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-carousel--nav__right" :disabled="dispatchingRoutesForWarehouse.length<3"
                         @click="moveCarousel(1)"></div>
                </div>
            </div>
        </div>
        <div v-if="snackBar">
            <snack-bar-message :success="snackBarSuccess" :message="snackBarMessage"></snack-bar-message>
        </div>
        <div v-if="openConfirmationDialog">
            <confirmation-box v-on:close="openConfirmationDialog=false" v-on:confirm="confirmAndProceedToHitApi"
                              :message="confirmationBoxMessage"></confirmation-box>
        </div>
    </div>
</template>

<script>
import draggable from "vuedraggable";
import SnackBarMessage from "../../shared/components/error-flash/SnackBarMessage";
import stringFormatter from "@shared~helpers/textConverter";
import {registerStoreModule, unregisterStoreModule} from "@shared~helpers/checkStoreState";
import DispatchRoute from "@warehouse~store/dispatch-route/store";
import ConfirmationBox from "../../../components/ConfirmationBox";
/* eslint-disable no-undef */
export default {
    name: "WarehouseDashboard",
    props: ['warehouse'],
    components: {
        ConfirmationBox,
        SnackBarMessage,
        draggable,
    },
    data() {
        return {
            map: null,
            allStores: [],
            warehouseMarker: null,
            allStoresMarker: [],
            source: null,
            polyline: [],
            storesAlongWithWarehouseToCreateDispatchRoute: [],
            dispatchedRoutes: [],
            dispatchRouteIndex: 0,
            color: ['red', 'green', 'purple', 'blue', 'pink', 'black'],
            snackBar: false,
            snackBarMessage: null,
            snackBarSuccess: false,
            myArray: [],
            allPolyline: [],
            placesToCreateDirectionService: [],
            wayPointsForDirectionService: [],
            sourceMarker: [],
            destinationMarker: [],
            wayPointsForTheDirectionService: [],
            showGoogleDirectionService: false,
            dS: null,
            dD: null,
            directionServiceArray: [],
            directionRendererArray: [],
            pinpointsMarkerBetweenTheDispatchRoutes: [],
            pinPointPolyline: [],
            selectedDispatchRouteCode: null,
            selectedDispatchRouteColor: null,
            showIndividualGoogleRoutes: false,
            addNewRoute: false,
            isAnyMarkerClicked: false,
            addNewDispatch: false,
            dispatchRouteChangedName: null,
            showDispatchRouteNameChangedField: false,
            totalPages: [],
            canSetPinPointForDispatchRoute: 0,
            confirmationBoxMessage: null,
            openConfirmationDialog: false,
            selectedStore: null,
            selectedDispatchOrderIndex: null,
            selectedDispatchRouteCodeForDeleting: null,
            selectedDispatchRoute: null,
            removeStoreFromTheDispatchRoute: false,
            removeDispatchRoute: false,
            currentOffset: 0,
            windowSize: 1,
            paginationFactor: 100,
            items: [
                {name: 'Kin Khao', tag: ["Thai"]},
                {name: 'Jū-Ni', tag: ["Sushi", "Japanese", "$$$$"]},
                {name: 'Delfina', tag: ["Pizza", "Casual"]},
                {name: 'San Tung', tag: ["Chinese", "$$"]},
                {name: 'Anchor Oyster Bar', tag: ["Seafood", "Cioppino"]},
                {name: 'Locanda', tag: ["Italian"]},
                {name: 'Garden Creamery', tag: ["Ice cream"]},
            ],
            disableDispatchRouteName: false,
            selectedStoreToBeSearchInTheMap: null,
        }
    },

    watch: {

        dispatchRouteChangedName: {

            handler() {

                this.checkForDispatchRouteName();

            },

            immediate: true

        }

    },

    created() {

        registerStoreModule('dispatchRoute', DispatchRoute);

    },

    beforeRouteLeave(to, from, next) {

        unregisterStoreModule('dispatchRoute');

        next();

    },

    mounted() {

        this.showPositionOfCurrentUserOnTheMap();

        this.getDispatchRouteList();

        this.getAllStoresLocation();


    },

    computed: {

        dispatchingRoutesForWarehouse() {

            return this.dispatchedRoutes;

        },

        atEndOfList() {

            return this.currentOffset <= (this.paginationFactor * -1) * (this.items.length - this.windowSize);
        },

        atHeadOfList() {

            return this.currentOffset === 0;

        },

    },

    methods: {

        checkForDispatchRouteName() {

            if (this.dispatchRouteChangedName === null || this.dispatchRouteChangedName === '') {

                this.disableDispatchRouteName = true;

            } else {

                this.disableDispatchRouteName = false;

            }

        },

        moveCarousel(direction) {

            // Find a more elegant way to express the :style. consider using props to make it truly generic
            if (direction === 1) {

                this.currentOffset -= this.paginationFactor;

            } else if (direction === -1 && this.currentOffset < 0) {

                this.currentOffset += this.paginationFactor;

            }

        },

        //Make the first letter of the word capital.
        formatToTitleCase(name) {
            return stringFormatter.convertToTitleCase(name);
        },

        //Function to redirect to the dispatch order detail page.
        goToDispatchOrderDetailPage(dispatchOrderCode) {

            location.href = `${window.location.origin}/warehouse/warehouse-dispatch-routes/${dispatchOrderCode}/show`;

        },

        //Setting the polyline.
        setPolyline(strokeColor, strokeWeight, strokeOpacity, source, destination, dispatchRouteCode, markerDetail) {

            // Define a symbol using SVG path notation, with an opacity of 1.
            const lineSymbol = {
                path: "M 0,-1 0,1",
                strokeOpacity,
                scale: 1,
            };

            this.polyline = new google.maps.Polyline({
                path: [source, destination],
                strokeColor,
                strokeWeight,
                strokeOpacity,
                markerDetail,
                map: this.map,
                icons: [
                    {
                        icon: lineSymbol,
                        offset: "0",
                        repeat: "20px",
                    },
                ],
                dispatched_route_code: dispatchRouteCode
            });

            this.allPolyline.push(this.polyline);

            console.log('creating polyline');

        },

        //Function to search for the store in the map.
        searchTheStoreInTheMap() {

            let selectedValue = document.getElementById('sel1');

            console.log(selectedValue.value);

            let selectedStore = this.allStores.find(data => data['store_code'] === selectedValue.value);

            this.map.setCenter({lat: selectedStore['store_latitude'], lng: selectedStore['store_longitude']});

            this.map.setZoom(18);

        },

        //Function to show the current location of the user on the map.
        showPositionOfCurrentUserOnTheMap() {

            let googleMap = document.getElementById("map");

            this.source = {lat: this.warehouse.latitude, lng: this.warehouse.longitude};

            this.$store.commit("dispatchRoute/WAREHOUSE_LOCATION", this.source);

            this.map = new google.maps.Map(googleMap, {
                center: this.source,
                zoom: 12,
            });

            this.warehouseMarker = new google.maps.Marker({
                position: this.source,
                map: this.map,
                marker_detail: {
                    store_address: '',
                    store_code: 1212121212,
                    lat: this.source.lat,
                    lng: this.source.lng,
                    store_latitude: this.source.lat,
                    store_location_code: 12121212121,
                    store_logo: '',
                    store_longitude: this.source.lng,
                    store_name: 'Warehouse',
                },
                animation: google.maps.Animation.DROP,
                title: "Warehouse",
                icon: "https://img.icons8.com/color/50/000000/region-code.png"

            });

            this.dispatchedRoutes.push({
                dispatch_route_code: 'WDRC11',
                dispatch_route_name: 'Warehouse - Unknown',
                has_dispatch_route_updated: 0,
                set_pin_points: 0,
                status: 'pending',
                can_set_pin_point: 0,
                swap_mode_enabled: 0,
                set_dispatch_route_name: 0,
                route_color: this.color[0],
                associated_stores: [
                    {
                        dispatch_route_code: 'WDRC1',
                        dispatch_route_store_code: 'WDRSC2',
                        has_store_added: 1,
                        lat: 27.96502959999999,
                        lng: 84.2518759,
                        show_default_google_map: 0,
                        sort_order: 3,
                        total_orders: 0,
                        total_amount: 0,
                        store_address: 'Kapan Warehouse',
                        store_code: 'SC1',
                        store_latitude: this.$store.state.dispatchRoute.warehouse_location.lat,
                        store_logo: '',
                        store_longitude: this.$store.state.dispatchRoute.warehouse_location.lng,
                        store_name: 'Warehouse',

                    }]
            });

            this.dispatchRouteIndex = this.dispatchedRoutes.length - 1;

            this.storesAlongWithWarehouseToCreateDispatchRoute.push({
                store_address: '',
                store_code: 1212121212,
                store_latitude: this.source.lat,
                store_location_code: 12121212121,
                store_logo: '',
                store_longitude: this.source.lng,
                has_store_added: 1,
                store_name: 'Warehouse',
            });

            const input = document.getElementById("pac-input");

            const autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.bindTo("bounds", this.map);

            autocomplete.addListener("place_changed", () => {

                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                this.map.setCenter(place.geometry.location);
                this.map.setZoom(17);
            });


        },

        //Api to get all the store location to be selected.
        getAllStoresLocation() {

            this.$store.dispatch("dispatchRoute/getTheListOfAssociatedStoreThatHasOrdersForTheWarehouse").then(response => {

                response.data.map(data => {
                    data['show_default_google_map'] = false;
                    data['has_store_added'] = 0;
                });

                this.allStores = response.data;

                for (let i = 0; i < this.allStores.length; i++) {

                    this.createMarker(this.allStores[i], 0, null, 1);

                }

            }).catch(e => {

                console.log(e.response);

                alert(e);

            })

        },

        //Function to create the markers for all the stores.
        createMarker(storeDetail, isStoreConnected, dispatchRouteCode, isNewDispatchRoute) {

            let storeLocation = {lat: storeDetail['store_latitude'], lng: storeDetail['store_longitude']};

            let marker = new google.maps.Marker({
                position: storeLocation,
                map: this.map,
                marker_detail: storeDetail,
                animation: google.maps.Animation.DROP,
                icon: "https://img.icons8.com/doodle/35/000000/user-location.png"

            });

            marker['is_connected_store'] = isStoreConnected;

            marker['dispatched_route_code'] = dispatchRouteCode;

            this.createInfoWindowForShowingStoreDetail(storeDetail, marker);

            if (dispatchRouteCode) {

                this.preLoadTheDispatchRoutesPolyline(marker, isNewDispatchRoute);

            } else {

                this.setDispatchRouteWhenStoreMarkerIsClicked(marker, isNewDispatchRoute);

            }

            this.allStoresMarker.push(marker);

        },

        //Add polyline to set the dispatch routes.
        setDispatchRouteWhenStoreMarkerIsClicked(marker, isNewDispatchRoute) {

            marker.addListener('click', () => {

                if (this.addNewDispatch) {

                    this.isAnyMarkerClicked = true;

                    if (!marker['is_connected_store']) {

                        this.setPolylineForTheAssociatedStores(marker, isNewDispatchRoute);

                    } else {

                        this.snackBarSuccess = false;

                        this.snackBarMessage = 'Store is already associated with anther dispatch route. Please remove the store for the dispatch route to add the store.'

                        this.snackBar = true;

                        setTimeout(() => {

                            this.snackBar = false;

                        }, 4000);

                    }

                } else {

                    this.isAnyMarkerClicked = false;

                    alert('Please click the Add New Route Button or add store to individual dispatch routes');

                }

            });

        },

        //Function to set polyline for the associated stores.
        setPolylineForTheAssociatedStores(marker, isNewDispatchRoute) {

            marker['marker_detail']['lat'] = marker['marker_detail']['store_latitude'];

            marker['marker_detail']['lng'] = marker['marker_detail']['store_longitude'];

            marker['is_connected_store'] = 1;

            marker['dispatched_route_code'] = this.dispatchedRoutes[this.dispatchRouteIndex]['dispatch_route_code'];

            //Static way to save all your stores in one array.
            this.storesAlongWithWarehouseToCreateDispatchRoute.push(marker.marker_detail);

            console.log(this.storesAlongWithWarehouseToCreateDispatchRoute, 'heeloo');

            this.dispatchedRoutes[this.dispatchRouteIndex]['associated_stores'] = this.storesAlongWithWarehouseToCreateDispatchRoute;

            if (marker['marker_detail']['store_name'] === 'Warehouse') {

                marker.setVisible(false);

            }

            if (this.dispatchedRoutes[this.dispatchRouteIndex]['associated_stores'].length > 1) {

                console.log('store ko length more than 1 xa');

                let destination = {
                    lat: marker['marker_detail']['store_latitude'],
                    lng: marker['marker_detail']['store_longitude'],
                    store_name: marker['marker_detail']['store_name'],
                };

                let source = {
                    lat: this.dispatchedRoutes[this.dispatchRouteIndex]['associated_stores'][this.dispatchedRoutes[this.dispatchRouteIndex]['associated_stores'].length - 2]['store_latitude'],
                    lng: this.dispatchedRoutes[this.dispatchRouteIndex]['associated_stores'][this.dispatchedRoutes[this.dispatchRouteIndex]['associated_stores'].length - 2]['store_longitude'],
                    store_name: this.dispatchedRoutes[this.dispatchRouteIndex]['associated_stores'][this.dispatchedRoutes[this.dispatchRouteIndex]['associated_stores'].length - 2]['store_name'],
                };

                if (this.dispatchedRoutes[this.dispatchRouteIndex]['status'] === 'pending') {

                    this.setPolyline(this.dispatchedRoutes[this.dispatchRouteIndex]['route_color'], 2, 0.4, source, destination, this.dispatchedRoutes[this.dispatchRouteIndex]['dispatch_route_code'], marker['marker_detail']);

                }

            }

        },

        //Function to get the store order and other info details on marker hover.
        createInfoWindowForShowingStoreDetail(storeDetail, marker) {

            // Store Information.
            let contentString = null;

            if (storeDetail['store_address'] && storeDetail['total_orders'] > 0) {

                contentString = '<div id="content">' +
                    '<div id="siteNotice">' +
                    "</div>" +
                    ` <h3 id="firstHeading" class="firstHeading">${storeDetail['store_name']}</h3>` +
                    '<div id="bodyContent">' +
                    `<img style="margin:10px 0" height="100" width="100%" src="${storeDetail['store_logo']}">` +
                    `<div style="margin-bottom: 5px;">Latitude: ${storeDetail['store_latitude']}<span style="margin-left: 20px;">Longitude: ${storeDetail['store_longitude']}</span></div>` +
                    `<div style="margin-bottom: 5px">Address: ${storeDetail['store_address']}</div>` +
                    `<div style="margin-bottom: 5px">Total Orders: ${storeDetail['total_orders']}</div>` +
                    `<div style="margin-bottom: 5px">Total Amount: Rs.  ${storeDetail['total_amount'].toFixed(2)}</div>` +
                    "</div>" +
                    "</div>"


            } else {

                contentString = '<div id="content">' +
                    '<div id="siteNotice">' +
                    "</div>" +
                    ` <h3 id="firstHeading" class="firstHeading">${storeDetail['store_name']}</h3>` +
                    '<div id="bodyContent">' +
                    `<img style="margin:10px 0" height="100" width="100%" src="${storeDetail['store_logo']}">` +
                    `<div style="margin-bottom: 5px;">Latitude: ${storeDetail['store_latitude']}<span style="margin-left: 20px;">Longitude: ${storeDetail['store_longitude']}</span></div>` +
                    "</div>" +
                    "</div>"


            }

            const infoWindow = new google.maps.InfoWindow({
                content: contentString,
            });

            //Open info window when mouseover.
            google.maps.event.addListener(marker, 'mouseover', function () {

                infoWindow.open({
                    anchor: marker,
                    map: this.map,
                    shouldFocus: false,
                });

            });

            //Close info window when mouseleave.
            google.maps.event.addListener(marker, 'mouseout', function () {

                infoWindow.close();

            });

        },

        //Function to save the selected route.
        addNewDispatchRoute() {

            alert('Please Click the Marker to make the dispatch route now');

            this.addNewDispatch = true;

            this.storesAlongWithWarehouseToCreateDispatchRoute = [];

            this.dispatchedRoutes.push({
                dispatch_route_code: this.getRandom(9),
                dispatch_route_name: 'Warehouse - Unknown',
                swap_mode_enabled: 0,
                set_pin_points: 0,
                status: 'pending',
                can_set_pin_point: 0,
                set_dispatch_route_name: 0,
                has_dispatch_route_updated: 0,
                route_color: this.color[this.dispatchedRoutes.length],
                associated_stores: [
                    {
                        dispatch_route_code: 'WDRC1',
                        dispatch_route_store_code: 'WDRSC2',
                        has_store_added: 1,
                        lat: this.$store.state.dispatchRoute.warehouse_location.lat,
                        lng: this.$store.state.dispatchRoute.warehouse_location.lng,
                        show_default_google_map: 0,
                        sort_order: 3,
                        store_address: 'Kapan Warehouse',
                        store_code: 'SC1',
                        store_latitude: this.$store.state.dispatchRoute.warehouse_location.lat,
                        store_logo: '',
                        total_orders: 0,
                        total_amount: 0,
                        store_longitude: this.$store.state.dispatchRoute.warehouse_location.lng,
                        store_name: 'Warehouse',

                    }]
            });

            this.storesAlongWithWarehouseToCreateDispatchRoute.push({
                store_address: '',
                store_code: 1212121212,
                store_latitude: this.source.lat,
                store_location_code: 12121212121,
                store_logo: '',
                store_longitude: this.source.lng,
                store_name: 'Warehouse',
            });

            this.dispatchRouteIndex = this.dispatchedRoutes.length - 1;

        },

        //Function to set the order and create the route accordingly.
        saveTheOrderingChanges() {

            //remove all the polyline from the map.
            for (let i = 0; i < this.allPolyline.length; i++) {

                this.allPolyline[i].setVisible(false);

            }

            this.allPolyline = [];

            //Set New Polyline for the changed store status.
            for (let i = 0; i < this.dispatchedRoutes.length; i++) {

                for (let j = 0; j < this.dispatchedRoutes[i]['associated_stores'].length; j++) {

                    let destination = {
                        lat: this.dispatchedRoutes[i]['associated_stores'][j + 1]['store_latitude'],
                        lng: this.dispatchedRoutes[i]['associated_stores'][j + 1]['store_longitude'],
                        store_name: this.dispatchedRoutes[i]['associated_stores'][j + 1]['store_name'],
                    };

                    let source = {
                        lat: this.dispatchedRoutes[i]['associated_stores'][j]['store_latitude'],
                        lng: this.dispatchedRoutes[i]['associated_stores'][j]['store_longitude'],
                        store_name: this.dispatchedRoutes[i]['associated_stores'][j]['store_name'],
                    };

                    this.setPolyline(this.dispatchedRoutes[i]['route_color'], 2, 0.4, source, destination, this.dispatchedRoutes[i]['dispatch_route_code'], this.dispatchedRoutes[i]['associated_stores'][j]);

                }

            }

        },

        //function to toggle google route.
        showGoogleDirectionServiceForTheRoute() {

            this.showGoogleDirectionService = !this.showGoogleDirectionService;

            if (this.showGoogleDirectionService) {

                this.proceedToContinueToSetTheDirectionService();

            } else {

                this.resetDirectionService();

            }

        },

        showIndividualDefaultGoogleRoute(index, value) {

            value = !value;

            if (value) {

                this.dS = new google.maps.DirectionsService;

                this.directionServiceArray.push(this.dS);

                this.dD = new google.maps.DirectionsRenderer({
                    map: this.map,
                    polylineOptions: {
                        strokeColor: this.dispatchedRoutes[index]['route_color'],
                        strokeWeight: 1,
                        strokeOpacity: 0.7
                    },
                    suppressMarkers: true

                });

                this.directionRendererArray.push(this.dD);

                let dispatchSource = {
                    dispatch_route_code: this.dispatchedRoutes[index]['dispatch_route_code'],
                    dispatch_route_name: this.dispatchedRoutes[index]['dispatch_route_name'],
                    source: this.dispatchedRoutes[index]['associated_stores'][0],
                    lat: this.dispatchedRoutes[index]['associated_stores'][0]['store_latitude'],
                    lng: this.dispatchedRoutes[index]['associated_stores'][0]['store_longitude'],
                }

                let dispatchDestination = {
                    dispatch_route_code: this.dispatchedRoutes[index]['dispatch_route_code'],
                    dispatch_route_name: this.dispatchedRoutes[index]['dispatch_route_name'],
                    destination: this.dispatchedRoutes[index]['associated_stores'][this.dispatchedRoutes[index]['associated_stores'].length - 1],
                    lat: this.dispatchedRoutes[index]['associated_stores'][this.dispatchedRoutes[index]['associated_stores'].length - 1]['store_latitude'],
                    lng: this.dispatchedRoutes[index]['associated_stores'][this.dispatchedRoutes[index]['associated_stores'].length - 1]['store_longitude'],
                }

                this.sourceMarker.push(dispatchSource);

                this.destinationMarker.push(dispatchDestination);

                let way_points = [...this.dispatchedRoutes[index]['associated_stores']];

                let wayPoints = [];

                way_points.splice(0, 1);

                way_points.splice(way_points.length - 1, 1);

                for (let j = 0; j < way_points.length; j++) {

                    wayPoints.push({
                        location: {
                            lat: way_points[j]['lat'],
                            lng: way_points[j]['lng'],
                            store_info: way_points[j],
                        }, stopover: false
                    });

                }

                let dispatchedInfo = {
                    dispatch_route_code: this.dispatchedRoutes[index]['dispatch_route_code'],
                    dispatch_route_name: this.dispatchedRoutes[index]['dispatch_route_name'],
                    route_color: this.dispatchedRoutes[index]['route_color'],
                    points: wayPoints
                }

                this.wayPointsForTheDirectionService.push(dispatchedInfo);

                let request = {

                    origin: this.sourceMarker[index],

                    destination: this.destinationMarker[index],

                    waypoints: this.wayPointsForTheDirectionService[index]['points'], //an array of waypoints

                    travelMode: google.maps.TravelMode.DRIVING

                };

                this.getDirection(this.dS, request, this.dD);

            } else {

                this.resetDirectionService();

            }

        },

        //Function to set the direction services to the associated dispatch routes.
        proceedToContinueToSetTheDirectionService() {

            this.wayPointsForTheDirectionService = [];

            this.sourceMarker = [];

            this.destinationMarker = [];

            //remove all the polyline from the map.
            // for (let i = 0; i < this.allPolyline.length; i++) {
            //
            //   this.allPolyline[i].setVisible(false);
            //
            // }

            this.allPolyline = [];

            for (let i = 0; i < this.dispatchedRoutes.length; i++) {

                this.dS = new google.maps.DirectionsService;

                this.directionServiceArray.push(this.dS);

                this.dD = new google.maps.DirectionsRenderer({
                    map: this.map,
                    polylineOptions: {
                        strokeColor: this.dispatchedRoutes[i]['route_color'],
                        strokeWeight: 1,
                        strokeOpacity: 0.7
                    },
                    suppressMarkers: true

                });

                this.directionRendererArray.push(this.dD);

                let dispatchSource = {
                    dispatch_route_code: this.dispatchedRoutes[i]['dispatch_route_code'],
                    dispatch_route_name: this.dispatchedRoutes[i]['dispatch_route_name'],
                    source: this.dispatchedRoutes[i]['associated_stores'][0],
                    lat: this.dispatchedRoutes[i]['associated_stores'][0]['store_latitude'],
                    lng: this.dispatchedRoutes[i]['associated_stores'][0]['store_longitude'],
                }

                let dispatchDestination = {
                    dispatch_route_code: this.dispatchedRoutes[i]['dispatch_route_code'],
                    dispatch_route_name: this.dispatchedRoutes[i]['dispatch_route_name'],
                    destination: this.dispatchedRoutes[i]['associated_stores'][this.dispatchedRoutes[i]['associated_stores'].length - 1],
                    lat: this.dispatchedRoutes[i]['associated_stores'][this.dispatchedRoutes[i]['associated_stores'].length - 1]['store_latitude'],
                    lng: this.dispatchedRoutes[i]['associated_stores'][this.dispatchedRoutes[i]['associated_stores'].length - 1]['store_longitude'],
                }

                this.sourceMarker.push(dispatchSource);

                this.destinationMarker.push(dispatchDestination);

                let way_points = [...this.dispatchedRoutes[i]['associated_stores']];

                let wayPoints = [];

                way_points.splice(0, 1);

                way_points.splice(way_points.length - 1, 1);

                for (let j = 0; j < way_points.length; j++) {

                    wayPoints.push({
                        location: {
                            lat: way_points[j]['store_latitude'],
                            lng: way_points[j]['store_longitude'],
                            store_info: way_points[j],
                        }, stopover: false
                    });

                }

                let dispatchedInfo = {
                    dispatch_route_code: this.dispatchedRoutes[i]['dispatch_route_code'],
                    dispatch_route_name: this.dispatchedRoutes[i]['dispatch_route_name'],
                    route_color: this.dispatchedRoutes[i]['route_color'],
                    points: wayPoints
                }

                this.wayPointsForTheDirectionService.push(dispatchedInfo);

                let request = {

                    origin: this.sourceMarker[i],

                    destination: this.destinationMarker[i],

                    waypoints: this.wayPointsForTheDirectionService[i]['points'], //an array of waypoints

                    travelMode: google.maps.TravelMode.DRIVING

                };

                this.getDirection(this.dS, request, this.dD);

            }

        },

        //Set Direction Service.
        getDirection(dS, request, dD) {

            dS.route(request, function (result, status) {

                if (status == google.maps.DirectionsStatus.OK) {

                    dD.setDirections(result);

                } else {
                    console.log(status);
                }

            })

        },

        //Function to remove default google routes.
        resetDirectionService() {

            for (let i = 0; i < this.directionRendererArray.length; i++) {

                this.directionRendererArray[i].set('directions', null);

            }

            this.directionRendererArray = [];

            this.directionServiceArray = [];

        },

        //Open Dialog Box for removing the store.
        openDialogBoxToDeleteTheStoreFromTheDispatchRoute(store, dispatchOrderIndex, dispatchRouteCode, route) {

            this.selectedStore = store;

            this.removeDispatchRoute = false;

            this.selectedDispatchOrderIndex = dispatchOrderIndex;

            this.selectedDispatchRouteCodeForDeleting = dispatchRouteCode;

            this.selectedDispatchRoute = route;

            this.confirmationBoxMessage = 'remove the store from the dispatch route';

            this.removeStoreFromTheDispatchRoute = true;

            this.openConfirmationDialog = true;

        },

        //remove the store from the dispatch route list.
        removeTheStoreFromTheDispatchedRouteList() {

            let fd = new FormData();

            fd.append(`dispatch_route_store_code[0]`, this.selectedStore['dispatch_route_store_code']);

            fd.append(`_method`, 'delete');

            let payload = {dispatch_route_code: this.selectedDispatchRouteCodeForDeleting, stores: fd};

            this.$store.dispatch("dispatchRoute/removeTheStoreFromTheDispatchRouteList", payload).then(response => {

                this.removeStoreFromTheDispatchRoute = false;

                this.snackBarMessage = response.message;

                this.snackBarSuccess = true;

                this.snackBar = true;

                location.reload();

                let storeIndex = null;

                let findStoreMarker = null;

                if (this.selectedDispatchRoute['has_dispatch_route_updated']) {

                    storeIndex = this.dispatchedRoutes[this.selectedDispatchOrderIndex]['associated_stores'].findIndex(data => data['dispatch_route_store_code'] === this.selectedStore['dispatch_route_store_code']);

                    this.dispatchedRoutes[this.selectedDispatchOrderIndex]['associated_stores'].splice(storeIndex, 1);

                    findStoreMarker = this.allStoresMarker.find(data => data['marker_detail']['dispatch_route_store_code'] === this.selectedStore['dispatch_route_store_code']);

                } else {

                    storeIndex = this.dispatchedRoutes[this.selectedDispatchOrderIndex]['associated_stores'].findIndex(data => data['store_code'] === this.selectedStore['store_code']);

                    this.dispatchedRoutes[this.selectedDispatchOrderIndex]['associated_stores'].splice(storeIndex, 1);

                    findStoreMarker = this.allStoresMarker.find(data => data['marker_detail']['store_code'] === this.selectedStore['store_code']);

                }

                findStoreMarker['is_connected_store'] = 0;

                findStoreMarker.setVisible(false);

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

                this.saveTheOrderingChanges(this.selectedDispatchRoute['has_dispatch_route_updated']);

            })

                .catch(e => {

                    this.snackBarMessage = e.response.data.message;

                    this.snackBarSuccess = false;

                    this.snackBar = true;

                    setTimeout(() => {

                        this.snackBar = false;

                    }, 4000);

                })

        },

        //Remove all others stores except the stores associated with the selected dispatched route.
        setPinPointsBetweenTheDispatchedRoute(dispatchRouteCode, dispatchRouteColor, canSetPinPoints) {

            this.selectedDispatchRouteCode = dispatchRouteCode;

            this.selectedDispatchRouteColor = dispatchRouteColor;

            let warehouseLocation = {
                lat: this.warehouseMarker['marker_detail']['lat'],
                lng: this.warehouseMarker['marker_detail']['lng']
            };

            this.map.setCenter(warehouseLocation);

            this.map.setZoom(14);

            let findThePolylineThatAreNotTheSelectedDispatchRoutes = [];

            let findThePinPointMarkerThatAreNotAssociatedWithTheSelectedDispatchRoute = [];

            let findTheStoreMarkersThatAreNotConnectedToTheSelectedDispatchRoute = this.allStoresMarker.filter(data => data['dispatched_route_code'] !== dispatchRouteCode);

            if (findTheStoreMarkersThatAreNotConnectedToTheSelectedDispatchRoute.length > 0) {

                findTheStoreMarkersThatAreNotConnectedToTheSelectedDispatchRoute.map(marker => {

                    marker.setVisible(false);

                })

                findThePolylineThatAreNotTheSelectedDispatchRoutes = this.allPolyline.filter(data => data['dispatched_route_code'] !== dispatchRouteCode);

                findThePolylineThatAreNotTheSelectedDispatchRoutes.map(polyline => {

                    polyline.setVisible(false);

                });

            }

            if (this.dispatchedRoutes.length > 1) {

                if (this.pinpointsMarkerBetweenTheDispatchRoutes.length > 0) {

                    findThePinPointMarkerThatAreNotAssociatedWithTheSelectedDispatchRoute = this.pinpointsMarkerBetweenTheDispatchRoutes.filter(data => data['dispatch_route_code'] !== dispatchRouteCode);

                    findThePinPointMarkerThatAreNotAssociatedWithTheSelectedDispatchRoute.map(point => {

                        point['pin_points'].map(marker => {

                            marker.setVisible(false);

                        })

                    })

                }

                if (this.pinPointPolyline.length > 0) {

                    findThePinPointMarkerThatAreNotAssociatedWithTheSelectedDispatchRoute = this.pinPointPolyline.filter(data => data['dispatch_route_code'] !== dispatchRouteCode);

                    findThePinPointMarkerThatAreNotAssociatedWithTheSelectedDispatchRoute.map(point => {

                        point['pin_points'].map(polyline => {

                            polyline.setVisible(false);

                        })

                    })

                }

            }

            let findThePolylineThatAreSelectedDispatchRoutes = this.allPolyline.filter(data => data['dispatched_route_code'] === dispatchRouteCode);

            this.pinpointsMarkerBetweenTheDispatchRoutes.push({
                dispatch_route_code: dispatchRouteCode,
                pin_points: []
            })

            this.pinPointPolyline.push({
                dispatch_route_code: dispatchRouteCode,
                pin_points: []
            });

            this.createPinPointsMarkers(warehouseLocation, dispatchRouteCode, 0);

            this.createPinPointsMarkers({
                lat: findThePolylineThatAreSelectedDispatchRoutes[findThePolylineThatAreSelectedDispatchRoutes.length - 1]['markerDetail']['store_latitude'],
                lng: findThePolylineThatAreSelectedDispatchRoutes[findThePolylineThatAreSelectedDispatchRoutes.length - 1]['markerDetail']['store_longitude']
            }, dispatchRouteCode, 0);

            google.maps.event.addListener(this.map, "click", (event) => {

                if (this.canSetPinPointForDispatchRoute) {

                    this.createPinPointsMarkers({
                        lat: event.latLng.lat(),
                        lng: event.latLng.lng()
                    }, dispatchRouteCode, 0);

                    this.createPolylineBetweenTheDispatchRouteToSetPinPoints(dispatchRouteCode, 0);

                } else {

                    alert('Please Click Set Pin Points button to add pin points');

                }

            });

        },

        //Function to create pin points markers.
        createPinPointsMarkers(position, dispatchRouteCode, oldPinPoints) {

            let pinPointMarker = null;

            if (oldPinPoints) {

                pinPointMarker = new google.maps.Marker({
                    position,
                    map: this.map,
                    marker_unique_id: position['pin_point_code'],
                    animation: google.maps.Animation.DROP,
                    icon: "https://img.icons8.com/fluency/20/000000/maps.png"

                });

            } else {

                pinPointMarker = new google.maps.Marker({
                    position,
                    map: this.map,
                    marker_unique_id: this.getRandom(9),
                    animation: google.maps.Animation.DROP,
                    icon: "https://img.icons8.com/fluency/20/000000/maps.png"

                });

            }

            let selectedIndexToPushThePinPointForTheSelectedDispatchRoute = this.pinpointsMarkerBetweenTheDispatchRoutes.findIndex(data => data['dispatch_route_code'] === dispatchRouteCode);

            let pinPoints = this.pinpointsMarkerBetweenTheDispatchRoutes[selectedIndexToPushThePinPointForTheSelectedDispatchRoute]['pin_points'];

            if (!oldPinPoints) {

                if (pinPoints.length < 2) {

                    pinPoints.push(pinPointMarker);

                } else {

                    pinPoints.splice(pinPoints.length - 1, 0, pinPointMarker);

                }

                pinPoints[0].setVisible(false);

                pinPoints[pinPoints.length - 1].setVisible(false);

            }

            pinPointMarker.addListener('click', () => {

                let selectedDispatchRouteIndex = this.dispatchedRoutes.findIndex(data => data['dispatch_route_code'] === dispatchRouteCode);

                if (this.dispatchedRoutes[selectedDispatchRouteIndex]['set_pin_points']) {

                    let markerIndex = pinPoints.findIndex(data => data['marker_unique_id'] === pinPointMarker['marker_unique_id']);

                    let markerSetVisible = pinPoints.find(data => data['marker_unique_id'] === pinPointMarker['marker_unique_id']);

                    markerSetVisible.setVisible(false);

                    pinPoints.splice(markerIndex, 1);

                    this.createPolylineBetweenTheDispatchRouteToSetPinPoints(dispatchRouteCode, 0);

                    pinPointMarker.setVisible(false);

                }

            });

            if (oldPinPoints) {

                this.createPolylineBetweenTheDispatchRouteToSetPinPoints(dispatchRouteCode, 1);

            }

        },

        //Hit Api to save the dispatched route list.
        saveTheChangesToAddDispatchRoute(dispatchRoute) {

            if (!dispatchRoute['has_dispatch_route_updated']) {

                let fd = new FormData();

                fd.append('route_name', dispatchRoute['dispatch_route_name']);

                for (let i = 0; i < dispatchRoute['associated_stores'].length; i++) {

                    let store = dispatchRoute['associated_stores'][i]['store_code'];

                    if (i !== 0) {

                        fd.append(`store_code[${i - 1}]`, store);

                    }

                }

                this.$store.dispatch("dispatchRoute/saveTheDispatchRoutesAlongWithAssociatedStores", fd).then(response => {

                    this.snackBarMessage = response.message;

                    this.snackBarSuccess = true;

                    this.snackBar = true;

                    dispatchRoute['dispatch_route_code'] = response.data['dispatch_route']['wh_dispatch_route_code'];

                    dispatchRoute['has_dispatch_route_updated'] = 1;

                    dispatchRoute['associated_stores'].map((store, index) => {

                        store['has_store_added'] = 1;

                        if (index !== 0) {

                            store['dispatch_route_store_code'] = response.data['route_stores'][index - 1]['wh_dispatch_route_store_code']

                            store['dispatch_route_code'] = response.data['dispatch_route']['wh_dispatch_route_code'];

                        } else {

                            store['dispatch_route_store_code'] = this.getRandom(9);

                            store['dispatch_route_code'] = response.data['dispatch_route']['wh_dispatch_route_code'];

                        }

                    })

                    this.allStoresMarker.map(store => {

                        store['dispatched_route_code'] = response.data['dispatch_route']['wh_dispatch_route_code'];

                    });

                    this.allPolyline.map(store => {

                        store['dispatched_route_code'] = response.data['dispatch_route']['wh_dispatch_route_code'];

                    });

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

                let findThoseStoresThatAreNotAddedToTheDispatchedRoute = dispatchRoute['associated_stores'].filter(data => !data['has_store_added']);

                if (findThoseStoresThatAreNotAddedToTheDispatchedRoute.length > 0) {

                    let fd = new FormData();

                    fd.append('route_name', dispatchRoute['dispatch_route_name']);

                    for (let i = 0; i < findThoseStoresThatAreNotAddedToTheDispatchedRoute.length; i++) {

                        if (i !== 0) {

                            let store = findThoseStoresThatAreNotAddedToTheDispatchedRoute[i]['store_code'];

                            fd.append(`store_code[${i - 1}]`, store);

                        }

                    }

                    let payload = {dispatch_route_code: dispatchRoute['dispatch_route_code'], new_added_stores: fd};

                    this.$store.dispatch("dispatchRoute/saveTheMassStoreUpdateToTheDispatchRoute", payload).then(response => {

                        this.snackBarMessage = response.message;

                        this.snackBarSuccess = true;

                        this.snackBar = true;

                        dispatchRoute['has_dispatch_route_updated'] = 1;

                        dispatchRoute['associated_stores'].map((store, index) => {

                            store['has_store_added'] = 1;

                            if (index !== 0) {

                                store['dispatch_route_store_code'] = response.data['route_stores'][index - 1]['wh_dispatch_route_store_code']

                                store['dispatch_route_code'] = dispatchRoute['dispatch_route_code'];

                            } else {

                                store['dispatch_route_store_code'] = this.getRandom(9);

                                store['dispatch_route_code'] = dispatchRoute['dispatch_route_code'];

                            }

                        })

                        this.allStoresMarker.map(store => {

                            store['dispatched_route_code'] = dispatchRoute['dispatch_route_code'];

                        });

                        this.allPolyline.map(store => {

                            store['dispatched_route_code'] = dispatchRoute['dispatch_route_code'];

                        });

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

                    this.snackBarMessage = 'Dispatch Route Saved.';

                    this.snackBarSuccess = true;

                    this.snackBar = true;

                    setTimeout(() => {

                        this.snackBar = false;

                    }, 4000);

                }

            }

        },

        //Function to open the dialog box to delete the dispatch route.
        openDialogBoxToDeleteDispatchRoute(index, dispatchRouteCode) {

            this.selectedDispatchOrderIndex = index;

            this.removeDispatchRoute = true;

            this.removeStoreFromTheDispatchRoute = false;

            this.confirmationBoxMessage = 'remove the dispatch route from the list';

            this.selectedDispatchRouteCodeForDeleting = dispatchRouteCode;

            this.openConfirmationDialog = true;


        },

        //Function to delete the dispatch route.
        removeTheDispatchedRoute() {

            this.$store.dispatch('dispatchRoute/deleteDispatchRoute', this.selectedDispatchRouteCodeForDeleting).then(response => {

                this.snackBarMessage = response.message;

                this.snackBarSuccess = true;

                this.snackBar = true;

                this.removeDispatchRoute = false;

                this.openConfirmationDialog = false;

                this.dispatchedRoutes.splice(this.selectedDispatchOrderIndex, 1);

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

                location.reload();

            }).catch(e => {

                this.snackBarMessage = e.response.data.message;

                this.snackBarSuccess = false;

                this.snackBar = true;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            });

        },

        //Generate Random Sequence of number.
        getRandom(length) {

            return Math.floor(Math.pow(10, length - 1) + Math.random() * 9 * Math.pow(10, length - 1));

        },

        //Function to create polyline for the dispatch route.
        createPolylineBetweenTheDispatchRouteToSetPinPoints(dispatchRouteCode, oldPinPoint) {

            let selectedIndexToPushThePinPointForTheSelectedDispatchRoute = this.pinpointsMarkerBetweenTheDispatchRoutes.findIndex(data => data['dispatch_route_code'] === dispatchRouteCode);

            if (this.pinPointPolyline[selectedIndexToPushThePinPointForTheSelectedDispatchRoute]['pin_points'].length > 0) {

                //remove all the polyline from the map.
                for (let i = 0; i < this.pinPointPolyline[selectedIndexToPushThePinPointForTheSelectedDispatchRoute]['pin_points'].length; i++) {

                    this.pinPointPolyline[selectedIndexToPushThePinPointForTheSelectedDispatchRoute]['pin_points'][i].setVisible(false);

                }

                this.pinPointPolyline[selectedIndexToPushThePinPointForTheSelectedDispatchRoute]['pin_points'] = [];

            }

            let pinPoints = this.pinpointsMarkerBetweenTheDispatchRoutes[selectedIndexToPushThePinPointForTheSelectedDispatchRoute]['pin_points'];

            let polylinePinPoints = this.pinPointPolyline[selectedIndexToPushThePinPointForTheSelectedDispatchRoute]['pin_points'];

            for (let i = 0; i < pinPoints.length; i++) {

                if (pinPoints[i + 1]) {

                    let source = {lat: pinPoints[i].getPosition().lat(), lng: pinPoints[i].getPosition().lng()};

                    let destination = {
                        lat: pinPoints[i + 1].getPosition().lat(),
                        lng: pinPoints[i + 1].getPosition().lng()
                    };

                    this.setPinPointPolyline(source, destination, polylinePinPoints, dispatchRouteCode, this.color[selectedIndexToPushThePinPointForTheSelectedDispatchRoute]);

                }

            }

        },

        //Function to create the polyline between the pin points.
        setPinPointPolyline(source, destination, polyline, dispatchRouteCode, strokeColor) {

            let polylineForPinPoints = new google.maps.Polyline({
                path: [source, destination],
                strokeColor,
                strokeWeight: 2,
                strokeOpacity: 0.7,
                map: this.map,
                dispatched_route_code: dispatchRouteCode
            });

            polyline.push(polylineForPinPoints);

        },

        //Function to save the selected dispatch route pin points and show other markers and polyline.
        saveTheDispatchRouteSelectedPinPoints() {

            let findTheStoreMarkersThatAreNotConnectedToTheSelectedDispatchRoute = this.allStoresMarker.filter(data => data['dispatched_route_code'] !== this.selectedDispatchRouteCode);

            findTheStoreMarkersThatAreNotConnectedToTheSelectedDispatchRoute.map(marker => {

                if (marker['marker_detail']['store_name'] !== 'Warehouse') {

                    marker.setVisible(true);

                }

            })

            let findThePolylineThatAreNotTheSelectedDispatchRoutes = this.allPolyline.filter(data => data['dispatched_route_code'] !== this.selectedDispatchRouteCode);

            findThePolylineThatAreNotTheSelectedDispatchRoutes.map(polyline => {

                polyline.setVisible(true);

            });

            if (this.pinpointsMarkerBetweenTheDispatchRoutes.length > 0) {

                let findThePinPointMarkerThatAreNotAssociatedWithTheSelectedDispatchRoute = this.pinpointsMarkerBetweenTheDispatchRoutes.filter(data => data['dispatch_route_code'] !== this.selectedDispatchRouteCode);

                findThePinPointMarkerThatAreNotAssociatedWithTheSelectedDispatchRoute.map(point => {

                    point['pin_points'].map(marker => {

                        marker.setVisible(true);

                    })

                })

            }

            if (this.pinPointPolyline.length > 0) {

                let findThePinPointMarkerThatAreNotAssociatedWithTheSelectedDispatchRoute = this.pinPointPolyline.filter(data => data['dispatch_route_code'] !== this.selectedDispatchRouteCode);

                findThePinPointMarkerThatAreNotAssociatedWithTheSelectedDispatchRoute.map(point => {

                    point['pin_points'].map(polyline => {

                        polyline.setVisible(true);

                    })

                })

            }

        },

        //Api to save the swap data.
        swapTheStoreCodeAndSaveTheChangeToHitTheApi(dispatchRouteDetail) {

            let fd = new FormData();

            fd.append('_method', 'put');

            for (let i = 0; i < dispatchRouteDetail['associated_stores'].length; i++) {

                let store = dispatchRouteDetail['associated_stores'][i]['dispatch_route_store_code'];

                if (i !== 0) {

                    fd.append(`dispatch_route_store_code[${i - 1}]`, store);

                }

            }

            let payload = {

                dispatch_route_code: dispatchRouteDetail['dispatch_route_code'],

                store_sort_order: fd

            }

            this.$store.dispatch("dispatchRoute/sortTheOrderOfTheDispatchRoute", payload).then(response => {

                this.snackBarMessage = response.message;

                this.snackBarSuccess = true;

                this.snackBar = true;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

                dispatchRouteDetail['swap_mode_enabled'] = 0;

                this.saveTheOrderingChanges(dispatchRouteDetail['has_dispatch_route_updated']);

            }).catch(e => {

                this.snackBarMessage = e.response.data.message;

                this.snackBarSuccess = false;

                this.snackBar = true;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            })

        },

        //Api to get the dispatch route list.
        getDispatchRouteList() {

            this.$store.dispatch("dispatchRoute/getListOfDispatchRoutes").then(response => {

                this.dispatchedRoutes = response.data;

                if (this.dispatchedRoutes.length > 0) {

                    response.data.map(route => {

                        let warehouse = {
                            dispatch_route_code: route['dispatch_route_code'],
                            dispatch_route_store_code: 'WDRSC2',
                            has_store_added: 1,
                            lat: this.$store.state.dispatchRoute.warehouse_location.lat,
                            lng: this.$store.state.dispatchRoute.warehouse_location.lng,
                            show_default_google_map: 0,
                            sort_order: 3,
                            store_address: 'Kapan Warehouse',
                            store_code: 'SC1',
                            store_latitude: this.$store.state.dispatchRoute.warehouse_location.lat,
                            store_logo: '',
                            store_longitude: this.$store.state.dispatchRoute.warehouse_location.lng,
                            store_name: 'Warehouse',

                        }

                        if (route['associated_stores'].length > 0) {

                            route['associated_stores'].splice(0, 0, warehouse);

                        } else {

                            route['associated_stores'].push(warehouse);

                        }

                    })

                    this.showMarkersFromTheDispatchRouteAssociatedStoresAlongWithPolyline();

                    //console.log(this.dispatchedRoutes );
                    this.setCurrentPolylineForTheDispatchRoutes();

                } else {

                    this.storesAlongWithWarehouseToCreateDispatchRoute = [];

                    this.dispatchedRoutes.push({
                        dispatch_route_code: this.getRandom(9),
                        dispatch_route_name: 'Warehouse - Unknown',
                        swap_mode_enabled: 0,
                        set_pin_points: 0,
                        set_dispatch_route_name: 0,
                        can_set_pin_point: 0,
                        status: 'pending',
                        has_dispatch_route_updated: 0,
                        route_color: this.color[this.dispatchedRoutes.length],
                        associated_stores: [
                            {
                                dispatch_route_code: 'WDRC1',
                                dispatch_route_store_code: 'WDRSC2',
                                has_store_added: 1,
                                lat: this.$store.state.dispatchRoute.warehouse_location.lat,
                                lng: this.$store.state.dispatchRoute.warehouse_location.lng,
                                show_default_google_map: 0,
                                sort_order: 3,
                                store_address: 'Kapan Warehouse',
                                store_code: 'SC1',
                                store_latitude: this.$store.state.dispatchRoute.warehouse_location.lat,
                                store_logo: '',
                                total_orders: 0,
                                total_amount: 0,
                                store_longitude: this.$store.state.dispatchRoute.warehouse_location.lng,
                                store_name: 'Warehouse',

                            }]
                    });

                    this.storesAlongWithWarehouseToCreateDispatchRoute.push({
                        store_address: '',
                        store_code: 1212121212,
                        store_latitude: this.source.lat,
                        store_location_code: 12121212121,
                        store_logo: '',
                        store_longitude: this.source.lng,
                        store_name: 'Warehouse',
                    });

                    this.dispatchRouteIndex = this.dispatchedRoutes.length - 1;

                }

            }).catch(e => {

                this.snackBarMessage = e.response.data.message;

                this.snackBarSuccess = false;

                this.snackBar = true;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            })

        },

        //Create markers for already created dispatched routes and polyline.
        showMarkersFromTheDispatchRouteAssociatedStoresAlongWithPolyline() {

            for (let i = 0; i < this.dispatchedRoutes.length; i++) {

                this.dispatchRouteIndexCount = -1;

                for (let j = 0; j < this.dispatchedRoutes[i]['associated_stores'].length; j++) {

                    this.allStores.push(this.dispatchedRoutes[i]['associated_stores'][j]);

                }

                for (let k = 0; k < this.allStores.length; k++) {

                    this.dispatchRouteIndex = this.dispatchedRoutes.findIndex(data => data['dispatch_route_code'] === this.allStores[k]['dispatch_route_code']);

                    console.log(this.allStores[k]['dispatch_route_code'], this.dispatchRouteIndex, 'marker haru create garda ko');

                    let storeConnectedStatus = this.dispatchedRoutes[this.dispatchRouteIndex]['status'];

                    let isStoreConnected = 0;

                    if (storeConnectedStatus === 'pending') {

                        isStoreConnected = 1;

                    } else {

                        isStoreConnected = 0;

                    }

                    this.createMarker(this.allStores[k], isStoreConnected, this.allStores[k]['dispatch_route_code'], 0);

                }

                if (this.dispatchedRoutes[i]['pin_points'].length > 0) {

                    this.pinpointsMarkerBetweenTheDispatchRoutes.push({
                        dispatch_route_code: this.dispatchedRoutes[i]['dispatch_route_code'],
                        pin_points: []
                    });

                    this.pinPointPolyline.push({
                        dispatch_route_code: this.dispatchedRoutes[i]['dispatch_route_code'],
                        pin_points: []
                    });

                    for (let k = 0; k < this.dispatchedRoutes[i]['pin_points'].length; k++) {

                        let pinPointMarker = new google.maps.Marker({
                            position: {
                                lat: this.dispatchedRoutes[i]['pin_points'][k]['latitude'],
                                lng: this.dispatchedRoutes[i]['pin_points'][k]['longitude']
                            },
                            map: this.map,
                            marker_unique_id: this.dispatchedRoutes[i]['pin_points'][k]['wh_dispatch_route_marker_code'],
                            animation: google.maps.Animation.DROP,
                            icon: "https://img.icons8.com/fluency/20/000000/maps.png"

                        });

                        this.pinpointsMarkerBetweenTheDispatchRoutes[i]['pin_points'].push(pinPointMarker);

                        this.createPinPointsMarkers({
                            lat: this.dispatchedRoutes[i]['pin_points'][k]['latitude'],
                            lng: this.dispatchedRoutes[i]['pin_points'][k]['longitude'],
                            pin_point_code: this.dispatchedRoutes[i]['pin_points'][k]['wh_dispatch_route_marker_code']
                        }, this.dispatchedRoutes[i]['dispatch_route_code'], 1);

                    }

                } else {

                    this.pinpointsMarkerBetweenTheDispatchRoutes.push({
                        dispatch_route_code: this.dispatchedRoutes[i]['dispatch_route_code'],
                        pin_points: []
                    });

                    this.pinPointPolyline.push({
                        dispatch_route_code: this.dispatchedRoutes[i]['dispatch_route_code'],
                        pin_points: []
                    });

                }

            }

        },

        //Function to display the polyline for the dispatch routes.
        preLoadTheDispatchRoutesPolyline(marker, isNewDispatchRoute) {

            marker.addListener('click', () => {

                if (this.addNewDispatch) {

                    this.isAnyMarkerClicked = true;

                    if (!marker['is_connected_store']) {

                        console.log('can be connected');

                        this.setPolylineForTheAssociatedStores(marker, isNewDispatchRoute);

                    } else {

                        this.snackBarSuccess = false;

                        this.snackBarMessage = 'Store is already associated with anther dispatch route. Please remove the store for the dispatch route to add the store.'

                        this.snackBar = true;

                        setTimeout(() => {

                            this.snackBar = false;

                        }, 4000);

                    }

                } else {

                    this.isAnyMarkerClicked = false;

                    alert('Please click the Add New Route Button or add store to individual dispatch routes');

                }

            });

        },

        //Function to set the individual pin points that shows only the selected dispatch route stores.
        setIndividualPinPoints(dispatchRouteCode, routeColor, dispatchRoute) {

            dispatchRoute['set_pin_points'] = 1;

            dispatchRoute['can_set_pin_point'] = 1;

            this.canSetPinPointForDispatchRoute = dispatchRoute['can_set_pin_point'];

            this.setPinPointsBetweenTheDispatchedRoute(dispatchRouteCode, routeColor, dispatchRoute['can_set_pin_point']);

        },

        //Function to save individual pin points for selected dispatch route.
        savePinPointsForTheSelectedDispatchRoute(route) {

            this.saveTheDispatchRouteSelectedPinPoints();

            let findTheSelectedDispatchRoutePinPoints = this.pinpointsMarkerBetweenTheDispatchRoutes.find(data => data['dispatch_route_code'] === route['dispatch_route_code']);

            let fd = new FormData();

            for (let i = 0; i < findTheSelectedDispatchRoutePinPoints['pin_points'].length; i++) {

                fd.append(`latitude[${i}]`, findTheSelectedDispatchRoutePinPoints['pin_points'][i].getPosition().lat());

                fd.append(`longitude[${i}]`, findTheSelectedDispatchRoutePinPoints['pin_points'][i].getPosition().lng());

            }

            let payload = {dispatch_route_code: route['dispatch_route_code'], pin_points: fd};

            this.$store.dispatch("dispatchRoute/saveThePinPointsForTheSelectedDispatchRoutes", payload).then(response => {

                this.snackBarMessage = response.message;

                this.snackBarSuccess = true;

                this.snackBar = true;

                route['set_pin_points'] = 0;

                route['can_set_pin_point'] = 0;

                this.canSetPinPointForDispatchRoute = route['can_set_pin_point'];

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

        },

        //Function to add store to selected dispatch routes.
        addStoreToSelectedDispatchRoute(routeIndex) {

            this.dispatchRouteIndex = routeIndex;

            this.addNewDispatch = true;

            this.storesAlongWithWarehouseToCreateDispatchRoute = [];

            this.storesAlongWithWarehouseToCreateDispatchRoute.push({
                store_address: '',
                store_code: 1212121212,
                store_latitude: this.source.lat,
                store_location_code: 12121212121,
                store_logo: '',
                store_longitude: this.source.lng,
                store_name: 'Warehouse',
            });

            if (this.dispatchedRoutes[routeIndex]['associated_stores'].length > 1) {

                for (let i = 0; i < this.dispatchedRoutes[routeIndex]['associated_stores'].length; i++) {

                    let store = this.dispatchedRoutes[routeIndex]['associated_stores'][i];

                    if (i !== 0) {

                        this.storesAlongWithWarehouseToCreateDispatchRoute.push(store);

                    }

                }

            }

        },

        //Function to set the route name.
        setRouteNameForTheDispatchRoute(route) {

            route['set_dispatch_route_name'] = 1;


        },

        //Api to save the dispatch route name
        saveTheDispatchRouteName(route) {

            let fd = new FormData();

            fd.append('_method', 'put');

            fd.append('route_name', this.dispatchRouteChangedName);

            let payload = {dispatch_route_code: route['dispatch_route_code'], route_name: fd};

            this.$store.dispatch("dispatchRoute/setTheDispatchRouteName", payload).then(response => {

                route['dispatch_route_name'] = this.dispatchRouteChangedName;

                this.snackBarMessage = response.message;

                this.snackBarSuccess = true;

                this.snackBar = true;

                route['set_dispatch_route_name'] = 0;

                setTimeout(() => {

                    this.snackBar = false;

                }, 4000);

            })

                .catch(e => {

                    this.snackBarMessage = e.response.data.message;

                    this.snackBarSuccess = false;

                    this.snackBar = true;

                    setTimeout(() => {

                        this.snackBar = false;

                    }, 4000);

                })

        },

        //Confirmation Message Dialog proceed.
        confirmAndProceedToHitApi() {

            if (this.removeStoreFromTheDispatchRoute) {

                this.removeTheStoreFromTheDispatchedRouteList();

            } else if (this.removeDispatchRoute) {

                this.removeTheDispatchedRoute();

            }

        },

        setCurrentPolylineForTheDispatchRoutes() {
            console.log(this.dispatchedRoutes[1]['associated_stores'], 'as');

            if (this.allPolyline.length > 0) {

                this.allPolyline.map(data => {
                    data.setVisible(false);
                });

                this.allPolyline = [];

            }


            for (let i = 0; i < this.dispatchedRoutes.length; i++) {

                console.log(i, 'index');

                if (this.dispatchedRoutes[i]['status'] === 'pending') {

                    for (let j = 0; j < this.dispatchedRoutes[i]['associated_stores'].length; j++) {

                        if (this.dispatchedRoutes[i]['associated_stores'][j + 1]) {

                            let source = {
                                lat: this.dispatchedRoutes[i]['associated_stores'][j]['store_latitude'],
                                lng: this.dispatchedRoutes[i]['associated_stores'][j]['store_longitude'],
                                store_name: this.dispatchedRoutes[i]['associated_stores'][j]['store_name'],
                            }

                            let destination = {
                                lat: this.dispatchedRoutes[i]['associated_stores'][j + 1]['store_latitude'],
                                lng: this.dispatchedRoutes[i]['associated_stores'][j + 1]['store_longitude'],
                                store_name: this.dispatchedRoutes[i]['associated_stores'][j + 1]['store_name'],
                            }

                            console.log(source, 'source', destination, 'index', i);

                            this.setPolyline(this.dispatchedRoutes[i]['route_color'], 2, 0.4, source, destination, this.dispatchedRoutes[i]['dispatch_route_code'], this.dispatchedRoutes[i]['associated_stores'][j]);

                        }

                    }

                }

            }


        }

    }

}
</script>

<style>
.cursor_hover:hover {
    cursor: pointer;
}

/* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    width: 46px;
    height: 18px;
}

/* Hide default HTML checkbox */
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

/* The slider */
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 0px;
    top: 0px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input:checked + .slider {
    background-color: #2196F3;
}

input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.border_bottom {
    border: 1px solid #e5e5e5 !important;
    border-radius: 4px !important;
    box-shadow: 3px 2px #c1c1c1 !important;
}

.border_bottom:hover {
    cursor: move !important;
}

.google_selection >>> label {
    font-size: 0.7rem !important;
}
</style>

<style lang="scss">

.card-carousel-wrapper {
    display: flex;
    align-items: center;
    justify-content: left;
    position: relative !important;
    margin: 20px 0 40px;
    color: grey;
}

.card-carousel {
    display: flex;
    justify-content: left;
    width: 100%;

    &--overflow-container {
        overflow: hidden;
    }

    &--nav__left,
    &--nav__right {
        display: inline-block;
        width: 15px;
        height: 15px;
        padding: 10px;
        box-sizing: border-box;
        border-top: 2px solid blue;
        border-right: 2px solid blue;
        cursor: pointer;
        margin: 0 20px;
        transition: transform 150ms linear;

        &[disabled] {
            opacity: 0.2;
            border-color: black;
        }
    }

    &--nav__left {
        transform: rotate(-135deg);

        &:active {
            transform: rotate(-135deg) scale(0.9);
        }
    }

    &--nav__right {
        transform: rotate(45deg);

        &:active {
            transform: rotate(45deg) scale(0.9);
        }
    }
}

.card-carousel-cards {
    display: flex;
    transition: transform 150ms ease-out;
    transform: translatex(0px);

    .card-carousel--card {
        margin: 0 10px;
        cursor: pointer;
        box-shadow: 0 4px 15px 0 rgba(40, 44, 53, .06), 0 2px 2px 0 rgba(40, 44, 53, .08);
        background-color: #fff;
        border-radius: 4px;
        z-index: 3;
        margin-bottom: 2px;

        &:first-child {
            margin-left: 0;
        }

        &:last-child {
            margin-right: 0;
        }

        img {
            vertical-align: bottom;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            transition: opacity 150ms linear;
            user-select: none;

            &:hover {
                opacity: 0.5;
            }
        }

        &--footer {
            border-top: 0;
            padding: 7px 15px;

            p {
                padding: 3px 0;
                margin: 0;
                margin-bottom: 2px;
                font-size: 19px;
                font-weight: 500;
                color: blue;
                user-select: none;

                &.tag {
                    font-size: 11px;
                    font-weight: 300;
                    padding: 4px;
                    background: rgba(40, 44, 53, .06);
                    display: inline-block;
                    position: relative;
                    margin-left: 4px;
                    color: grey;

                    &:before {
                        content: "";
                        float: left;
                        position: absolute;
                        top: 0;
                        left: -12px;
                        width: 0;
                        height: 0;
                        border-color: transparent rgba(40, 44, 53, .06) transparent transparent;
                        border-style: solid;
                        border-width: 8px 12px 12px 0;
                    }

                    &.secondary {
                        margin-left: 0;
                        border-left: 1.45px dashed white;

                        &:before {
                            display: none !important;
                        }
                    }

                    &:after {
                        content: "";
                        position: absolute;
                        top: 8px;
                        left: -3px;
                        float: left;
                        width: 4px;
                        height: 4px;
                        border-radius: 2px;
                        background: white;
                        box-shadow: -0px -0px 0px #004977;
                    }
                }
            }
        }
    }
}

.card-carousel--nav__left {
    position: absolute !important;
    left: 0 !important;
    z-index: 2 !important;
}

.card-carousel--nav__right {
    position: absolute !important;
    right: 0 !important;
    z-index: 2 !important;
}
</style>
