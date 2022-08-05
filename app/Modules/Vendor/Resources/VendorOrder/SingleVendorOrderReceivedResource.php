<?php

namespace App\Modules\Vendor\Resources\VendorOrder;

use App\Modules\AlpasalWarehouse\Resources\OrderDetailResource;
use App\Modules\AlpasalWarehouse\Resources\SingleWarehouseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleVendorOrderReceivedResource extends JsonResource
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
            'warehouse_details' => new SingleWarehouseResource($this->order->warehouse),
            'order_details' => OrderDetailResource::collection($this->order->details),
            'order_received_date' => $this->order_received_date,
            'order_received_status' => $this->order_received_status,
        ];
    }
}
