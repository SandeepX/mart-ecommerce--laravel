<?php


namespace App\Modules\InventoryManagement\Resources\StoreInventorySales;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreInventoryProductVariantDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'product_variant_code' => $this->product_variant_code,
            'product_variant_name' => $this->productVariantDetail->product_variant_name,
        ];
    }
}








