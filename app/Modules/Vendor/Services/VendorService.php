<?php

namespace App\Modules\Vendor\Services;

use App\Modules\Vendor\Repositories\VendorRepository;
use Illuminate\Support\Facades\DB;

class VendorService
{
    private $vendorRepository;

    public function __construct(VendorRepository $vendorRepository){
       $this->vendorRepository = $vendorRepository;
    }


    public function getAllVendors($with = []){
        return $this->vendorRepository->getAllVendors($with);
    }

    public function getAllActiveVendors($with=[]){
        return $this->vendorRepository->getAllVendorsByActiveStatus(true,$with);
    }

    public function findVendorByCode($vendorCode){
        return $this->vendorRepository->findVendorByCode($vendorCode);
    }

    public function findVendorByID($vendorID){
        return $this->vendorRepository->findVendorByID($vendorID);
    }

    public function findVendorBySlug($vendorSlug){
        return $this->vendorRepository->findVendorBySlug($vendorSlug);
    }

    public function findOrFailVendorById($VendorId)
    {
        return $this->vendorRepository->findOrFailVendorById($VendorId);
    }


    public function findOrFailVendorByCode($vendorCode)
    {
        return $this->vendorRepository->findOrFailVendorByCode($vendorCode);
    }


    public function findOrFailVendorBySlug($vendorSlug)
    {
        return $this->vendorRepository->findOrFailVendorBySlug($vendorSlug);
    }


    public function storeVendorDetails($validated){
        try {
            $vendor = $this->vendorRepository->create($validated);

        } catch (\Exception $exception) {

            throw  $exception;
        }
        return $vendor;
    }


    public function updateVendorDetails($validated, $vendorCode)
    {
        DB::beginTransaction();

        try {
            $vendor = $this->findVendorByCode($vendorCode);
            $vendor = $this->vendorRepository->update($validated, $vendor);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $vendor;
    }

    public function deleteVendorDetails($vendorCode)
    {
        DB::beginTransaction();
        try {
            $vendor = $this->findVendorByCode($vendorCode);
            $vendor = $this->vendorRepository->delete($vendor);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $vendor;
    }


    public function changeVendorStatus($vendorCode,$status)
    {
        try {
            $vendor = $this->vendorRepository->findVendorByCode($vendorCode);
            if ($status == 'active') {
                $status = 1;
            } elseif ($status == 'inactive') {
                $status = 0;
            }
            //dd($status);
            DB::beginTransaction();
            $vendor = $this->vendorRepository->changeVendorStatus($vendor, $status);
            DB::commit();
            return $vendor;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getWarehouseWiseVendors($warehouseCode,$with=[]){
        return $this->vendorRepository->getWarehouseWiseVendors($warehouseCode,$with);
    }
}
