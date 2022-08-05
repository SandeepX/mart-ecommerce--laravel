<?php

namespace App\Modules\Vendor\Resources\VendorOrder;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorOrderReceivedListResource extends JsonResource
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
            'order_vendor_received_code' => $this->order_vendor_received_code,
            'order_code' => $this->order_code,
            'order_received_date' => $this->order_received_date,
            'order_received_status' => $this->order_received_status,
        ];
    }
}
