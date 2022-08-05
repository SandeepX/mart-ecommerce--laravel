<?php

namespace App\Modules\Cart\Resources;

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use Illuminate\Http\Resources\Json\JsonResource;
use Exception;

class MinimalCartResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'cart_code' => $this->cart_code,
            'product_code' => $this->product_code,
            'product_variant_code' =>$this->product_variant_code,
            'warehouse_code'=>$this->warehouse_code,
            'quantity' => (int)$this->quantity,
            'package_code'=>$this->package_code,
            'overall_stock' => $this->overall_stock,
            'product_packaging_types' => $this->product_packaging_types
        ];
    }


}
