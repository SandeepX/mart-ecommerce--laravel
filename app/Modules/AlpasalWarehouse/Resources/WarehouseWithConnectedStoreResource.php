<?php

namespace App\Modules\AlpasalWarehouse\Resources;

use App\Modules\Store\Resources\MinimalStoreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseWithConnectedStoreResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'warehouse_code' => $this->warehouse_code,
            'warehouse_name' => $this->warehouse_name,
            'slug' => $this->slug,
            'stores'=>$this->stores->map(function ($store){
                return [
                    'store_code' => $store->store_code,
                    'store_name' => $store->store_name
                ];
            })
        ];
    }

}
