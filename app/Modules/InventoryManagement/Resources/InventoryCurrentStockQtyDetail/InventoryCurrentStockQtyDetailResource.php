<?php


namespace App\Modules\InventoryManagement\Resources\InventoryCurrentStockQtyDetail;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryCurrentStockQtyDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'siirqd_code' => $this->siirqd_code,
            'product_code' => $this->storeInventoryItemDetail->storeInventory->product_code,
            'product_variant_code' => ($this->storeInventoryItemDetail->storeInventory->product_variant_code)?
                $this->storeInventoryItemDetail->storeInventory->product_variant_code:null,
            'package_code' =>$this->package_code,
            'package_name' => $this->packageTypeDetail->package_name,
            'pph_code' => $this->pph_code,
            'quantity' => $this->quantity,
            'source' => ucfirst(str_replace('_',' ',$this->source)),
            'reference_code' => ($this->reference_code)?$this->reference_code:'N/A',
            'created_at' => ($this->created_at)->format('Y-m-d h:i:s'),
            'updated_at' => ($this->updated_at)->format('Y-m-d h:i:s'),
        ];
    }
}






