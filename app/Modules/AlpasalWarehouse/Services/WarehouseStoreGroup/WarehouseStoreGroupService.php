<?php


namespace App\Modules\AlpasalWarehouse\Services\WarehouseStoreGroup;

use App\Modules\AlpasalWarehouse\Repositories\WarehouseStoreGroup\WarehouseStoreGroupDetailRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseStoreGroup\WarehouseStoreGroupRepository;
use App\Modules\Store\Repositories\StoreRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseStoreGroupService
{
    private $warehouseStoreGroupRepository,$warehouseStoreGroupDetailRepository,$storeRepository;

    public function __construct(
        WarehouseStoreGroupRepository $warehouseStoreGroupRepository,
        WarehouseStoreGroupDetailRepository $warehouseStoreGroupDetailRepository,
        StoreRepository $storeRepository
    )
    {
        $this->warehouseStoreGroupRepository = $warehouseStoreGroupRepository;
        $this->storeRepository = $storeRepository;
        $this->warehouseStoreGroupDetailRepository = $warehouseStoreGroupDetailRepository;
    }

    public function saveWarehouseStoreGroup($validatedData)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $validatedData['store_code'] = array_filter($validatedData['store_code']);
            $warehouseStoreGroup=$this->warehouseStoreGroupRepository->create([
                'warehouse_code' => $authWarehouseCode,
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? '',
                'group_basis' => 'route-default',
                'is_active' => 1
            ]);
            $validStoresCodeArr =$this->storeRepository->select(['store_code'])
                ->getStoresByCode($validatedData['store_code'])->pluck('store_code')->toArray();

            $toBeStoredStores =[];
            foreach ($validStoresCodeArr as $key=>$inputStoreCode){
                if (!in_array($inputStoreCode,$validStoresCodeArr)){
                    throw new Exception('Invalid store');
                }
                array_push($toBeStoredStores,[
                   'wh_store_group_code' => $warehouseStoreGroup->wh_store_group_code,
                   'store_code' => $inputStoreCode,
                  // 'sort_order' => $validatedData['sort_order'][$key] ?? $key+1,
                   'sort_order' => $key+1,
                   'is_active' => 1
                ]);
            }
            $this->warehouseStoreGroupDetailRepository->createMany($toBeStoredStores);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateWarehouseStoreGroup($validatedData,$warehouseStoreGroupCode)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $warehouseStoreGroup = $this->warehouseStoreGroupRepository->findByWarehouseCode($authWarehouseCode,$warehouseStoreGroupCode);
            if(!$warehouseStoreGroup){
                throw new Exception('Stores group not found');
            }
            $this->warehouseStoreGroupRepository->update($warehouseStoreGroup,$validatedData);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteWarehouseStoreGroup($warehouseStoreGroupCode)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $warehouseStoreGroup = $this->warehouseStoreGroupRepository->findByWarehouseCode($authWarehouseCode,$warehouseStoreGroupCode);
            if(!$warehouseStoreGroup){
                throw new Exception('Stores group not found');
            }
            $this->warehouseStoreGroupRepository->delete($warehouseStoreGroup);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function toggleWarehouseStoreGroupStatus($warehouseStoreGroupCode)
    {
        try {
            DB::beginTransaction();
            $authWarehouseCode = getAuthWarehouseCode();
            $warehouseStoreGroup = $this->warehouseStoreGroupRepository->findByWarehouseCode($authWarehouseCode,$warehouseStoreGroupCode);
            if(!$warehouseStoreGroup){
                throw new Exception('Stores group not found');
            }
            $validatedData['is_active'] = $warehouseStoreGroup->is_active == 1 ? 0:1;
            $this->warehouseStoreGroupRepository->update($warehouseStoreGroup,$validatedData);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
