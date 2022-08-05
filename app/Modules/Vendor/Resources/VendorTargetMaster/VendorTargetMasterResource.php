<?php

/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 6/3/2021
 * Time: 11:20 PM
 */

namespace App\Modules\Vendor\Resources\VendorTargetMaster;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorTargetMasterResource  extends JsonResource
{

    public function toArray($request)
    {
        $data = [
            'vendor_target_master_code' => $this->vendor_target_master_code,
            'name' => $this->name,
            'slug' => $this->slug,
            'province_code' => $this->province_code,
            'district_code' => $this->district_code,
            'municipalityCode' => $this->municipality_code,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'is_active' => $this->is_active,
            'status' => $this->status,
            'remark' => $this->remark,
            'createdBy_user_code' => $this->created_by,
            'createdBy_name' => $this->createdBy->name,
            'updatedBy' => $this->updated_by,
        ];
        $data['vendor']['vendor_code'] = $this->vendor_code;
        $data['vendor']['vendor_name'] = $this->vendor->vendor_name;

        return $data;
    }
}






