<?php

namespace App\Modules\Store\Services;

use App\Modules\Store\Repositories\StoreOrderRemarkRepository;
use App\Modules\Store\Repositories\StoreOrderRepository;
use Exception;

class StoreOrderRemarkService
{
    protected $storeOrderRepository;
    protected $storeOrderRemarkRepository;
    public function __construct(
        StoreOrderRepository $storeOrderRepository,
        StoreOrderRemarkRepository $storeOrderRemarkRepository
    ){
        $this->storeOrderRepository = $storeOrderRepository;
        $this->storeOrderRemarkRepository = $storeOrderRemarkRepository;
    }

    public function saveRemarksOfStoreOrder($validatedData, $storeOrderCode){
        try{
            //dd(getAuthStoreCode());
            $storeOrder = $this->storeOrderRepository->findOrFailByCode($storeOrderCode);
            if($storeOrder->store_code  != getAuthStoreCode()){
               throw new Exception('Invalid Store Order :(');
            }
            $validatedData['store_order_code'] = $storeOrderCode;
            $validatedData['created_by'] = getAuthUserCode();
            $storeOrderRemark = $this->storeOrderRemarkRepository->saveRemarks($validatedData);
            return $storeOrderRemark;
        }catch (Exception $exception){
           throw $exception;
        }
    }

}
