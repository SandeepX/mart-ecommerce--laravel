<?php

namespace App\Modules\AlpasalWarehouse\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseProductCollectionResource extends JsonResource
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
            'product_collection_code' => $this->product_collection_code,
            'warehouse_code' => $this->warehouse_code,
            'product_collection_title' => $this->product_collection_title,
            'product_collection_subtitle' => $this->product_collection_subtitle,
            'product_collection_slug' => $this->product_collection_slug,
            'product_collection_image' => url('/uploads/alpasalwarehouse/warehouse-product-collections/'.$this->product_collection_image),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'products_count' => count($this->warehouseProductMasters()->wherePivot('is_active',1)
                ->where('current_stock','>',0)
                ->qualifiedToDisplay()->whereHas('product',function ($query){
                    $query->where('is_active',1)->whereHas('unitPackagingDetails');
                })
//                ->whereHas('warehouseProductStockView',function ($query){
//                    $query->havingRaw('SUM(current_stock) > 0');
//                })
                ->groupBy('wh_product_collection_details.warehouse_product_master_code','wh_product_collection_details.product_collection_code')
                ->get()),
//            'products'=>$this->warehouseProductMasters,
            'products' => $this->warehouseProductMasters()->wherePivot('is_active', 1)
                ->where('current_stock','>',0)
                ->qualifiedToDisplay()->whereHas('product',function ($query){
                    $query->where('is_active',1)->whereHas('unitPackagingDetails');
                })
//                ->whereHas('warehouseProductStockView',function ($query){
//                    $query->havingRaw('SUM(current_stock) > 0');
//                })
                ->groupBy('wh_product_collection_details.warehouse_product_master_code','wh_product_collection_details.product_collection_code')
                ->limit(8)
                ->get()->map(function ($warehouseProductMaster) {
                $whProduct=$warehouseProductMaster->product;
                    return new WarehouseProductResource($whProduct);
            })
        ];
    }
}
