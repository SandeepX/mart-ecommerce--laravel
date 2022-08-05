<?php


namespace App\Modules\AlpasalWarehouse\Resources\WarehouseDispatchRoute;


use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseDispatchRouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'dispatch_route_code' => $this[0]->wh_dispatch_route_code,
            'dispatch_route_name' => $this[0]->route_name,
            'status' => $this[0]->status,
            'associated_stores' => $this->arrangeStores(),
            'pin_points' =>$this[0]->status == 'dispatched' ? []: $this[0]->warehouseDispatchRouteMarkers
        ];
    }

    public function arrangeStores()
    {
       // dd(count((array)$this));
        $stores = [];
        foreach ($this->resource as $store) {
            if ($store->wh_dispatch_route_store_code){
                array_push($stores, [
                    'dispatch_route_store_code' => $store['wh_dispatch_route_store_code'],
                    'store_code' => $store['store_code'],
                    'store_name' => $store['store_name'],
                    'sort_order' => $store['sort_order'],
                    'store_latitude' => $store['latitude'],
                    'store_longitude' => $store['longitude'],
                    'store_address' =>$store['store_landmark_name'],
                    'store_logo' => photoToUrl($store['store_logo'], asset('uploads/stores/logos'))
                ]);
            }

        }

        return $stores;

    }
}
