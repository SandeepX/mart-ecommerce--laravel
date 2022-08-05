<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/24/2020
 * Time: 4:39 PM
 */

namespace App\Modules\Store\Resources\StoreTypePackage;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class StoreTypePackagesResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'store_type_package_history_code'=>$this->store_type_package_history_code,
            'store_type_code'=>$this->store_type_code,
            'package_name'=>$this->package_name,
            'description'=>$this->description,
            'short_description'=>Str::limit($this->description,200),
            'refundable_registration_charge'=>$this->refundable_registration_charge,
            'non_refundable_registration_charge'=>$this->non_refundable_registration_charge,
            'base_investment'=>$this->base_investment,
            'annual_purchasing_limit'=>$this->annual_purchasing_limit,
           // 'referal_registration_incentive_amount'=>$this->referal_registration_incentive_amount,
            'is_active'=>$this->is_active,
            'image' => photoToUrl($this->image, asset('uploads/stores/storetypepackages/histories/images/'))
        ];
        return $result;
    }

}
