<?php


namespace App\Modules\AlpasalWarehouse\Resources;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehousePreOrderResource extends JsonResource
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
            'status_type' => $this->status_type,
            'banner_image'=>photoToUrl($this->banner_image,url(WarehousePreOrderListing::IMAGE_PATH)),
//            'has_orders' => count($this->storePreOrders) ? true : false,
//            'store_pre_order_code'=> count($this->storePreOrders)
//                                     ? ($this->storePreOrders->first())['store_preorder_code']
//                                     : null,
            'can_pre_order' => $this->isPreOrderable(),
            'has_start_time_past'=>$this->isPastStartTime()
        ];
    }
}
