<?php

namespace App\Modules\AlpasalWarehouse\Services\AdminCurrentStock;

use App\Modules\AlpasalWarehouse\Repositories\AdminCurrentStock\WarehouseWiseStockReportingRepository;
use Exception;

class WarehouseWiseStockReportingService
{
    private $warehouseWiseStockReportingRepository;

    public function __construct(WarehouseWiseStockReportingRepository $warehouseWiseStockReportingRepository)
    {
        $this->warehouseWiseStockReportingRepository = $warehouseWiseStockReportingRepository;
    }

    public function getVendorWiseCurrentStock($warehouseCode,$filterParameters)
    {
        return $this->warehouseWiseStockReportingRepository->getVendorWiseCurrentStock($warehouseCode,$filterParameters);
    }

    public function getVendorWiseProduct($warehouseCode,$vendorCode,$filterParameters)
    {
        return $this->warehouseWiseStockReportingRepository->getVendorWiseProduct($warehouseCode,$vendorCode,$filterParameters);
    }

    public function getWarehouseWiseCurrentStock($filterParameters)
    {
        return $this->warehouseWiseStockReportingRepository->getWarehouseWiseCurrentStock($filterParameters);
    }
}
