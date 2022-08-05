<?php

namespace App\Modules\Store\Resources\StorePreOrder;

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class StorePreOrderStatusLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $store_preorder_status_log = [
            //'store_preorder_status_log_code'=>$this->store_preorder_status_log_code,
            'store_preorder_code'=>$this->store_preorder_code,
            'status'=>$this->status,
            'remarks'=>$this->remarks,
            'created_at'=>getReadableDate(getNepTimeZoneDateTime($this->created_at))
        ];
        return $store_preorder_status_log;
    }

}
