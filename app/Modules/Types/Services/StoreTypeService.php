<?php


namespace App\Modules\Types\Services;

use App\Modules\Types\Repositories\StoreTypeRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class StoreTypeService
{
    protected $storeTypeRepository;

    public function __construct(StoreTypeRepository $storeTypeRepository)
    {
        $this->storeTypeRepository = $storeTypeRepository;
    }

    public function getAllStoreTypes()
    {
        return $this->storeTypeRepository->getAllStoreTypes();
    }

    public function getAllActiveStoreTypes()
    {
        return $this->storeTypeRepository->getAllActiveStoreTypes();
    }


    public function findStoreTypeById($storeTypeId, $with = [])
    {
        return $this->storeTypeRepository->findStoreTypeById($storeTypeId, $with);
    }

    public function findStoreTypeByCode($storeTypeCode, $with = [])
    {
        return $this->storeTypeRepository->findStoreTypeByCode($storeTypeCode, $with);
    }

    public function findOrFailStoreTypeById($storeTypeId, $with = [])
    {
        return $this->storeTypeRepository->findStoreTypeById($storeTypeId, $with);
    }

    public function findOrFailStoreTypeByCode($storeTypeCode, $with = [])
    {
        return $this->storeTypeRepository->findOrFailStoreTypeByCode($storeTypeCode, $with);
    }

    public function storeStoreType($validated)
    {
        DB::beginTransaction();
        try {
            $storeType = $this->storeTypeRepository->storeStoreType($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $storeType;
    }

    public function updateStoreType($validated, $storeTypeCode)
    {
        DB::beginTransaction();
        try {
            $storeType = $this->storeTypeRepository->findOrFailStoreTypeByCode($storeTypeCode);
            $this->storeTypeRepository->updateStoreType($validated, $storeType);
            DB::commit();
            return $storeType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteStoreType($storeTypeCode)
    {
        DB::beginTransaction();
        try {
            $storeType = $this->storeTypeRepository->findOrFailStoreTypeByCode($storeTypeCode);
            if($storeType->is_active == 1)
            {
                throw new Exception('Active Store Type Can not be Deleted');
            }
            $storeType = $this->storeTypeRepository->delete($storeType);
            DB::commit();
            return $storeType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function changeStoreTypeStatus($storeTypeCode,$status)
    {

//        try {
//            $storeType = $this->storeTypeRepository->findOrFailStoreTypeByCode($storeTypeCode);
//            DB::beginTransaction();
//            $storeType->is_active == 1 ? $data['is_active'] = 0 : $data['is_active'] = 1;
//            $this->storeTypeRepository->changeStoreTypeStatus($storeType,$status);
//            DB::commit();
//            return $storeType;
//        } catch (Exception $exception) {
//            DB::rollBack();
//            throw  $exception;
//        }
        try{
            $storeType = $this->storeTypeRepository->findOrFailStoreTypeByCode($storeTypeCode);
            if($status == 'active'){
                $status = 1;
            }elseif($status == 'inactive'){
                $status = 0;
            }

            DB::beginTransaction();
            $storeType = $this->storeTypeRepository->changeStoreTypeStatus($storeType,$status);
            DB::commit();
            return $storeType;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function changeStoreTypeDisplayOrder($storeTypeCode,$sortOrdersToChange)
    {
        try{

            DB::beginTransaction();
            $storeTypes = $this->storeTypeRepository->getAllStoreTypes($storeTypeCode);
            foreach ($storeTypes as $storeType) {
                $storeType->timestamps = false; // To disable update_at field updation
                $id = $storeType->id;

                foreach ($sortOrdersToChange as $order) {
                    if ($order['id'] == $id) {
                        $storeType->update(['sort_order' => $order['position']]);
                    }
                }
            }
            DB::commit();
            return $storeType;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

}
