<?php

namespace App\Modules\AlpasalWarehouse\Services\CurrentStock;

use App\Modules\AlpasalWarehouse\Repositories\CurrentStock\VendorWiseStockReportingRepository;
use Exception;

class VendorWiseStockReportingService
{
    private $vendorWiseStockReportingRepository;

    public function __construct(VendorWiseStockReportingRepository $vendorWiseStockReportingRepository)
    {
        $this->vendorWiseStockReportingRepository = $vendorWiseStockReportingRepository;
    }

   public function getVendorWiseCurrentStock($warehouseCode,$filterParameters)
   {
       return $this->vendorWiseStockReportingRepository->getVendorWiseCurrentStock($warehouseCode,$filterParameters);
   }

    public function getVendorWiseProduct($warehouseCode,$vendorCode,$filterParameters)
    {
        return $this->vendorWiseStockReportingRepository->getVendorWiseProduct($warehouseCode,$vendorCode,$filterParameters);
    }

}
