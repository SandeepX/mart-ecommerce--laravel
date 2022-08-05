<?php

namespace App\Modules\Store\Controllers\Api\Front\StoreRegistrationApiFormField;


use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Location\Resources\LocationHierarchy\LocationHierarchyApiResource;
use App\Modules\Types\Models\CompanyType;
use App\Modules\Types\Models\RegistrationType;
use App\Modules\Types\Models\StoreSize;
use App\Modules\Types\Models\StoreType;
use App\Modules\Types\Models\UserType;

use App\Modules\Types\Resources\CompanyType\CompanyTypeApiResource;
use App\Modules\Types\Resources\RegistrationType\RegistrationTypeApiResource;
use App\Modules\Types\Resources\StoreSize\StoreSizeApiResource;
use App\Modules\Types\Resources\StoreType\StoreTypeApiResource;
use App\Modules\Types\Resources\UserType\UserTypeApiResourceTypeResource;

use App\Http\Controllers\Controller;

class StoreRegistrationFormFieldController extends Controller
{
    public function getAllCompanyType(){
        return CompanyTypeApiResource::collection(CompanyType::where('is_active', 1)->get());
    }
    public function getAllLocation(){
        return LocationHierarchyApiResource::collection(LocationHierarchy::all());
    }
    public function getAllRegistration(){
        return RegistrationTypeApiResource::collection(RegistrationType::where('is_active',1)->get());
    }
    public function getAllUserType(){
        return UserTypeApiResourceTypeResource::collection(UserType::where('is_active',1)->get());
    }
    public function getAllStoreSize(){
        return StoreSizeApiResource::collection(StoreSize::where('is_active',1)->get());
    }
    public function getAllStoreType(){
        return StoreTypeApiResource::collection(StoreType::where('is_active',1)->get());
    }

}
