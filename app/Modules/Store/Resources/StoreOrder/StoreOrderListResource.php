<?php

namespace App\Modules\Store\Resources\StoreOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrderListResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ordersList = [
            'store_order_code' => $this->store_order_code,
            'total_price' => ($this->total_price),
            'acceptable_price'=> is_null($this->acceptable_amount) ? "N/A" : $this->acceptable_amount ,
            'final_payment_status' => $this->payment_status,
            'payment_status' => $this->getLatestOfflinePaymentStatus() ? $this->translatePaymentStatus($this->getLatestOfflinePaymentStatus()) : 'Unpaid',
            //'payment_status_name' => $this->payment_status ? 'Paid' : 'Unpaid',
            'can_pay' => $this->canAddOfflinePayment(),
            'delivery_status' => ucwords($this->delivery_status),
            'created_at' => $this->created_at
        ];

        if(
            $this->delivery_status == 'under-verification'
            ||
            $this->delivery_status == 'pending'
        ){
            $ordersList['acceptable_price']= 'N/A';
        }

        return $ordersList;
    }

    private function translatePaymentStatus($paymentStatus){

        switch ($paymentStatus){

            case 'verified':
                return 'Paid';
                break;
            default:
                return ucwords($paymentStatus);
                break;

        }
    }

}
