<?php


namespace App\Modules\AlpasalWarehouse\Resources\WarehouseDispatchRoute;


use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseDispatchRouteDetailResource extends JsonResource
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
            'dispatch_route_code' => $this->wh_dispatch_route_code,
            'dispatch_route_name' => $this->route_name,
            'vehicle_name' => $this->vehicle_name,
            'vehicle_number' => $this->vehicle_number,
            'driver_name' => $this->driver_name,
            'driver_license_number' => $this->driver_license_number,
            'driver_contact_primary' => $this->driver_contact_primary,
            'driver_contact_secondary' => $this->driver_contact_secondary,
            'status' => $this->status,
            'verification_question' => isset($this->question_checked_meta) ? json_decode($this->question_checked_meta,true) : [],
            'created_by' => $this->createdBy->name,
            'updated_by' => $this->updatedBy->name,
            'created_at' => getReadableDate($this->created_at),
            'updated_at' => getReadableDate($this->updated_at),
            'associated_stores' =>$this->getAssociatedStores()
        ];
    }

    public function getAssociatedStores(){
        if ($this->status == 'dispatched'){

            $associatedStores=$this->warehouseDispatchRouteStores->map(function ($routeStore){
                return [
                    'wh_dispatch_route_store_code' => $routeStore->wh_dispatch_route_store_code,
                    'store_code' => $routeStore->store_code,
                    'store_name' => $routeStore->store->store_name,
                    'store_latitude' => $routeStore->store->latitude,
                    'store_longitude' => $routeStore->store->longitude,
                    'store_address' => $routeStore->store->store_landmark_name,
                    'sort_order' => $routeStore->sort_order,
                    'total_amount' => $routeStore->warehouseDispatchRouteStoreOrders->sum('total_amount'),
                    'store_orders' => $routeStore->warehouseDispatchRouteStoreOrders
                ];
            });

        }else{

            $associatedStores=$this->warehouseDispatchRouteStores->map(function ($routeStore){
                return [
                    'wh_dispatch_route_store_code' => $routeStore->wh_dispatch_route_store_code,
                    'store_code' => $routeStore->store_code,
                    'store_name' => $routeStore->store->store_name,
                    'store_latitude' => $routeStore->store->latitude,
                    'store_longitude' => $routeStore->store->longitude,
                    'store_address' => $routeStore->store->store_landmark_name,
                    'sort_order' => $routeStore->sort_order,
                    'total_amount' => $routeStore->store_orders->sum('total_amount'),
                    'store_orders' => $routeStore->store_orders
                ];
            });
        }

        return $associatedStores;
    }
}
