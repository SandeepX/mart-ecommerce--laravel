<?php


namespace App\Modules\InventoryManagement\Resources\StoreInventoryItemDetail;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreInventoryItemDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
//            'inventory_code' =>$this->store_inventory_code,
            'siid_code' => $this->siid_code,
            'cost_price' => $this->cost_price,
            'mrp' => $this->mrp,
            'manufacture_date' => $this->manufacture_date,
            'expiry_date' => $this->expiry_date,
            'option_data' => ('Cost Price:'.$this->cost_price.', MRP:'.$this->mrp. ', Manufacture Date:'.$this->manufacture_date. ', Expiry date:'.$this->expiry_date)

        ];
    }
}









