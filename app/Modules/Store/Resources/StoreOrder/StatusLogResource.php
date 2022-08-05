<?php


namespace App\Modules\Store\Resources\StoreOrder;


use Illuminate\Http\Resources\Json\JsonResource;

class StatusLogResource extends JsonResource
{
   public function toArray($request)
   {
       return [
           'status' =>$this->status,
           'status_log_code' =>$this->store_order_status_log_code,
           'status_update_date'=>$this->status_update_date,
           'updated_at'=>$this->updated_at,
           'remarks' => $this->remarks
       ];
   }
}
