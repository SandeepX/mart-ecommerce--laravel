<?php

namespace App\Modules\Vendor\Services;

use App\Modules\Vendor\Repositories\VendorRepository;
use App\Modules\Vendor\Repositories\VendorWarehouseRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VendorWarehouseService 
{   
    private $vendorWarehouseRepository;
    private $vendorRepository;
    public function __construct(VendorWarehouseRepository $vendorWarehouseRepository, VendorRepository $vendorRepository)
    {
        $this->vendorWarehouseRepository = $vendorWarehouseRepository;
        $this->vendorRepository = $vendorRepository;
    }

    public function getAllVendorWarehouses($vendorCode){
        $vendor = $this->vendorRepository->findOrFailVendorByCode($vendorCode);
        return $this->vendorWarehouseRepository->getAllVendorWarehouses($vendor);
    }

    public function findVendorWarehouseByCode($vendorCode, $vendorWarehouseCode){
        try{
            $this->checkVendorAndWarehouse($vendorCode, $vendorWarehouseCode);
            return $this->vendorWarehouseRepository->findVendorWarehouseByCode($vendorWarehouseCode);
        }catch(Exception $exception){
            throw($exception);
        }
    }

    public function storeVendorWarehouse($vendorCode, $validatedVendorWarehouse){
        DB::beginTransaction();
        try{
            $vendor = $this->vendorRepository->findOrFailVendorByCode($vendorCode);
            $vendorWarehouse = $this->vendorWarehouseRepository->storeVendorWarehouse($vendor, $validatedVendorWarehouse);
            DB::commit();
            return $vendorWarehouse;
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }

    public function updateVendorWarehouse($vendorCode, $validatedVendorWarehouse, $vendorWarehouseCode){
        DB::beginTransaction();
        try{
            $this->checkVendorAndWarehouse($vendorCode, $vendorWarehouseCode);
            $vendorWarehouse = $this->vendorWarehouseRepository->findVendorWarehouseByCode($vendorWarehouseCode);
            $vendorWarehouse = $this->vendorWarehouseRepository->updateVendorWarehouse($vendorWarehouse, $validatedVendorWarehouse);
            DB::commit();
            return $vendorWarehouse;
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }

    public function deleteVendorWarehouse($vendorCode, $vendorWarehouseCode){
        DB::beginTransaction();
        try{
            $this->checkVendorAndWarehouse($vendorCode, $vendorWarehouseCode);
            $vendorWarehouse = $this->vendorWarehouseRepository->findVendorWarehouseByCode($vendorWarehouseCode);
            $vendorWarehouse = $this->vendorWarehouseRepository->deleteVendorWarehouse($vendorWarehouse);
            DB::commit();
            return $vendorWarehouse;
        }catch(Exception $exception){
            DB::rollBack();
            throw($exception);
        }
    }

    private function checkVendorAndWarehouse($vendorCode, $vendorWarehouseCode){
        $vendor = $this->vendorRepository->findOrFailVendorByCode($vendorCode);
        $vendorWarehouseCodes = $vendor->vendorWarehouses()->pluck('vendor_warehouse_code')->toArray();
        if(!in_array($vendorWarehouseCode, $vendorWarehouseCodes)){
            throw new NotFoundHttpException('Sorry ! Not Found !', null, 404);
        }
    }
    
}