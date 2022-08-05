<?php

/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 7/3/2021
 * Time: 11:20 PM
 */


namespace App\Modules\Vendor\Resources\VendorTargetIncentative;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorTargetIncentativeResource extends JsonResource
{

    public function toArray($request)
    {
        $data = [
            'vendor_target_incentive_code' => $this->vendor_target_incentive_code,
            'vendor_target_master_code' => $this->vendor_target_master_code,
            'product_code' => isset($this->product_code)? $this->product_code:'N/A',
            'product_variant_code' => isset($this->product_variant_code)?$this->product_variant_code:'N/A',
            'starting_range' =>$this->starting_range,
            'end_range' => $this->end_range,
            'incentive_type' => $this->incentive_type,
            'incentive_value' => $this->incentive_value,
            'has_meet_target' => $this->has_meet_target,
            'createdBy' => $this->createdBy->name,
            'updatedBy' => $this->updated_by,
        ];

        return $data;
    }
}







