<?php


namespace App\Modules\InventoryManagement\Resources\StoreInventorySales;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreInventorySalesRecordDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'siidqd_code' => $this->siidqd_code,
            'siid_code' => $this->siid_code,
            'product_code' => $this->storeInventoryItemDetail->storeInventory->product_code,
            'product_variant_code' => ($this->storeInventoryItemDetail->storeInventory->product_variant_code) ?
                $this->storeInventoryItemDetail->storeInventory->product_variant_code : null,
            'package_code' => $this->package_code,
            'package_name' => $this->packageTypeDetail->package_name,
            'pph_code' => $this->pph_code,
            'quantity' => $this->quantity,
            'selling_price' => $this->selling_price,
            'mrp' => $this->storeInventoryItemDetail->mrp,
            'payment_type' => ( $this->payment_type),
            'created_at' => ($this->created_at)->format('Y-m-d h:i:s'),
            'updated_at' => ($this->updated_at)->format('Y-m-d h:i:s'),
        ];
    }
}







