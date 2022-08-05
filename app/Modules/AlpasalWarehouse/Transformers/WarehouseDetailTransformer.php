<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/2/2020
 * Time: 5:37 PM
 */

namespace App\Modules\AlpasalWarehouse\Transformers;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Location\Repositories\LocationHierarchyRepository;

class WarehouseDetailTransformer
{
    private $warehouse;

    public function __construct(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function transform(){
        $warehouseLocation = (new LocationHierarchyRepository())->getLocationByCode($this->warehouse->location_code);
        $warehouseLocTree = (new LocationHierarchyRepository())->getLocationPath($warehouseLocation);

        return [
            'warehouse_name' => $this->warehouse->warehouse_name,
            'warehouse_code' => $this->warehouse->warehouse_code,
            'warehouse_type' => $this->warehouse->warehouseType->warehouse_type_name,
            'pan_vat_type' => $this->warehouse->pan_vat_type,
            'pan_vat_no' => $this->warehouse->pan_vat_no,
            'remarks' => $this->warehouse->remarks,
            'warehouse_logo'=>photoToUrl($this->warehouse->warehouse_logo,asset($this->warehouse->getLogoUploadPath())),
            'contact_name'=>$this->warehouse->contact_name,
            'contact_email'=>$this->warehouse->contact_email,
            'contact_phone_1'=>$this->warehouse->contact_phone_1,
            'contact_phone_2'=>$this->warehouse->contact_phone_2,
            'location_details' => [
                'province' => $warehouseLocTree['province'],
                'district' => $warehouseLocTree['district'],
                'municipality' => $warehouseLocTree['municipality'],
                "ward" => $warehouseLocTree['ward'],
                'landmark' => [
                    'name' => $this->warehouse->landmark_name,
                    'lat' => $this->warehouse->latitude,
                    'long' => $this->warehouse->longitude
                ]
            ],
            'warehouse_user_details' => $this->warehouse->warehouseAdmins->map(function ($warehouseUser){

                return [
                    'name'=>$warehouseUser->user->name,
                    'email' => $warehouseUser->user->login_email
                ];
            }),

        ];
    }

}
