<?php
/**
 * Created by PhpStorm.
 * User: Shramik
 * Date: 02/18/2021
 */

namespace App\Modules\SalesManager\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListOfStoresReferredByManagerResource extends JsonResource
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
            'store_name' => $this->referredStore->store_name,
            'store_type_name' => $this->referredStore->storeType ? $this->referredStore->storeType->store_type_name : null,
            'store_type_package' => $this->referredStore->storeTypePackage ? $this->referredStore->storeTypePackage->package_name : null,
            'referal_registration_incentive_amount'=>$this->referredStore->storeTypePackage ? $this->referredStore->storeTypePackage->referal_registration_incentive_amount : null,
            'annual_purchasing_limit'=>$this->referredStore->storeTypePackage ? $this->referredStore->storeTypePackage->annual_purchasing_limit : null,
            'store_code' => $this->referredStore->store_code,
            'status' => $this->referredStore->status,
        ];

        return $result;
    }

}
