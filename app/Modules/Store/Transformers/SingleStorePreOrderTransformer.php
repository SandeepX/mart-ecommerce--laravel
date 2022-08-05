<?php

namespace App\Modules\Store\Transformers;

use App\Modules\Store\Models\Store;
use App\Modules\Store\Resources\StorePreOrder\StorePreOrderDetailCollection;
use App\Modules\Store\Resources\StorePreOrder\StorePreOrderStatusLogsCollection;

class SingleStorePreOrderTransformer
{
    private $storePreOrderDetail;

    public function __construct($storePreOrderDetail)
    {

        $this->storePreOrderDetail = $storePreOrderDetail;
      //  $this->targets = $targets;
    }

    public function transform(){

       // dd($this->storePreOrderDetail);

        $storePreOrder =  [
            'store_preorder_code' => $this->storePreOrderDetail['store_pre_order']->store_preorder_code,
            'warehouse_preorder_listing_code' => $this->storePreOrderDetail['store_pre_order']->warehouse_preorder_listing_code,
            'warehouse_preorder_listing_name' => $this->storePreOrderDetail['store_pre_order']->warehousePreOrderListing->pre_order_name,
            'payment_status' =>$this->storePreOrderDetail['store_pre_order']->payment_status,
            'store_preorder_status' =>$this->storePreOrderDetail['store_pre_order']->status,
            'preorder_start_time'=>$this->storePreOrderDetail['store_pre_order']->warehousePreOrderListing->start_time,
            'preorder_end_time'=>$this->storePreOrderDetail['store_pre_order']->warehousePreOrderListing->end_time,
            'preorder_finalization_time'=>$this->storePreOrderDetail['store_pre_order']->warehousePreOrderListing->finalization_time,
            'product_count' => (isset($this->storePreOrderDetail['store_pre_order_detail'])) ? $this->storePreOrderDetail['store_pre_order_detail']->count() : 0,
            'details' => (new StorePreOrderDetailCollection($this->storePreOrderDetail['store_pre_order_detail'])),
            'status_logs'=>(new StorePreOrderStatusLogsCollection($this->storePreOrderDetail['store_pre_order_status_logs'])),
        ];
//        $storePreOrder['targets']=['total_price'=>$this->targets->total_price,
//            'total_group_target'=>(int)$this->targets->total_group_target,
//            'total_individual_target'=>(int)$this->targets->total_individual_target,
//            'total_group_order'=>(int)$this->targets->total_group_order
//        ];
        $storePreOrder['readable_time']['start_time'] = getReadableDate($this->storePreOrderDetail['store_pre_order']->warehousePreOrderListing->start_time);
        $storePreOrder['readable_time']['end_time'] = getReadableDate($this->storePreOrderDetail['store_pre_order']->warehousePreOrderListing->end_time);
        $storePreOrder['readable_time']['finalization_time'] = getReadableDate($this->storePreOrderDetail['store_pre_order']->warehousePreOrderListing->finalization_time);

        if($this->storePreOrderDetail['store_pre_order']->status == 'dispatched' && $this->storePreOrderDetail['store_pre_order_dispatch_details']){

            $storePreOrder['dispatched_detail']['driver_name'] = $this->storePreOrderDetail['store_pre_order_dispatch_details']->driver_name;
            $storePreOrder['dispatched_detail']['vehicle_number'] = $this->storePreOrderDetail['store_pre_order_dispatch_details']->vehicle_number;
            $storePreOrder['dispatched_detail']['vehicle_type'] = $this->storePreOrderDetail['store_pre_order_dispatch_details']->vehicle_type;
            $storePreOrder['dispatched_detail']['vehicle_contact_number'] = $this->storePreOrderDetail['store_pre_order_dispatch_details']->contact_number;
            $storePreOrder['dispatched_detail']['expected_delivery_time'] = $this->storePreOrderDetail['store_pre_order_dispatch_details']->expected_delivery_time;
        }

       // dd($storePreOrder);

        return $storePreOrder;
    }
}
