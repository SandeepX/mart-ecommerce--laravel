<?php

namespace App\Modules\Store\Transformers;

use App\Modules\Store\Models\StoreOrder;


class SingleStoreOrderTransformer
{
    private $storeOrder;

    public function __construct(StoreOrder $storeOrder)
    {
        $this->storeOrder = $storeOrder;
    }

    public function transform(){
        $storeOrder = $this->storeOrder;
        return [
            'store_order_code' => $this->storeOrder->store_order_code,
            'total_price' => $this->storeOrder->total_price,
            'acceptable_price'=> $this->storeOrder->acceptable_amount ? $this->storeOrder->acceptable_amount : "N/A" ,
            'final_payment_status' => $this->storeOrder->payment_status,
            'payment_status' => $this->storeOrder->getLatestOfflinePaymentStatus() ? $this->storeOrder->getLatestOfflinePaymentStatus() : 'Unpaid',
            'can_pay' => $this->storeOrder->canAddOfflinePayment(),
            'delivery_status' => $this->storeOrder->delivery_status,
            //'details' => StoreOrderDetailResource::collection($this->storeOrder->details),
           'details'=> ($storeOrder->details)->each(function($detail) use ($storeOrder){
             return (new StoreOrderDetailTransformer($storeOrder,$detail))->transform();
          }),
            'status_log' => $this->storeOrder->statusLogs
        ];
    }
}
