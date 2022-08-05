<?php

namespace App\Modules\Types\Controllers\Api\Frontend;

use App\Modules\Types\Resources\StoreType\StoreTypeNewResource;
use App\Modules\Types\Services\StoreTypeService;
use Exception;

class StoreTypeApiController
{
    private $storeTypeService;
    public function __construct(StoreTypeService $storeTypeService)
    {
        $this->storeTypeService = $storeTypeService;
    }

    public function index()
    {
        try{
            $storeTypes = $this->storeTypeService->getAllActiveStoreTypes();
            $storeTypes = StoreTypeNewResource::collection($storeTypes);
            return sendSuccessResponse('Data Found!', $storeTypes);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
