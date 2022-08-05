<?php

namespace App\Modules\Types\Services;

use App\Modules\Types\Repositories\StoreSizeRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class StoreSizeService
{
    protected $storeSizeRepository;

    public function __construct(StoreSizeRepository $storeSizeRepository)
    {
        $this->storeSizeRepository = $storeSizeRepository;
    }

    public function getAllStoreSizes(){
        return $this->storeSizeRepository->getAllStoreSizes();
    }

    public function getAllActiveStoreSizes(){
        return $this->storeSizeRepository->getAllStoreSizes(true);
    }


    public function findStoreSizeById($storeSizeId, $with = [])
    {
        return $this->storeSizeRepository->findStoreSizeById($storeSizeId, $with);
    }

    public function findStoreSizeByCode($storeSizeCode, $with = [])
    {
        return $this->storeSizeRepository->findStoreSizeByCode($storeSizeCode, $with);
    }

    public function findOrFailStoreSizeById($storeSizeId, $with = [])
    {
        return $this->storeSizeRepository->findStoreSizeById($storeSizeId, $with);
    }

    public function findOrFailStoreSizeByCode($storeSizeCode, $with = [])
    {
        return $this->storeSizeRepository->findOrFailStoreSizeByCode($storeSizeCode, $with);
    }

    public function storeStoreSize($validated)
    {
        DB::beginTransaction();
        try {
            $storeSize = $this->storeSizeRepository->storeStoreSize($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $storeSize;
    }

    public function updateStoreSize($validated, $storeSizeCode)
    {
        DB::beginTransaction();
        try {
            $storeSize = $this->storeSizeRepository->findOrFailStoreSizeByCode($storeSizeCode);
            $this->storeSizeRepository->updateStoreSize($validated, $storeSize);
            DB::commit();
            return $storeSize;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteStoreSize($storeSizeCode)
    {
        DB::beginTransaction();
        try {
            $storeSize = $this->storeSizeRepository->findOrFailStoreSizeByCode($storeSizeCode);
            $storeSize = $this->storeSizeRepository->delete($storeSize);
            DB::commit();
            return $storeSize;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }






}