<?php

namespace App\Modules\Store\Resources\StorePreOrder;

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class StorePreOrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->package_name){
            $packageName = $this->package_name;
        }
        elseif ($this->old_package_name){
            $packageName= $this->old_package_name;
        }
        else{
            $packageName='-';
        }
        $store_preorder_detail = [
            'store_preorder_code'=>$this->store_preorder_code,
            'store_preorder_detail_code'=>$this->store_preorder_detail_code,
            'warehouse_preorder_product_code'=>$this->warehouse_preorder_product_code,
            'product_name'=>$this->product_name,
            'image' => photoToUrl($this->image, asset((new ProductMaster())->uploadFolder)),
            'initial_order_quantity' =>(int)$this->initial_order_quantity,
            'quantity' =>(int)$this->quantity,
            'is_taxable'=>$this->is_taxable,
            'created_at' => getReadableDate(getNepTimeZoneDateTime($this->created_at)),
            'updated_at' => getReadableDate(getNepTimeZoneDateTime($this->updated_at)),
            'is_active' => $this->is_active,
            'unit_rate'=>$this->unit_rate,
            'delivery_status'=>$this->delivery_status,
            'payable' => ($this->is_active && $this->delivery_status) ? true : false,
            //'package_name' => $this->package_name
            'package_name' => $packageName
        ];
//        if($this->is_taxable){
//            $store_preorder_detail['unit_rate'] = roundPrice($this->store_price / ( (1 + (StorePreOrder::VAT_PERCENTAGE_VALUE/100) )) );
//
//        }else{
//             $store_preorder_detail['unit_rate'] = roundPrice($this->store_price);
//        }
        $store_preorder_detail['sub_total'] = roundPrice($store_preorder_detail['unit_rate'] * $this->quantity);
        return $store_preorder_detail;
    }

}
