<?php


namespace App\Modules\AlpasalWarehouse\Services\StockTransfer;

use App\Modules\AlpasalWarehouse\Helpers\StockTransfer\StockTransferHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductPriceHelper;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransfer;
use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Repositories\StockTransfer\WarehouseReceiveStockTransferDetailsRepository;
use App\Modules\AlpasalWarehouse\Repositories\StockTransfer\WarehouseStockTransferDetailsRepository;
use App\Modules\AlpasalWarehouse\Repositories\StockTransfer\WarehouseStockTransferRepository;
use App\Modules\AlpasalWarehouse\Repositories\StockTransfer\WarehouseTransferStockRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductPriceRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductStockRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use Exception;

class AdminStockTransferService
{

    private $warehouseRepository;
    private $warehouseStockTransferRepository;
    private $warehouseStockTransferDetailsRepository;
    private $warehouseProductStockRepository;
    private $warehouseTransferStockRepository;
    private $warehouseProductMasterRepository;
    private $warehouseProductPriceRepository;
    private $vendorProductPackagingHistoryRepository;
    private $warehouseReceiveStockTransferDetailsRepository;

    public function __construct(
        WarehouseRepository $warehouseRepository,
        WarehouseStockTransferRepository $warehouseStockTransferRepository,
        WarehouseStockTransferDetailsRepository $warehouseStockTransferDetailsRepository,
        WarehouseProductStockRepository $warehouseProductStockRepository,
        WarehouseTransferStockRepository $warehouseTransferStockRepository,
        WarehouseProductMasterRepository $warehouseProductMasterRepository,
        WarehouseProductPriceRepository $warehouseProductPriceRepository,
        VendorProductPackagingHistoryRepository $vendorProductPackagingHistoryRepository,
        WarehouseReceiveStockTransferDetailsRepository $warehouseReceiveStockTransferDetailsRepository
    ){
        $this->warehouseRepository = $warehouseRepository;
        $this->warehouseStockTransferRepository = $warehouseStockTransferRepository;
        $this->warehouseStockTransferDetailsRepository = $warehouseStockTransferDetailsRepository;
        $this->warehouseProductStockRepository = $warehouseProductStockRepository;
        $this->warehouseTransferStockRepository = $warehouseTransferStockRepository;
        $this->warehouseProductMasterRepository = $warehouseProductMasterRepository;
        $this->warehouseProductPriceRepository = $warehouseProductPriceRepository;
        $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
        $this->warehouseReceiveStockTransferDetailsRepository = $warehouseReceiveStockTransferDetailsRepository;
    }

    public function stockTransfer($validatedData){

        try{
            if($validatedData['source_warehouse'] == $validatedData['destination_warehouse']){
                throw new Exception('Source and Destination Warehouse cannot be same');
            }
            $sourceWarehouse  = $this->warehouseRepository->findOrFailByCode($validatedData['source_warehouse']);
            $destinationWarehouse = $this->warehouseRepository->findOrFailByCode($validatedData['destination_warehouse']);

            $transferProducts = StockTransferHelper::getWarehouseTransferableProducts($sourceWarehouse);

            if(!count($transferProducts)>0){
                 throw new Exception('Source warehouse does not contain any transferable products');
            }

            $warehouseStockTransferMasterData = [];
            $warehouseStockTransferMasterData['status'] = 'received';
            $warehouseStockTransferMasterData['created_by'] = getAuthUserCode();
            $warehouseStockTransferMasterData['remarks'] = 'Bulk source to destination stock transfer';
            $warehouseStockTransferMasterData['source_warehouse_code'] = $validatedData['source_warehouse'];
            $warehouseStockTransferMasterData['destination_warehouse_code'] = $validatedData['destination_warehouse'];

            $warehouseStockTransfer = $this->warehouseStockTransferRepository->create($warehouseStockTransferMasterData);

            return $this->saveWarehouseStockTransferProductsDetails($warehouseStockTransfer,$transferProducts);

        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function saveWarehouseStockTransferProductsDetails(WarehouseStockTransfer $warehoueStockTransfer,$transferProducts){
        try{
            foreach($transferProducts as $transferProduct){

                $latestProductPackagingDetails = $this->vendorProductPackagingHistoryRepository
                                                 ->getProductPackagingHistoryByProductCodeAndVariantCode(
                                                     $transferProduct->product_code,
                                                     $transferProduct->product_variant_code
                                                 );

                $destinationWarehouseProduct = $this->warehouseProductMasterRepository
                    ->findProductByWarehouseCode(
                        $warehoueStockTransfer->destination_warehouse_code,
                        $transferProduct->product_code,
                        $transferProduct->product_variant_code
                    );

                if(!$destinationWarehouseProduct){
                    $destinationWarehouseProductData = [];
                    $destinationWarehouseProductData['warehouse_code'] =  $warehoueStockTransfer->destination_warehouse_code;
                    $destinationWarehouseProductData['product_code'] =    $transferProduct->product_code;
                    $destinationWarehouseProductData['product_variant_code'] = $transferProduct->product_variant_code;
                    $destinationWarehouseProductData['vendor_code'] = $transferProduct->vendor_code;
                    $destinationWarehouseProduct = $this->warehouseProductMasterRepository
                        ->storeWarehouseProduct($destinationWarehouseProductData);

                    $warehouseProductPrice = WarehouseProductPriceHelper::findWarehouseProductPriceByWarehouseProductCode(
                        $transferProduct->warehouse_product_master_code
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

                if(!$latestProductPackagingDetails){
                    //sent transfer stock
                    $stockTransferDetailsData = [];
                    $stockTransferDetailsData['stock_transfer_master_code'] = $warehoueStockTransfer->stock_transfer_master_code;
                    $stockTransferDetailsData['warehouse_product_master_code'] = $transferProduct->warehouse_product_master_code;
                    $stockTransferDetailsData['sending_quantity'] = $transferProduct->current_stock;
                    $stockTransferDetailsData['created_by'] = getAuthUserCode();
                    $warehouseStockTransferDetails =   $this->warehouseStockTransferDetailsRepository->save($stockTransferDetailsData);

                    $warehouseProductStockData  = [];
                    $warehouseProductStockData['warehouse_product_master_code'] = $transferProduct->warehouse_product_master_code;
                    $warehouseProductStockData['quantity'] = $warehouseStockTransferDetails->sending_quantity;
                    $warehouseProductStockData['action'] = 'stock-transfer';
                    $warehouseProductStockData['reference_code'] = $warehoueStockTransfer->stock_transfer_master_code;
                    $this->warehouseProductStockRepository->storeWarehouseProductStock($warehouseProductStockData);

                    //update current stock
                    $sentCurrentStock = $transferProduct->current_stock - (int) $warehouseStockTransferDetails->sending_quantity;
                    $this->warehouseProductMasterRepository->updateWarehouseProductCurrentStock($transferProduct,$sentCurrentStock);

                    //receive destination product stock
                    $receiveProductStockData = [];
                    $receiveProductStockData['warehouse_product_master_code'] = $destinationWarehouseProduct->warehouse_product_master_code;
                    $receiveProductStockData['quantity'] =  $warehouseStockTransferDetails->sending_quantity;
                    $receiveProductStockData['action'] = 'received-stock-transfer';
                    $receiveProductStockData['reference_code'] = $warehoueStockTransfer->stock_transfer_master_code;
                    $this->warehouseProductStockRepository
                            ->storeWarehouseProductStock($receiveProductStockData);

                    //update current stock
                    $receiveCurrentStock = $destinationWarehouseProduct->current_stock + (int) $warehouseStockTransferDetails->sending_quantity;
                    $this->warehouseProductMasterRepository->updateWarehouseProductCurrentStock($destinationWarehouseProduct,$receiveCurrentStock);

                    $receiveStockTransferDetailsData = [];
                    $receiveStockTransferDetailsData['stock_transfer_master_code'] = $warehoueStockTransfer->stock_transfer_master_code;
                    $receiveStockTransferDetailsData['warehouse_product_master_code'] =  $destinationWarehouseProduct->warehouse_product_master_code;
                    $receiveStockTransferDetailsData['received_quantity'] = $warehouseStockTransferDetails->sending_quantity;
                    $receiveStockTransferDetailsData['created_by'] =  getAuthUserCode();

                    $this->warehouseReceiveStockTransferDetailsRepository->save(
                                                                          $receiveStockTransferDetailsData
                                                                  );
                }else{
                    $productPackagingFormatter = new ProductPackagingFormatter();
                    $arr =[];
                    if ($latestProductPackagingDetails){
                        if ($latestProductPackagingDetails->micro_unit_code){
                            $arr[1] =$latestProductPackagingDetails->micro_unit_code;
                        }
                        if ($latestProductPackagingDetails->unit_code){
                            $arrKey = intval($latestProductPackagingDetails->micro_to_unit_value);
                            $arr[$arrKey] =$latestProductPackagingDetails->unit_code;
                        }
                        if ($latestProductPackagingDetails->macro_unit_code){
                            $arrKey = intval($latestProductPackagingDetails->micro_to_unit_value *
                                $latestProductPackagingDetails->unit_to_macro_value);
                            $arr[$arrKey] =$latestProductPackagingDetails->macro_unit_code;
                        }
                        if ($latestProductPackagingDetails->super_unit_code){
                            $arrKey = intval($latestProductPackagingDetails->micro_to_unit_value *
                                $latestProductPackagingDetails->unit_to_macro_value *
                                $latestProductPackagingDetails->macro_to_super_value);

                            $arr[$arrKey] =$latestProductPackagingDetails->super_unit_code;
                        }
                    }
                    $arr=array_reverse($arr,true);
                    $productPackagingDetails = $productPackagingFormatter->formatPackagingCombinationWithPackageCode(
                        $transferProduct->current_stock,
                        //  79,
                        $arr
                    );

                    //sent transfer section
                    foreach($productPackagingDetails as $key => $productPackagingDetail){
                        //send warehouse stock
                        $stockTransferDetailsData = [];
                        $stockTransferDetailsData['stock_transfer_master_code'] = $warehoueStockTransfer->stock_transfer_master_code;
                        $stockTransferDetailsData['warehouse_product_master_code'] = $transferProduct->warehouse_product_master_code;
                        $stockTransferDetailsData['sending_quantity'] = $productPackagingDetail['micro_quantity'];
                        $stockTransferDetailsData['package_quantity'] = $productPackagingDetail['package_quantity'];
                        $stockTransferDetailsData['package_code'] = $key;
                        $stockTransferDetailsData['product_packaging_history_code'] = $latestProductPackagingDetails->product_packaging_history_code;
                        $stockTransferDetailsData['created_by'] = getAuthUserCode();
                        $warehouseStockTransferDetails =   $this->warehouseStockTransferDetailsRepository->save($stockTransferDetailsData);

                        $warehouseProductStockData  = [];
                        $warehouseProductStockData['warehouse_product_master_code'] = $transferProduct->warehouse_product_master_code;
                        $warehouseProductStockData['quantity'] = $productPackagingDetail['micro_quantity'];
                        $warehouseProductStockData['action'] = 'stock-transfer';
                        $warehouseProductStockData['package_qty'] = $productPackagingDetail['package_quantity'];
                        $warehouseProductStockData['package_code'] = $key;
                        $warehouseProductStockData['product_packaging_history_code'] = $latestProductPackagingDetails->product_packaging_history_code;
                        $warehouseProductStockData['reference_code'] = $warehoueStockTransfer->stock_transfer_master_code;
                        $this->warehouseProductStockRepository->storeWarehouseProductStock($warehouseProductStockData);

                        //update current stock
                        $sentCurrentStock = $transferProduct->current_stock - (int) $productPackagingDetail['micro_quantity'];
                        $this->warehouseProductMasterRepository->updateWarehouseProductCurrentStock($transferProduct,$sentCurrentStock);


                        //receive warehouse Stock section
                        $receiveStockTransferDetailsData = [];
                        $receiveStockTransferDetailsData['stock_transfer_master_code'] = $warehoueStockTransfer->stock_transfer_master_code;
                        $receiveStockTransferDetailsData['warehouse_product_master_code'] =  $destinationWarehouseProduct->warehouse_product_master_code;
                        $receiveStockTransferDetailsData['received_quantity'] =  $productPackagingDetail['micro_quantity'];
                        $receiveStockTransferDetailsData['package_quantity'] =  $productPackagingDetail['package_quantity'];
                        $receiveStockTransferDetailsData['package_code'] = $key;
                        $receiveStockTransferDetailsData['product_packaging_history_code'] =  $latestProductPackagingDetails->product_packaging_history_code;
                        $receiveStockTransferDetailsData['created_by'] =  getAuthUserCode();
                        $this->warehouseReceiveStockTransferDetailsRepository->save(
                            $receiveStockTransferDetailsData
                        );

                        $receiveProductStockData = [];
                        $receiveProductStockData['warehouse_product_master_code'] = $destinationWarehouseProduct->warehouse_product_master_code;
                        $receiveProductStockData['quantity'] = $productPackagingDetail['micro_quantity'];
                        $receiveProductStockData['package_qty'] =  $productPackagingDetail['package_quantity'];
                        $receiveProductStockData['package_code'] = $key;
                        $receiveProductStockData['product_packaging_history_code'] =  $latestProductPackagingDetails->product_packaging_history_code;
                        $receiveProductStockData['action'] = 'received-stock-transfer';
                        $receiveProductStockData['reference_code'] = $warehoueStockTransfer->stock_transfer_master_code;
                        $this->warehouseProductStockRepository
                            ->storeWarehouseProductStock($receiveProductStockData);

                        //update current stock
                        $receiveCurrentStock = $destinationWarehouseProduct->current_stock + (int) $productPackagingDetail['micro_quantity'];
                        $this->warehouseProductMasterRepository->updateWarehouseProductCurrentStock($destinationWarehouseProduct,$receiveCurrentStock);

                    }
                }
            }

            return $warehoueStockTransfer;
        }catch (Exception $exception){
            throw $exception;
        }
    }




}
