<?php

namespace App\Modules\Store\Resources\StorePreOrder;

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class MinimalStorePreOrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $store_preorder_detail = [
        "store_preorder_detail_code" => $this->store_preorder_detail_code,
        "store_preorder_code" => $this->store_preorder_code,
        //"warehouse_preorder_product_code" => $this->warehouse_preorder_product_code,
        "package_code" => $this->package_code,
        //"product_packaging_history_code" => $this->product_packaging_history_code,
        "quantity" => $this->quantity,
      //  "initial_order_quantity" => $this->initial_order_quantity,
        ];

        return $store_preorder_detail;
    }

}
