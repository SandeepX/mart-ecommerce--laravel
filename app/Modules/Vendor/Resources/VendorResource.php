<?php

namespace App\Modules\Vendor\Resources;

use App\Modules\Location\Repositories\LocationHierarchyRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $vendorLocation = (new LocationHierarchyRepository)->getLocationByCode($this->vendor_location_code);
        $vendorLocTree = (new LocationHierarchyRepository)->getLocationPath($vendorLocation);
    
        return [
            'id' => $this->id,
            'vendor_name' => $this->vendor_name,
            'vendor_code' => $this->vendor_code,
            'vendor_type' => $this->vendorType->vendor_type_name,
            'company_type' => $this->companyType->company_type_name,
            'registration_type' => $this->registrationType->registration_type_name,
            'vendor_logo' => photoToUrl($this->vendor_logo,asset('/uploads/vendors/logos')),
            'vendor_owner' => $this->vendor_owner,
            'vendor_tax' => [
                 'type' => !is_null($this->pan) ? 'PAN No.' : 'VAT No.',
                 'number' => !is_null($this->pan) ? $this->pan : $this->vat
            ],
            'contact_details' => [
                'contact_person' => $this->contact_person,
                'contact_landline' => $this->contact_landline,
                'contact_email'=> $this->contact_email,
                'contact_fax' => $this->contact_fax
            ],
            'location_details' => [
                'province' => $vendorLocTree['province'],
                'district' => $vendorLocTree['district'],
                'municipality' => $vendorLocTree['municipality'],
                "ward" => $vendorLocTree['ward'],
                'landmark' => [
                    'name' => $this->vendor_landmark,
                    'lat' => $this->landmark_latitude,
                    'long' => $this->landmark_longitude
                ]

            ]


        ];
    }
}
