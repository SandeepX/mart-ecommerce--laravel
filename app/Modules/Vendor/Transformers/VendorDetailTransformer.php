<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/18/2020
 * Time: 4:55 PM
 */

namespace App\Modules\Vendor\Transformers;


use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\Vendor\Models\Vendor;

class VendorDetailTransformer
{

    private $vendor;

    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    public function transform(){
        $vendorLocation = (new LocationHierarchyRepository())->getLocationByCode($this->vendor->vendor_location_code);
        $vendorLocTree = (new LocationHierarchyRepository())->getLocationPath($vendorLocation);

        return [
            'vendor_name' => $this->vendor->vendor_name,
            'vendor_code' => $this->vendor->vendor_code,
            'vendor_type' => $this->vendor->companyType->company_type_name,
            'registration_type' => $this->vendor->registrationType->registration_type_name,
            'vendor_logo' => photoToUrl($this->vendor->vendor_logo,asset($this->vendor->getLogoUploadPath())),

            'pan_no' =>$this->vendor->pan,
            'vat_no' =>$this->vendor->vat,

            'joined_date' => date('M j Y', strtotime($this->vendor->created_at)),
            'vendor_owner' => $this->vendor->vendor_owner,
            // 'vendor_tax' => [
            //      'type' => !is_null($this->vendor->pan) ? 'PAN No.' : 'VAT No.',
            //      'number' => !is_null($this->vendor->pan) ? $this->vendor->pan : $this->vendor->vat
            // ],
            'contact_details' => [
                'contact_person'=>$this->vendor->contact_person,
                'contact_mobile' => $this->vendor->contact_mobile,
                'contact_phone' => $this->vendor->contact_landline,
                'contact_email'=> $this->vendor->contact_email,
                'contact_fax' => $this->vendor->contact_fax
            ],
            'location_details' => [
                'province' => $vendorLocTree['province'],
                'district' => $vendorLocTree['district'],
                'municipality' => $vendorLocTree['municipality'],
                "ward" => $vendorLocTree['ward'],
                'landmark' => [
                    'name' => $this->vendor->vendor_landmark,
                    'lat' => $this->vendor->landmark_latitude,
                    'long' => $this->vendor->landmark_longitude
                ]
            ],
            'vendor_user_details' => [
                'name' => $this->vendor->user->name,
                'email' => $this->vendor->user->login_email
            ]

        ];
    }
}
