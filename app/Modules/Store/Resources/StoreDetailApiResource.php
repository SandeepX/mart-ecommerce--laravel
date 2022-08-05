<?php


namespace App\Modules\Store\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreDetailApiResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(!empty($this->pan_vat_no) && $this->has_store === 1){
            $hasStore = 1;
        }
        else{
            $hasStore = 0;
        }

        return [

        'has_complete_billing_info'=>$hasStore,

        ];
    }

}
