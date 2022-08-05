<?php

namespace App\Modules\Vendor\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MinimalVendorResource extends JsonResource
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
           // 'id' => $this->id,
            'vendor_name' => $this->vendor_name,
            'vendor_code' => $this->vendor_code,
            'vendor_type' => $this->vendorType->vendor_type_name,
            'company_type' => $this->companyType->company_type_name,
            'registration_type' => $this->registrationType->registration_type_name,
            'vendor_logo' => photoToUrl($this->vendor_logo,asset('uploads/vendors/logo'))
        ];
    }
}
