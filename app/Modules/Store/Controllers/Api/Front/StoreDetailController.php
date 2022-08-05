<?php

namespace App\Modules\Store\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Store\Requests\StoreUpdateApiRequest;
use App\Modules\Store\Requests\StoreUpdateMapLocationRequest;
use App\Modules\Store\Resources\MinimalStoreResource;
use App\Modules\Store\Resources\StoreDetailApiResource;
use App\Modules\Store\Resources\StoreResource;
use App\Modules\Store\Services\StoreService;
use Exception;

class StoreDetailController extends Controller
{
    private $storeService;

    public function __construct(StoreService $storeService)
    {

        $this->storeService = $storeService;
    }

    public function getStoreDetail(){
        try{
            $storeDetail = new StoreResource(auth()->user()->store);
            return sendSuccessResponse('Data Found', $storeDetail);

        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function update(StoreUpdateApiRequest $storeUpdateApiRequest){
        $validatedStore = $storeUpdateApiRequest->validated();
        $storeCode = getAuthStoreCode();
        try{
            $store = $this->storeService->updateStore($validatedStore, $storeCode);

            $store = new StoreDetailApiResource($store);
            return sendSuccessResponse($store->store_name.' Updated Successfully',$store);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function checkHasStore()
    {
        $storeCode = getAuthStoreCode();
        try{
            $store = $this->storeService->findStoreByCode($storeCode);
            $store = new StoreDetailApiResource($store);
            return sendSuccessResponse('data found',$store);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function updateStoreMapLocation(StoreUpdateMapLocationRequest $storeUpdateMapLocationRequest)
    {
        $validatedStore = $storeUpdateMapLocationRequest->validated();

        $storeCode = getAuthStoreCode();
        try{
            $store = $this->storeService->updateStoreMapLocation($validatedStore, $storeCode);

            $store = new MinimalStoreResource($store);
            return sendSuccessResponse($store->store_name.' Updated Successfully',$store);
        }catch(Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
