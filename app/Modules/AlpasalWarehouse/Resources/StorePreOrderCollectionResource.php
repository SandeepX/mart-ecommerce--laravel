<?php


namespace App\Modules\AlpasalWarehouse\Resources;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderCollectionHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class StorePreOrderCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $with=[
            'product.images',
            'product:product_code,product_name,slug,highlights'
        ];

        return [
            'warehouse_preorder_listing_code'=> $this->warehouse_preorder_listing_code,
            'warehouse_code' => $this->warehouse_code,
            'pre_order_name'=>$this->pre_order_name,
            'start_time' => $this->start_time,
            'display_start_time'=>$this->getStartTime('d M Y'),
            'end_time' => $this->end_time,
            'display_end_time'=>$this->getEndTime('d M Y'),
            'finalization_time' => $this->finalization_time,
            'display_finalizationTime_time'=>$this->getFinalizationTime('d M Y'),
            'is_active' => $this->is_active,
            'banner_image'=>photoToUrl($this->banner_image,url(WarehousePreOrderListing::IMAGE_PATH)),
            'can_pre_order'=>$this->isPreOrderable() ? true : false,
            'has_start_time_past'=>$this->isPastStartTime(),
            'pre_orderable_products' => StorePreOrderCollectionHelper::preOrderProductsForStoreCollection(
                $this->warehouse_preorder_listing_code,$with
            ) ->map(function ($warehousePreOrderProduct) {
                    return new WarehousePreOrderProductResource($warehousePreOrderProduct);
                }),
            'pre_orderable_products_count'=> count($this->warehousePreOrderProducts()
                                                  ->where('is_active',1)
                                                  ->groupBy('product_code')
                                                  ->get()
            )
        ];
    }
}
