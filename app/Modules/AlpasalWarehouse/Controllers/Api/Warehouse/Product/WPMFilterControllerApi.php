<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Api\Warehouse\Product;

use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Services\ProductCollection\WarehouseProductCollectionService;
use App\Modules\AlpasalWarehouse\Services\StockTransfer\WarehouseStockTransferService;
use App\Modules\AlpasalWarehouse\Services\WarehouseProductStockService;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Store\Services\StoreService;
use Illuminate\Http\Request;

class WPMFilterControllerApi extends Controller
{
    protected $warehouseStockTransferService,$warehouseProductStockService;

    public function __construct(WarehouseStockTransferService $warehouseStockTransferService,
                                WarehouseProductStockService $warehouseProductStockService

    )
    {
        $this->warehouseStockTransferService = $warehouseStockTransferService;
        $this->warehouseProductStockService = $warehouseProductStockService;
    }

    public function getWhProducts(Request $request,$stockTransferCode)
    {
        try{
            //$with = ['warehouseProductMaster'];
            $response = [];
            $WPMCode = $request->warehouse_product_master_code;
            $productStock = WarehouseProductStockHelper::findCurrentProductStockInWarehouse($WPMCode);
            $product = $this->warehouseStockTransferService->getProductByWarehouseProductMasterCode($request->warehouse_product_master_code, $stockTransferCode);
            $productPackages = ProductUnitPackagingHelper::getAvailableProductPackagingTypes($product->product_code,$product->product_variant_code);
            $response = [
                'wpm_code'=>$product->warehouse_product_master_code,
                'product_name'=>$product->product_name,
                'product_code'=>$product->product_code,
                'product_variant_code'=>$product->product_variant_code,
                'product_varient_name'=>$product->productVariant->product_variant_name,
                'stock'=>$productStock->current_stock,
                'vendor_price'=>$product->vendor_price,
                'package_details'=>collect($productPackages)->map(function($product){
                    return $product;
                })
            ];
            $packageDetails = $response['package_details'];
            //$response['html'] = view('AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.includes.product-row',
              //  compact('product', 'WPMCode','packageDetails'))->render();
            return response()->json($response);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }
}
