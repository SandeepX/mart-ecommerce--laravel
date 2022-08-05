<?php

namespace App\Modules\Types\Services;

use App\Modules\Types\Repositories\VendorTypeRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class VendorTypeService
{
    protected $vendorTypeRepository;

    public function __construct(VendorTypeRepository $vendorTypeRepository)
    {
        $this->vendorTypeRepository = $vendorTypeRepository;
    }
    public function getAllVendorTypes(){
        return $this->vendorTypeRepository->getAllVendorTypes();
    }



    public function findVendorTypeById($vendorTypeId, $with = [])
    {
        return $this->vendorTypeRepository->findVendorTypeById($vendorTypeId, $with);
    }

    public function findVendorTypeByCode($vendorTypeCode, $with = [])
    {
        return $this->vendorTypeRepository->findVendorTypeByCode($vendorTypeCode, $with);
    }

    public function findOrFailVendorTypeById($vendorTypeId, $with = [])
    {
        return $this->vendorTypeRepository->findVendorTypeById($vendorTypeId, $with);
    }

    public function findOrFailVendorTypeByCode($vendorTypeCode, $with = [])
    {
        return $this->vendorTypeRepository->findOrFailVendorTypeByCode($vendorTypeCode, $with);
    }

    public function storeVendorType($validated)
    {
        DB::beginTransaction();
        try {
            $vendorType = $this->vendorTypeRepository->storeVendorType($validated);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $vendorType;
    }

    public function updateVendorType($validated, $vendorTypeCode)
    {
        DB::beginTransaction();
        try {
            $vendorType = $this->vendorTypeRepository->findOrFailVendorTypeByCode($vendorTypeCode);
            $this->vendorTypeRepository->updateVendorType($validated, $vendorType);
            DB::commit();
            return $vendorType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }

    public function deleteVendorType($vendorTypeCode)
    {
        DB::beginTransaction();
        try {
            $vendorType = $this->vendorTypeRepository->findOrFailVendorTypeByCode($vendorTypeCode);
            $vendorType = $this->vendorTypeRepository->delete($vendorType);
            DB::commit();
            return $vendorType;

        } catch (Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
    }






}