<?php

namespace App\Modules\AlpasalWarehouse\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseProductCollectionDetailResource extends JsonResource
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
            'product_collection_title' => $this->product_collection_title,
            'product_collection_subtitle' => $this->product_collection_subtitle,
            'product_collection_image' => url('/uploads/alpasalwarehouse/warehouse-product-collections/'.$this->product_collection_image),
            'products_count' => count($this->warehouseProductMasters()
                ->wherePivot('is_active',1)
                ->where('current_stock','>',0)
                ->qualifiedToDisplay()
//                ->whereHas('warehouseProductStockView',function ($query){
//                    $query->havingRaw('SUM(current_stock) > 0');
//                })
                ->whereHas('product',function ($query){
                    $query->where('is_active',1)->whereHas('unitPackagingDetails');
                })
                ->where('warehouse_product_master.is_active',1)
                ->groupBy('wh_product_collection_details.warehouse_product_master_code','wh_product_collection_details.product_collection_code')
                ->get()),
        ];
    }
}
