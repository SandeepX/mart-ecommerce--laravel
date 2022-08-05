<?php


namespace App\Modules\AlpasalWarehouse\Resources\WarehouseDispatchRoute;


use Illuminate\Http\Resources\Json\JsonResource;

class DispatchableStoreResource extends JsonResource
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
            'store_code' => $this->store_code,
            'store_name' => $this->store_name,
            'store_latitude' => $this->latitude,
            'store_longitude' => $this->longitude,
            'store_address' => $this->store_landmark_name,
            'store_logo' => photoToUrl($this->store_logo, asset('uploads/stores/logos')),
            'total_orders' => $this->total_orders,
            'total_amount' => $this->total_amount
        ];
    }
}
