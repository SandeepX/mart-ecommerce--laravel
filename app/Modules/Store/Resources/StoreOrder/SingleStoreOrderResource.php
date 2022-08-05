<?php

namespace App\Modules\Store\Resources\StoreOrder;

use App\Modules\Store\Resources\StoreOrderRemarkResource;
use App\Modules\Store\Transformers\StoreOrderDetailTransformer;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Store\Resources\StoreOrder\StatusLogResource;

class SingleStoreOrderResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order = [
            'store_order_code' => $this->store_order_code,
            'total_price' => $this->total_price,
            'acceptable_price'=> !is_null($this->acceptable_amount) ? $this->acceptable_amount : "N/A" ,
            'final_payment_status' => $this->payment_status,
            'payment_status' => $this->getLatestOfflinePaymentStatus() ? $this->getLatestOfflinePaymentStatus() : 'Unpaid',
            'can_pay' => $this->canAddOfflinePayment(),
            'delivery_status' => strtoupper($this->delivery_status),
            'details' => StoreOrderDetailResource::collection($this->details),
            'status_log' => StatusLogResource::collection( $this->statusLogs),
            'remarks' => StoreOrderRemarkResource::collection($this->latestRemarks),
        ];

//        if($this->delivery_status == 'accepted'){
//            $order['acceptable_price'] = NULL;
//        }
        if($this->delivery_status == 'dispatched' && $this->storeOrderDispatchDetail){

            $order['dispatched_detail']['driver_name'] = $this->storeOrderDispatchDetail->driver_name;
            $order['dispatched_detail']['vehicle_number'] = $this->storeOrderDispatchDetail->vehicle_number;
            $order['dispatched_detail']['vehicle_type'] = $this->storeOrderDispatchDetail->vehicle_type;
            $order['dispatched_detail']['vehicle_contact_number'] = $this->storeOrderDispatchDetail->contact_number;
            $order['dispatched_detail']['expected_delivery_time'] = $this->storeOrderDispatchDetail->expected_delivery_time;
        }
        return $order;
    }

}
