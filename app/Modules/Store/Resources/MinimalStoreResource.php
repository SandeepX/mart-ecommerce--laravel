<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\Store\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MinimalStoreResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
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

        if(!empty($this->latitude) && !empty($this->longitude))
        {
            $mapInfo = 1;
        }
        else{
            $mapInfo = 0;
        }


        $result = [
            'store_name' => $this->store_name,
            'store_code' => $this->store_code,
            'is_billable' => $hasStore,
            'map_info' => $mapInfo,
           // 'store_type' => $this->companyType->company_type_name,
            //'registration_type' => $this->registrationType->registration_type_name,
            'status' => $this->status,
            'remarks' => $this->when($this->status === "rejected", $this->remarks),
            'store_logo' => photoToUrl($this->store_logo, asset('uploads/stores/logos'))
        ];
        return $result;
    }

}
