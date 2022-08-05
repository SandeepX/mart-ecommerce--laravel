<?php


namespace App\Modules\Vendor\Resources\VendorOrder;


use Illuminate\Http\Resources\Json\JsonResource;

class VendorSalesReturnResource extends JsonResource
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
            'warehouse_order_code' => $this->warehouse_order_code,
            'vendor_code' => $this->vendor_code,
            'warehouse_order_detail_code' => $this->warehouse_order_detail_code,
            'return_quantity' => $this->return_quantity,
            'accepted_return_quantity' => $this->accepted_return_quantity,
            'status'=> $this->status,
            'reason_type'=> $this->reason_type,
            'return_reason_remarks'=> $this->return_reason_remarks,
            'status_remarks'=> $this->status_remarks,
            'status_responded_by'=> $this->status_responded_by,
            'status_responded_at'=> $this->status_responded_at,
            'total_sales_return_items'=>  $this->total_sales_return_items,
            'warehouse_name'=>$this->warehouse_name
        ];
    }
}
