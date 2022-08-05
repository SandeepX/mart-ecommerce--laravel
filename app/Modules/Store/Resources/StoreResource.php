<?php


namespace App\Modules\Store\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Location\Repositories\LocationHierarchyRepository;

class StoreResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $storeLocation = (new LocationHierarchyRepository)->getLocationByCode($this->store_location_code);
        $storeLocTree = (new LocationHierarchyRepository)->getLocationPath($storeLocation);

        return [
            'store_name' => $this->store_name,
            'store_code' => $this->store_code,
           // 'store_type' => $this->companyType->company_type_name,
           // 'registration_type' => $this->registrationType->registration_type_name,
            'store_logo' => photoToUrl($this->store_logo,asset('uploads/stores/logos')),
           // 'store_size' =>$this->storeSize->store_size_name,
            'store_establishe_date' => $this->store_established_date,
            'store_owner' => $this->store_owner,
            // 'store_tax' => [
            //      'type' => !is_null($this->pan) ? 'PAN No.' : 'VAT No.',
            //      'number' => !is_null($this->pan) ? $this->pan : $this->vat
            // ],
            'contact_details' => [
                'contact_mobile' => $this->store_contact_mobile,
                'contact_phone' => $this->store_contact_phone,
                'contact_email'=> $this->store_email,
               // 'contact_fax' => $this->contact_fax
            ],
            'location_details' => [
                'province' => $storeLocTree['province'],
                'district' => $storeLocTree['district'],
                'municipality' => $storeLocTree['municipality'],
                "ward" => $storeLocTree['ward'],
                'landmark' => [
                    'name' => $this->store_landmark_name,
                    'lat' => $this->latitude,
                    'long' => $this->longitude
                ]
            ],
            'package_details' => [
                'store_type' => $this->storeType ? $this->storeType->store_type_name : '',
                'store_type_code' => $this->storeType ? $this->storeType->store_type_code : '',
                'store_type_package' => $this->storeTypePackage ? $this->storeTypePackage->package_name : '',
                'base_investment' => $this->storeTypePackage ? $this->storeTypePackage->base_investment : '',
                'non_refundable_registration_charge' => $this->storeTypePackage ? $this->storeTypePackage->non_refundable_registration_charge : '',
                'refundable_registration_charge' => $this->storeTypePackage ? $this->storeTypePackage->refundable_registration_charge : '',
                'annual_purchasing_limit' => $this->storeTypePackage ? $this->storeTypePackage->annual_purchasing_limit : '',
                'has_purchase_power' => $this->has_purchase_power,
                'enable_purchase_message' => ($this->has_purchase_power == 0) ? 'Please Pay Base Investment Charge ('.$this->storeTypePackage->base_investment.') for purchase power': '',
            ]

        ];
    }

}
