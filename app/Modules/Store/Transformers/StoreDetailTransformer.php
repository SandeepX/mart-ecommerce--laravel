<?php

namespace App\Modules\Store\Transformers;


use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\Store\Models\Store;

class StoreDetailTransformer
{
    private $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function transform(){
        $storeLocation = (new LocationHierarchyRepository)->getLocationByCode($this->store->store_location_code);
        $storeLocTree = (new LocationHierarchyRepository)->getLocationPath($storeLocation);

        return [
            'store_name' => $this->store->store_name,
            'store_code' => $this->store->store_code,
            //'store_type' => $this->store->companyType->company_type_name,
            //'registration_type' => $this->store->registrationType->registration_type_name,
            'store_logo' => photoToUrl($this->store->store_logo,asset('uploads/stores/logos')),
            //'store_size' =>$this->store->storeSize->store_size_name,
            //'store_established_date' => $this->store->store_established_date,
            'store_owner' => $this->store->store_owner,
            'pan_vat_type'=>$this->store->pan_vat_type,
            'pan_vat_no'=>$this->store->pan_vat_no,
            'status'=>$this->store->status,
            'remarks'=>$this->store->remarks,
            'non_refundable_registration_charge'=>$this->store->storeTypePackage ? $this->store->storeTypePackage->non_refundable_registration_charge : 0,
            'refundable_registration_charge'=>$this->store->storeTypePackage ? $this->store->storeTypePackage->refundable_registration_charge : 0,
            'base_investment'=>$this->store->storeTypePackage ? $this->store->storeTypePackage->base_investment : 0,
            'store_type_name'=>$this->store->storeType ? $this->store->storeType->store_type_name : '',
            'package_name'=>$this->store->storeTypePackage ? $this->store->storeTypePackage->package_name : '',
            'has_purchase_power'=>$this->store->has_purchase_power,
            // 'store_tax' => [
            //      'type' => !is_null($this->store->pan) ? 'PAN No.' : 'VAT No.',
            //      'number' => !is_null($this->store->pan) ? $this->store->pan : $this->store->vat
            // ],
            'contact_details' => [
                'contact_mobile' => $this->store->store_contact_mobile,
                'contact_phone' => $this->store->store_contact_phone,
                'contact_email'=> $this->store->store_email,
               // 'contact_fax' => $this->store->contact_fax
            ],
            'location_details' => [
                'province' => $storeLocTree['province'],
                'district' => $storeLocTree['district'],
                'municipality' => $storeLocTree['municipality'],
                "ward" => $storeLocTree['ward'],
                'landmark' => [
                    'name' => $this->store->store_landmark_name,
                    'lat' => $this->store->latitude,
                    'long' => $this->store->longitude
                ]
                ],
            'store_user_details' => [

                'name' => ($this->store->user)? $this->store->user->name:'N/A',
                'email' => ($this->store->user) ?$this->store->user->login_email :'N/A'
            ],
//                'name' => $this->store->user->name,
//                'email' => $this->store->user->login_email
//            ],
            'store_package_histories' => $this->store->storePackageHistories()->orderBy('id','DESC')->get()


        ];
    }
}
