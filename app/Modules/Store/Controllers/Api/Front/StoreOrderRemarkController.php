<?php

namespace App\Modules\Store\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Modules\Store\Requests\SaveStoreOrderRemarkRequest;
use App\Modules\Store\Resources\StoreOrderRemarkResource;
use App\Modules\Store\Services\StoreOrderRemarkService;
use Illuminate\Support\Facades\DB;
use Exception;

class StoreOrderRemarkController extends Controller
{
   protected $storeOrderRemarkService;
    public function __construct(StoreOrderRemarkService $storeOrderRemarkService)
    {
        $this->storeOrderRemarkService = $storeOrderRemarkService;
    }

    public function saveRemarks(SaveStoreOrderRemarkRequest $request,$storeOrderCode){
        try{
            $validatedData = $request->validated();
            DB::beginTransaction();
            $storeOrderRemark = $this->storeOrderRemarkService->saveRemarksOfStoreOrder($validatedData,$storeOrderCode);
            DB::commit();
            $storeOrderRemark = new StoreOrderRemarkResource($storeOrderRemark);
            return sendSuccessResponse('Remarks added Successfully', $storeOrderRemark);
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }



}
