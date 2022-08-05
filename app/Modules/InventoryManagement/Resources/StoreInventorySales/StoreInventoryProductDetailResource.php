<?php


namespace App\Modules\InventoryManagement\Resources\StoreInventorySales;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreInventoryProductDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'product_code' =>$this->product_code,
            'product_name' =>$this->productDetail->product_name,
        ];
    }
}







