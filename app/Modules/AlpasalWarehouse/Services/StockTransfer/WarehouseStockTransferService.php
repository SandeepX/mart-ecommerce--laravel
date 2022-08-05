<?php


namespace App\Modules\AlpasalWarehouse\Services\StockTransfer;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductPriceHelper;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransfer;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Repositories\StockTransfer\WarehouseReceiveStockTransferDetailsRepository;
use App\Modules\AlpasalWarehouse\Repositories\StockTransfer\WarehouseStockTransferRepository;
use App\Modules\AlpasalWarehouse\Repositories\StockTransfer\WarehouseTransferLossRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductPriceRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductStockRepository;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class WarehouseStockTransferService
{
    private $warehouseStockTransferRepository,$warehouseProductStockRepository;
    private $vendorProductPackagingHistoryRepository;
    private $warehouseProductMasterRepository;
    private $warehouseReceiveStockTransferDetailsRepository;
    private $warehouseTransferLossRepository;
    private $warehouseProductPriceRepository;

    public function __construct(
        WarehouseStockTransferRepository $warehouseStockTransferRepository,
        WarehouseProductStockRepository $warehouseProductStockRepository,
        VendorProductPackagingHistoryRepository $vendorProductPackagingHistoryRepository,
        WarehouseProductMasterRepository $warehouseProductMasterRepository,
        WarehouseReceiveStockTransferDetailsRepository $warehouseReceiveStockTransferDetailsRepository,
        WarehouseTransferLossRepository $warehouseTransferLossRepository,
        WarehouseProductPriceRepository $warehouseProductPriceRepository
    ){
        $this->warehouseStockTransferRepository = $warehouseStockTransferRepository;
        $this->warehouseProductStockRepository = $warehouseProductStockRepository;
        $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
        $this->warehouseProductMasterRepository = $warehouseProductMasterRepository;
        $this->warehouseReceiveStockTransferDetailsRepository = $warehouseReceiveStockTransferDetailsRepository;
        $this->warehouseTransferLossRepository = $warehouseTransferLossRepository;
        $this->warehouseProductPriceRepository = $warehouseProductPriceRepository;
    }

    public function getAllWarehouseStockTransfer($filterParameters, $paginateBy = null)
    {
        return $this->warehouseStockTransferRepository->getAllWarehouseStockTransfer($filterParameters, $paginateBy);
    }

    public function addWarehouseStockTransfer($validatedStockTransferWarehouse)
    {
        try {
            DB::beginTransaction();
            $warehouseStockTransfer = $this->warehouseStockTransferRepository->addWarehouseStockTransfer($validatedStockTransferWarehouse);
            DB::commit();
            return $warehouseStockTransfer;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getWarehouseStockTransferByCode($warehouseStockTransferCode,  $with = [], $select)
    {
        try {
            $warehouseStockTransfer = $this->warehouseStockTransferRepository->getWarehouseStockTransferByCode($warehouseStockTransferCode, $with, $select);
            return $warehouseStockTransfer;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getWarehouseProducts($filterParameters, $paginated)
    {
        try {
            $warehouseProducts = $this->warehouseStockTransferRepository->getWarehouseProducts($filterParameters, $paginated);
            return $warehouseProducts;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getProductByWarehouseProductMasterCode($warehouse_product_master_code, $stockTransferCode)
    {
        try{
           return $this->warehouseStockTransferRepository->getProductByWarehouseProductMasterCode($warehouse_product_master_code, $stockTransferCode);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
//    public function getProductByProductCode($product_code, $product_variant_code)
//    {
//        try{
//           return $this->warehouseStockTransferRepository->getProductByProductCode($product_code, $product_variant_code);
//        } catch (Exception $exception) {
//            throw $exception;
//        }
//    }

    public function storeStockTransferProductsDetails($products, $stockTransferCode, $status = null)
    {
        try{
            DB::beginTransaction();
            $stockTransferDetails = $this->warehouseStockTransferRepository->storeStockTransferProductsDetails($products, $stockTransferCode, $status);
            DB::commit();
            return $stockTransferDetails;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getWarehouseStockTransferProductsDetails( $stockTransferCode, $filterParameters = [], $paginateBy = null)
    {
        try {
            return $this->warehouseStockTransferRepository->getWarehouseStockTransferProductsDetailsByCode( $stockTransferCode, $filterParameters, $paginateBy);
        }catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getAllReceivedWarehouseStockTransfers($filterParameters = [], $paginateBy = null)
    {
        try{
            return $this->warehouseStockTransferRepository->getAllReceivedWarehouseStockTransfers($filterParameters, $paginateBy);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateWarehouseReceivedProductsQuantity($validatedData, $stockTransferCode)
    {
        try{

            $stockTransferMaster = $this->warehouseStockTransferRepository->getWarehouseStockTransferByCode($stockTransferCode);

            if($stockTransferMaster->status != 'sent' ){
              throw new Exception('This Transfer Cannot be received because it is not in sent Status!');
            }
            DB::beginTransaction();
            foreach($validatedData['warehouse_product_master_code'] as $key => $warehouseProductMasterCode){
                $with = ['product','productVariant'];
                $sourceWarehouseProductMaster =$this->warehouseProductMasterRepository->findOrFailProductByCode(
                    $warehouseProductMasterCode,$stockTransferMaster->source_warehouse_code,$with
                );

                $productPackagingHistory = $this->warehouseStockTransferRepository->getProductPackageHistoryByTransferAndWarehouseProductCode(
                                                                                            $stockTransferCode,
                                                                                            $warehouseProductMasterCode
                                                                                       );

                $totalMicroStockTransferQuantity =  $this->warehouseStockTransferRepository->getTotalSendingMicroOrderedQtyByTransferAndWarehouseProductCode(
                                                                                           $stockTransferCode,
                                                                                           $warehouseProductMasterCode
                                                                                         );

                $receivedMicroQuantity =isset($validatedData['micro_received_quantity'][$key])?
                    $validatedData['micro_received_quantity'][$key] : 0;
                $receivedUnitQuantity =isset($validatedData['unit_received_quantity'][$key])?
                    $validatedData['unit_received_quantity'][$key] : 0;
                $receivedMacroQuantity =isset($validatedData['macro_received_quantity'][$key])?
                    $validatedData['macro_received_quantity'][$key] : 0;
                $receivedSuperQuantity =isset($validatedData['super_received_quantity'][$key])?
                    $validatedData['super_received_quantity'][$key] : 0;

                $allReceivedPackages = [];
                $receivedMicroMicroQuantity = 0;
                $receivedUnitMicroQuantity = 0;
                $receivedMacroMicroQuantity = 0;
                $receivedSuperMicroQuantity = 0;

                if ($receivedSuperQuantity){
                        $receivedSuperMicroQuantity =ProductUnitPackagingHelper::convertToMicroPackagingUnitQuantity(
                            $productPackagingHistory,$receivedSuperQuantity,'SUPER_UNIT_TYPE'
                        );
                        $allReceivedPackages[$productPackagingHistory->super_unit_code] = [
                            'package_quantity' => $receivedSuperQuantity,
                            'micro_quantity' => $receivedSuperMicroQuantity
                        ];
                }

                if ($receivedMacroQuantity){
                    $receivedMacroMicroQuantity =ProductUnitPackagingHelper::convertToMicroPackagingUnitQuantity(
                        $productPackagingHistory,$receivedMacroQuantity,'MACRO_UNIT_TYPE'
                    );
                    $allReceivedPackages[$productPackagingHistory->macro_unit_code] = [
                        'package_quantity' => $receivedMacroQuantity,
                        'micro_quantity' => $receivedMacroMicroQuantity
                    ];
                }
                if ($receivedUnitQuantity){
                    $receivedUnitMicroQuantity =ProductUnitPackagingHelper::convertToMicroPackagingUnitQuantity(
                        $productPackagingHistory,$receivedUnitQuantity,'UNIT_TYPE'
                    );
                    $allReceivedPackages[$productPackagingHistory->unit_code] = [
                        'package_quantity' => $receivedUnitQuantity,
                        'micro_quantity' => $receivedUnitMicroQuantity
                    ];
                }
                if ($receivedMicroQuantity){
                    $receivedMicroMicroQuantity =ProductUnitPackagingHelper::convertToMicroPackagingUnitQuantity(
                        $productPackagingHistory,$receivedMicroQuantity,'MICRO_UNIT_TYPE'
                    );
                    $allReceivedPackages[$productPackagingHistory->micro_unit_code] = [
                        'package_quantity' => $receivedMicroQuantity,
                        'micro_quantity' => $receivedMicroQuantity
                    ];
                }

                 $receivedQuantity = $receivedMicroMicroQuantity+$receivedUnitMicroQuantity
                    +$receivedMacroMicroQuantity+$receivedSuperMicroQuantity;

                if ($receivedQuantity > $totalMicroStockTransferQuantity) {
                    throw new Exception('Received quantity cannot be greater than transferd quantity of product '
                        .$sourceWarehouseProductMaster->product->product_name.' '
                        .(($sourceWarehouseProductMaster->productVariant)? $sourceWarehouseProductMaster->productVariant->product_variant_name:''));
                }

                $destinationWarehouseProduct = $this->warehouseProductMasterRepository->findProductByWarehouseCode(
                                              $stockTransferMaster->destination_warehouse_code,
                                              $sourceWarehouseProductMaster->product_code,
                                              $sourceWarehouseProductMaster->product_variant_code
                                            );

                if(!$destinationWarehouseProduct){
                    $destinationWarehouseProductData = [];
                    $destinationWarehouseProductData['warehouse_code'] =  $stockTransferMaster->destination_warehouse_code;
                    $destinationWarehouseProductData['product_code'] =  $sourceWarehouseProductMaster->product_code;
                    $destinationWarehouseProductData['product_variant_code'] = $sourceWarehouseProductMaster->product_variant_code;
                    $destinationWarehouseProductData['vendor_code'] = $sourceWarehouseProductMaster->vendor_code;
                    $destinationWarehouseProduct = $this->warehouseProductMasterRepository
                        ->storeWarehouseProduct($destinationWarehouseProductData);

                    $warehouseProductPrice = WarehouseProductPriceHelper::findWarehouseProductPriceByWarehouseProductCode(
                        $sourceWarehouseProductMaster->warehouse_product_master_code
                    );

                    if($warehouseProductPrice){
                        $destinationWarehouseProductPriceData = [];
                        $destinationWarehouseProductPriceData['warehouse_product_master_code'] = $destinationWarehouseProduct
                            ->warehouse_product_master_code;
                        $destinationWarehouseProductPriceData['mrp'] = $warehouseProductPrice->mrp;
                        $destinationWarehouseProductPriceData['admin_margin_type'] = $warehouseProductPrice->admin_margin_type;
                        $destinationWarehouseProductPriceData['admin_margin_value'] = $warehouseProductPrice->admin_margin_value;
                        $destinationWarehouseProductPriceData['wholesale_margin_type'] = $warehouseProductPrice->wholesale_margin_type;
                        $destinationWarehouseProductPriceData['wholesale_margin_value'] = $warehouseProductPrice->wholesale_margin_value;
                        $destinationWarehouseProductPriceData['retail_margin_value'] = $warehouseProductPrice->retail_margin_type;
                        $destinationWarehouseProductPriceData['retail_margin_value'] = $warehouseProductPrice->retail_margin_value;
                        $this->warehouseProductPriceRepository->updateProductPrice($destinationWarehouseProduct,$destinationWarehouseProductPriceData);
                    }
                }

                $this->storeReceivedStockTransferDetails(
                                    $stockTransferMaster,
                                    $destinationWarehouseProduct,
                                    $productPackagingHistory,
                                    $allReceivedPackages
                                );
                $transferLossQuantity = $totalMicroStockTransferQuantity - $receivedQuantity;
                if($transferLossQuantity > 0){
                    $this->storeTransferLossDetails(
                                    $stockTransferMaster,
                                    $sourceWarehouseProductMaster,
                                    $productPackagingHistory,
                                    $transferLossQuantity
                              );
                }
            }
            $stockTransferMaster = $this->warehouseStockTransferRepository->updateStatusOfStockTransferMaster(
                  $stockTransferMaster,['status'=>'received']
              );

            DB::commit();
            return $stockTransferMaster;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function storeReceivedStockTransferDetails(
        WarehouseStockTransfer $warehouseStockTransfer,
        WarehouseProductMaster $warehouseProductMaster,
        ProductPackagingHistory $productPackagingHistory,
        array $allReceivedPackages
    ){
            foreach ($allReceivedPackages as $key => $receivedPackage){
                $validatedReceivedStockTransferData = [];
                $validatedReceivedStockTransferData['stock_transfer_master_code'] = $warehouseStockTransfer->stock_transfer_master_code;
                $validatedReceivedStockTransferData['warehouse_product_master_code'] = $warehouseProductMaster->warehouse_product_master_code;
                $validatedReceivedStockTransferData['received_quantity'] = $receivedPackage['micro_quantity'];
                $validatedReceivedStockTransferData['package_quantity'] = $receivedPackage['package_quantity'];
                $validatedReceivedStockTransferData['package_code'] = $key;
                $validatedReceivedStockTransferData['product_packaging_history_code'] = $productPackagingHistory->product_packaging_history_code;
                $validatedReceivedStockTransferData['created_by'] = getAuthUserCode();
                $this->warehouseReceiveStockTransferDetailsRepository->save($validatedReceivedStockTransferData);

                $validatedReceivedStock = [];
                $validatedReceivedStock['warehouse_product_master_code'] = $warehouseProductMaster->warehouse_product_master_code;
                $validatedReceivedStock['quantity'] = $receivedPackage['micro_quantity'];
                $validatedReceivedStock['package_qty'] = $receivedPackage['package_quantity'];
                $validatedReceivedStock['package_code'] = $key;
                $validatedReceivedStock['action'] = 'received-stock-transfer';
                $validatedReceivedStock['product_packaging_history_code'] = $productPackagingHistory->product_packaging_history_code;
                $validatedReceivedStock['reference_code'] =  $warehouseStockTransfer->stock_transfer_master_code;
                $warehouseProductStock =   $this->warehouseProductStockRepository->storeWarehouseProductStock(
                    $validatedReceivedStock
                );
                //update current stock in warehouse product master
                $currentStock = $warehouseProductMaster->current_stock + (int) $warehouseProductStock->quantity;
                $this->warehouseProductMasterRepository->updateWarehouseProductCurrentStock($warehouseProductMaster,$currentStock);
            }
    }

    private function storeTransferLossDetails(
        WarehouseStockTransfer $warehouseStockTransfer,
        WarehouseProductMaster $warehouseProductMaster,
        ProductPackagingHistory $productPackagingHistory,
        $lossQty
    ){
        $productPackagingFormatter = new ProductPackagingFormatter();
        $arr =[];
        if ($productPackagingHistory){
            if ($productPackagingHistory->micro_unit_code){
                $arr[1] =$productPackagingHistory->micro_unit_code;
            }
            if ($productPackagingHistory->unit_code){
                $arrKey = intval($productPackagingHistory->micro_to_unit_value);
                $arr[$arrKey] =$productPackagingHistory->unit_code;
            }
            if ($productPackagingHistory->macro_unit_code){
                $arrKey = intval($productPackagingHistory->micro_to_unit_value *
                    $productPackagingHistory->unit_to_macro_value);
                $arr[$arrKey] =$productPackagingHistory->macro_unit_code;
            }
            if ($productPackagingHistory->super_unit_code){
                $arrKey = intval($productPackagingHistory->micro_to_unit_value *
                    $productPackagingHistory->unit_to_macro_value *
                    $productPackagingHistory->macro_to_super_value);

                $arr[$arrKey] =$productPackagingHistory->super_unit_code;
            }
        }
        $arr=array_reverse($arr,true);
        $productPackagingDetails = $productPackagingFormatter->formatPackagingCombinationWithPackageCode(
           $lossQty,
            //  79,
            $arr
        );
        foreach ($productPackagingDetails as $key => $lossPackage){
            $validatedTransferLossData = [];
            $validatedTransferLossData['stock_transfer_master_code'] = $warehouseStockTransfer->stock_transfer_master_code;
            $validatedTransferLossData['warehouse_product_master_code'] = $warehouseProductMaster->warehouse_product_master_code;
            $validatedTransferLossData['quantity'] = $lossPackage['micro_quantity'];
            $validatedTransferLossData['package_quantity'] = $lossPackage['package_quantity'];
            $validatedTransferLossData['package_code'] = $key;
            $validatedTransferLossData['product_packaging_history_code'] = $productPackagingHistory->product_packaging_history_code;
            $validatedTransferLossData['created_by'] = getAuthUserCode();
            $this->warehouseTransferLossRepository->save($validatedTransferLossData);
        }
    }


    public function deleteWarehouseStockTransferDetailsByCode($storeTransferDetailsCode, $stockTransferCode)
    {
        try {
            DB::beginTransaction();
            $storeTransferDetails = $this->warehouseStockTransferRepository->deleteWarehouseStockTransferDetailsByCode($storeTransferDetailsCode, $stockTransferCode);
            DB::commit();
            return $storeTransferDetails;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function addStockTransferMasterMeta($request, $stockTransferCode)
    {
        try{
            DB::beginTransaction();
            $warehouseStockTransferMeta = $this->warehouseStockTransferRepository->addStockTransferMasterMeta($request, $stockTransferCode);
            DB::commit();
            return $warehouseStockTransferMeta;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function getStockTransferMetaByStockTransferCode($storeTransferCode)
    {
        try{
            return $this->warehouseStockTransferRepository->getStockTransferMetaByStockTransferCode($storeTransferCode);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function addProductToStockTransfer($validatedStockTransfer,$stockTransferCode,$status = null){
        try {
            $wpmCodes = $validatedStockTransfer['warehouse_product_master_code'];
            $warehouseCode = getAuthWarehouseCode();
            DB::beginTransaction();

            foreach ($wpmCodes as $key=>$wpmCode)
            {
                $warehouseProductMaster = $this->warehouseProductStockRepository->findCurrentProductStockInWarehouse($wpmCode);
                $productPackagingDetail = $this->vendorProductPackagingHistoryRepository->getLatestProductPackagingHistoryByPackageCode(
                    $validatedStockTransfer['package_code'][$key],
                    $validatedStockTransfer['product_code'][$key],
                    $validatedStockTransfer['product_variant_code'][$key]
                );
                $convertedOrderedMicroQuantity = ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                    $validatedStockTransfer['package_code'][$key],  $productPackagingDetail, $validatedStockTransfer['quantity'][$key]);

                $currentStock = $this->warehouseProductStockRepository->findCurrentProductStockInWarehouse($wpmCode);
                if ( $convertedOrderedMicroQuantity > $currentStock->current_stock){
                    throw new Exception('Stock Transfer failed: Insufficient stock for The Product ');
                }
                $data = [
                    'warehouse_product_master_code'=>$wpmCode,
                    'product_quantity'=>$convertedOrderedMicroQuantity,
                    'package_quantity'=>$validatedStockTransfer['quantity'][$key],
                    'package_code'=>$validatedStockTransfer['package_code'][$key],
                    'product_packaging_history_code'=>$productPackagingDetail->product_packaging_history_code
                ];
                 $whStockTransfer = $this->warehouseStockTransferRepository->addProductToStockTransfer($data,$stockTransferCode,$convertedOrderedMicroQuantity, $warehouseProductMaster,$status);
            }
            DB::commit();

            // DB::commit();

            return $whStockTransfer;
        }catch (Exception $exception) {
            DB::rollBack();
           // dd($exception->getFile(),$exception->getLine());
            throw $exception;
        }
    }
}
