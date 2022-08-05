<?php


namespace App\Modules\AlpasalWarehouse\Services\WarehouseStoreGroup;

use App\Modules\AlpasalWarehouse\Repositories\WarehouseStoreGroup\WarehouseStoreGroupDetailRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseStoreGroup\WarehouseStoreGroupRepository;
use App\Modules\Store\Repositories\StoreRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseStoreGroupDetailService
{
    private $warehouseStoreGroupRepository, $warehouseStoreGroupDetailRepository;
    private $storeRepository;

    public function __construct(
        WarehouseStoreGroupRepository $warehouseStoreGroupRepository,
        WarehouseStoreGroupDetailRepository $warehouseStoreGroupDetailRepository,
        StoreRepository $storeRepository
    )
    {
        $this->warehouseStoreGroupRepository = $warehouseStoreGroupRepository;
        $this->warehouseStoreGroupDetailRepository = $warehouseStoreGroupDetailRepository;
        $this->storeRepository = $storeRepository;
    }

    public function massAddWarehouseStoreGroupDetail($warehouseStoreGroupCode, $validatedData)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $validatedData['store_code'] = array_filter($validatedData['store_code']);
            $with = [
                'maxSortedWarehouseStoreGroupDetail',
                'warehouseStoreGroupDetails'
            ];
            $warehouseStoreGroup = $this->warehouseStoreGroupRepository->with($with)->findByWarehouseCode($authWarehouseCode, $warehouseStoreGroupCode);

            $warehouseStoreGroupDetailCodesArr = $warehouseStoreGroup->warehouseStoreGroupDetails->pluck('store_code')->toArray();

            $validStoresCodeArr = $this->storeRepository->select(['store_code'])
                ->getStoresByCode($validatedData['store_code'])->pluck('store_code')->toArray();

            $toBeStoredStores =[];

            $lastMaxSortedOrder = $warehouseStoreGroup->maxSortedWarehouseStoreGroupDetail ? $warehouseStoreGroup->maxSortedWarehouseStoreGroupDetail->max_sort_order : 0;
            foreach ($validatedData['store_code'] as $inputStoreCode) {
                if (!in_array($inputStoreCode, $validStoresCodeArr)) {
                    throw new Exception('Invalid store');
                }
                if (in_array($inputStoreCode, $warehouseStoreGroupDetailCodesArr)) {
                    continue;
                }


                array_push($toBeStoredStores,[
                    'wh_store_group_code' => $warehouseStoreGroup->wh_store_group_code,
                    'store_code' => $inputStoreCode,
                    'sort_order' => ++$lastMaxSortedOrder,
                    'is_active' => 1
                ]);
            }

            if (count($toBeStoredStores) > 0){
                $this->warehouseStoreGroupDetailRepository->createMany($toBeStoredStores);
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function sortWarehouseStoreGroupDetail($warehouseStoreGroupCode,$validatedData){
        try{
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $validatedData['group_detail_codes'] = array_filter($validatedData['group_detail_codes']);
            $with = [
                'warehouseStoreGroupDetails'
            ];
            $warehouseStoreGroup = $this->warehouseStoreGroupRepository->with($with)->findByWarehouseCode($authWarehouseCode, $warehouseStoreGroupCode);

            //$warehouseStoreGroupDetailCodesArr = $warehouseStoreGroup->warehouseStoreGroupDetails->pluck('wh_store_group_detail_code')->toArray();
           // dd($validatedData['group_detail_codes']);
            foreach($warehouseStoreGroup->warehouseStoreGroupDetails as $groupDetail){
                $inputDetailCodeKey = array_search($groupDetail->wh_store_group_detail_code,$validatedData['group_detail_codes']);
                if ($inputDetailCodeKey === false){
                    throw new Exception('Detail code missing: input all the details');
                }
                $this->warehouseStoreGroupDetailRepository->update($groupDetail,[
                   'sort_order' => $inputDetailCodeKey+1
                ]);
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function massDeleteStoreGroupDetail($warehouseStoreGroupCode, $validatedData)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $validatedData['group_detail_codes'] = array_filter($validatedData['group_detail_codes']);
            $with = [
                'warehouseStoreGroupDetails' => function ($query) use ($validatedData) {
                    $query->whereIn('wh_store_group_detail_code', $validatedData['group_detail_codes']);
                }
            ];
            $warehouseStoreGroup = $this->warehouseStoreGroupRepository->with($with)->findByWarehouseCode($authWarehouseCode, $warehouseStoreGroupCode);

            $warehouseStoreGroupDetailCodesArr = $warehouseStoreGroup->warehouseStoreGroupDetails->pluck('wh_store_group_detail_code')->toArray();
            $toBeDeleteDetailCodesArr = [];
            foreach ($validatedData['group_detail_codes'] as $groupDetailCode) {
                if (!in_array($groupDetailCode, $warehouseStoreGroupDetailCodesArr)) {
                    throw new Exception('Invalid store');
                }
                array_push($toBeDeleteDetailCodesArr, $groupDetailCode);
            }

            $this->warehouseStoreGroupDetailRepository->massDelete($toBeDeleteDetailCodesArr);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
