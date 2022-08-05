<?php


namespace App\Modules\Store\Resources\StorePreOrder;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Store\Models\PreOrder\StorePreOrder;

class StorePreOrderListingResource extends JsonResource
{
    public function toArray($request)
    {
        $now_time = Carbon::now('Asia/Kathmandu')->toDateTimeString();
        $storePreOrderDetail = [
            'store_preorder_code' => $this->store_preorder_code,
           'pre_order_name' => $this->warehousePreOrderListing->pre_order_name,
            'warehouse_preorder_listing_code' => $this->warehousePreOrderListing->warehouse_preorder_listing_code,
           'store_code' => $this->store_code,
           'payment_status' => $this->payment_status,
           'status' => $this->status,
           'created_at' => $this->created_at,
           'warehouse_code' => $this->warehouse_code,
           'start_time' => $this->start_time,
           'end_time'=> $this->end_time,
           'total_price'=>roundPrice($this->total_price),
           'pre_order_elapsed' =>$this->end_time < $now_time ? 1 : 0,
//            'total_group_target'=>(int)$this->total_group_target,
//            'total_individual_target'=>(int)$this->total_individual_target,
//            'total_group_order'=>(int)$this->total_group_order,
        ];

        $storePreOrderDetail['activation_period']['start_time'] = getReadableDate($this->start_time);
        $storePreOrderDetail['activation_period']['end_time'] = getReadableDate($this->end_time);
        $storePreOrderDetail['activation_period']['finalization_time'] = getReadableDate($this->finalization_time);
        $storePreOrderDetail['products_count'] = $this->store_pre_order_details_count;
        $storePreOrderDetail['warehouse_name'] =$this->warehouse_name;
        return $storePreOrderDetail;
    }
}
