<?php

namespace App\Modules\Store\Controllers\Api\Front\StoreRegistration;


use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Location\Resources\LocationHierarchy\LocationHierarchyApiResource;
use App\Modules\Store\Requests\RegisterApiRequest\StoreCreateApiRequest;
use App\Modules\Store\Requests\RegisterApiRequest\StoreUserCreateApiRequest;
use App\Modules\Store\Resources\MinimalStoreResource;

use App\Modules\Store\Resources\StoreAccountStatusResource;
use App\Modules\Store\Services\StoreService;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Auth;


class
StoreRegistrationController extends Controller
{

    private $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService= $storeService;
    }

    public function getStoreRegistrationFormResources(){
        return
            [
                'company_types' => CompanyTypeApiResource::collection(CompanyType::where('is_active', 1)->get()),
                'location_hierarchy' => LocationHierarchyApiResource::collection(LocationHierarchy::all()),
                'registration_types' => RegistrationTypeApiResource::collection(RegistrationType::where('is_active',1)->get()),
                'user_types' => UserTypeApiResourceTypeResource::collection(UserType::where('is_active',1)->get()),
                'store_sizes' =>StoreSizeApiResource::collection(StoreSize::where('is_active',1)->get()),
                'store_types' => StoreTypeApiResource::collection(StoreType::where('is_active',1)->get()),
            ];
    }


    public function createUserWithStoreFromApi(StoreCreateApiRequest $storeRequest, StoreUserCreateApiRequest $userRequest)
    {
        try{
            $validatedStore = $storeRequest->validated();
            $validatedUser = $userRequest->validated();
            $this->storeService->createUserWithStoreFromApi($validatedStore,$validatedUser);
            return sendSuccessResponse('New Store Registered Successfully');
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function findStoreAccountStatus(){
         try{
             $store = getAuthStore();
             return sendSuccessResponse(
                 'Store Account Status Fetched',
                 new StoreAccountStatusResource($store)
             );
         }catch(\Exception $exception){
             return sendErrorResponse($exception->getMessage(), $exception->getCode());
         }
    }
}
