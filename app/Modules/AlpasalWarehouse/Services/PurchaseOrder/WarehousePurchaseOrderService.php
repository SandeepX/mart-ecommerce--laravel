<?php

namespace App\Modules\AlpasalWarehouse\Services\PurchaseOrder;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehousePurchaseOrderHelper;
use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder\WarehousePurchaseOrderDetailRepository;
use App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder\WarehousePurchaseOrderReceiveDetailsRepository;
use App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder\WarehousePurchaseOrderRepository;
use App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder\WarehousePurchaseReturnRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductStockRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehousePurchaseStockRepository;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;
use App\Modules\Product\Helpers\ProductPackagingPriceHelper;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use App\Modules\Vendor\Repositories\VendorProductPackagingRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehousePurchaseOrderService
{
    private $warehousePurchaseOrderRepository;
    private $productRepository;
    private $purchaseOrderDetailRepository;
    private $warehouseProductMasterRepository;
    private $warehouseProductStockRepository,$warehousePurchaseStockRepository;
    private $warehousePurchaseOrderDetailRepository;
    private $warehousePurchaseReturnRepository,$vendorProductPackagingRepository;
    private $vendorProductPackagingHistoryRepository;
    private $warehousePurchaseOrderReceiveDetailsRepository;

    public function __construct(
        WarehousePurchaseOrderRepository $warehousePurchaseOrderRepository,
        ProductRepository $productRepository,
        WarehousePurchaseOrderDetailRepository $purchaseOrderDetailRepository,
        WarehouseProductMasterRepository $warehouseProductMasterRepository,
        WarehouseProductStockRepository $warehouseProductStockRepository,
        WarehousePurchaseStockRepository $warehousePurchaseStockRepository,
        WarehousePurchaseOrderDetailRepository $warehousePurchaseOrderDetailRepository,
        WarehousePurchaseReturnRepository $warehousePurchaseReturnRepository,
        VendorProductPackagingRepository $vendorProductPackagingRepository,
        VendorProductPackagingHistoryRepository $vendorProductPackagingHistoryRepository,
        WarehousePurchaseOrderReceiveDetailsRepository $warehousePurchaseOrderReceiveDetailsRepository
    )
    {
        $this->warehousePurchaseOrderRepository = $warehousePurchaseOrderRepository;
        $this->productRepository = $productRepository;
        $this->purchaseOrderDetailRepository = $purchaseOrderDetailRepository;
        $this->warehouseProductMasterRepository = $warehouseProductMasterRepository;
        $this->warehouseProductStockRepository=$warehouseProductStockRepository;
        $this->warehousePurchaseStockRepository= $warehousePurchaseStockRepository;
        $this->warehousePurchaseOrderDetailRepository = $warehousePurchaseOrderDetailRepository;
        $this->warehousePurchaseReturnRepository= $warehousePurchaseReturnRepository;
        $this->vendorProductPackagingRepository= $vendorProductPackagingRepository;
        $this->vendorProductPackagingHistoryRepository= $vendorProductPackagingHistoryRepository;
        $this->warehousePurchaseOrderReceiveDetailsRepository= $warehousePurchaseOrderReceiveDetailsRepository;
    }


    public function findPurchaseOrderByCode($purchaseOrderCode)
    {
        return $this->warehousePurchaseOrderRepository->findPurchaseOrderByCode($purchaseOrderCode);
    }

    public function findOrFailPurchaseOrderByWarehouseCodeWith($warehouseCode, $purchaseOrderCode, array $with)
    {
        return $this->warehousePurchaseOrderRepository->findOrFailPurchaseOrderByWarehouseCode($warehouseCode, $purchaseOrderCode, $with);
    }

    public function filterPurchaseOrders($filterBy)
    {
        if ($filterBy == '' || $filterBy == 'all') {
            $purchaseOrders = $this->getAllWarehousePurchaseOrdersByAdmin();

        } elseif ($filterBy == 'sent' || $filterBy == 'draft') {
            $purchaseOrders = $this->filterWarehousePurchaseOrdersByStatus($filterBy);

        } elseif (in_array($filterBy, ['pending', 'accepted', 'in_process', 'ready_for_dispatch', 'cancelled'])) {
            $purchaseOrders = $this->filterWarehousePurchaseOrdersByReceivedStatus($filterBy);

        } else {
            throw new Exception('Invalid Filter Query', 400);
        }

        return $purchaseOrders;

    }

    public function filterWarehousePurchaseOrdersByStatus($filterBy)
    {
        return $this->warehousePurchaseOrderRepository->filterWarehousePurchaseOrdersByStatus($filterBy);
    }

    public function filterWarehousePurchaseOrdersByReceivedStatus($filterBy)
    {
        return $this->warehousePurchaseOrderRepository->filterWarehousePurchaseOrdersByReceivedStatus($filterBy);
    }

    public function getAllWarehousePurchaseOrdersByAdmin()
    {
        return $this->warehousePurchaseOrderRepository->getAllWarehousePurchaseOrdersByAdmin();
    }

    public function storeWarehousePurchaseOrder($validatedPurchaseOrder)
    {
        try {
            $warehouseCode = getAuthWarehouseCode();
            $productPriceHelper = new ProductPriceHelper();
            $purchaseOrderDetails = [];
          //  $validatedPurchaseOrder['total_amount'] = [];

            $orderedProductsTaxableTotal = 0;
            $orderedProductsNonTaxableTotal = 0;
            $orderGrossTotal = 0;


            foreach ($validatedPurchaseOrder['quantity'] as $key => $quantity) {

                $productCode = $validatedPurchaseOrder['product_code'][$key];
                $product = $this->productRepository->findOrFailProductByCodeWith($productCode, ['productVariants']);
                // $quantity =$validatedPurchaseOrder['quantity'][$key];

                if ($product->hasVariants()) {
                    //if product has variants ..variant must be selected
                    if (isset($validatedPurchaseOrder['product_variant_code'][$key])) {

                        $variantCodes = $product->productVariants()->pluck('product_variant_code')->toArray();
                        $inputVariantCode = $validatedPurchaseOrder['product_variant_code'][$key];

                        if (!in_array($inputVariantCode, $variantCodes)) {
                            throw new Exception('Variant of code ' . $inputVariantCode . ' does not belongs to ' . $product->product_name, 422);
                        }
                        $productPriceList = $productPriceHelper->findOrFailProductPriceList($productCode, $inputVariantCode);

                        $productPrice = $productPriceHelper->getProductWarehousePrice($productCode, $inputVariantCode);

                        if($product->isTaxable()){
                            $unitRate = roundPrice($productPrice / ( (1 + (Warehouse::VAT_PERCENTAGE_VALUE/100) )) );
                            $orderedProductsTaxableTotal += roundPrice($unitRate * $quantity);
                        }else{
                            $unitRate = $productPrice;
                            $orderedProductsNonTaxableTotal += roundPrice($unitRate * $quantity);
                        }

                        if (!ProductUnitPackagingHelper::isProductPackagedByPackageCode(
                            $validatedPurchaseOrder['package_code'][$key],$product->product_code,$inputVariantCode)){
                            throw new Exception('Purchase Order failed: package type does not exist for '.$product->product_name);
                        }

                       // array_push($validatedPurchaseOrder['total_amount'], roundPrice($productPrice * $quantity));
                        array_push($purchaseOrderDetails, [
                            'product_code' => $productCode,
                            'product_variant_code' => $inputVariantCode,
                            'package_code' => $validatedPurchaseOrder['package_code'][$key],
                            'is_taxable_product' => $product->is_taxable,
                            'quantity' => $quantity,
                            'unit_rate' => $unitRate,
                            'mrp' => $productPriceList['mrp'],
                            'admin_margin_type' => $productPriceList['admin_margin_type'],
                            'admin_margin_value' => $productPriceList['admin_margin_value'],
                            'wholesale_margin_type' => $productPriceList['wholesale_margin_type'],
                            'wholesale_margin_value' => $productPriceList['wholesale_margin_value'],
                            'retail_margin_type' => $productPriceList['retail_store_margin_type'],
                            'retail_margin_value' => $productPriceList['retail_store_margin_value'],

                        ]);
                    } else {
                        throw new Exception('Variant for ' . $product->product_name . ' must Be of selected', 422);
                    }
                } else {
                    $productPriceList = $productPriceHelper->findOrFailProductPriceList($productCode);
                    $productPrice = $productPriceHelper->getProductWarehousePrice($productCode);

                    if($product->isTaxable()){
                        $unitRate = roundPrice($productPrice / ( (1 + (Warehouse::VAT_PERCENTAGE_VALUE/100) )) );
                        $orderedProductsTaxableTotal += roundPrice($unitRate * $quantity);
                    }else{
                        $unitRate = $productPrice;
                        $orderedProductsNonTaxableTotal += roundPrice($unitRate * $quantity);
                    }

                    if (!ProductUnitPackagingHelper::isProductPackagedByPackageCode(
                        $validatedPurchaseOrder['package_code'][$key],$product->product_code)){
                        throw new Exception('Purchase Order failed: package type does not exist for '.$product->product_name);
                    }
                  //  array_push($validatedPurchaseOrder['total_amount'], roundPrice($productPrice * $quantity));
                    array_push($purchaseOrderDetails, [
                        'product_code' => $productCode,
                        'product_variant_code' => null,
                        'package_code' => $validatedPurchaseOrder['package_code'][$key],
                        'is_taxable_product' => $product->is_taxable,
                        'quantity' => $quantity,
                        'unit_rate' => $unitRate,
                        'mrp' => $productPriceList['mrp'],
                        'admin_margin_type' => $productPriceList['admin_margin_type'],
                        'admin_margin_value' => $productPriceList['admin_margin_value'],
                        'wholesale_margin_type' => $productPriceList['wholesale_margin_type'],
                        'wholesale_margin_value' => $productPriceList['wholesale_margin_value'],
                        'retail_margin_type' => $productPriceList['retail_store_margin_type'],
                        'retail_margin_value' => $productPriceList['retail_store_margin_value'],

                    ]);

                }
                /*  $variantCodes = $product->productVariants()->pluck('product_variant_code')->toArray();
                  if(!in_array($validatedPurchaseOrder['product_variant_code'][$key], $variantCodes)){
                      throw new Exception('Variant'.$key. 'must Be of Selected Product', 422);
                  }*/
            }

            $validatedPurchaseOrder['warehouse_code'] = $warehouseCode;
            if ($validatedPurchaseOrder['submit_type'] == 'sent') {
                $validatedPurchaseOrder['order_date'] = Carbon::now();
                $validatedPurchaseOrder['status'] = 'sent';
            } else {
                $validatedPurchaseOrder['status'] = 'draft';
            }

            //  dd($purchaseOrderDetails);
            //dd('last');
            // dd($validatedPurchaseOrder['total_amount']);
          //  $validatedPurchaseOrder['total_amount'] = array_sum($validatedPurchaseOrder['total_amount']);
            $orderGrossTotal = ($orderedProductsTaxableTotal + roundPrice( (Warehouse::VAT_PERCENTAGE_VALUE/100)*$orderedProductsTaxableTotal) ) + $orderedProductsNonTaxableTotal;
            $validatedPurchaseOrder['total_amount'] = $orderGrossTotal;

            DB::beginTransaction();
            $purchaseOrder = $this->warehousePurchaseOrderRepository->storeWarehousePurchaseOrder($validatedPurchaseOrder, $purchaseOrderDetails);

            DB::commit();
            return $purchaseOrder;

        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
    }

    public function getWarehousePurchaseOrderDetails($warehouseOrderCode){
        return WarehousePurchaseOrderHelper::getWarehousePurchaseOrderDetail($warehouseOrderCode);
    }


    public function newStoreWarehousePurchaseOrder($validatedPurchaseOrder)
    {
        try {
            $warehouseCode = getAuthWarehouseCode();
            $productPriceHelper = new ProductPriceHelper();
            $purchaseOrderDetails = [];
            //  $validatedPurchaseOrder['total_amount'] = [];

            $orderedProductsTaxableTotal = 0;
            $orderedProductsNonTaxableTotal = 0;
            $orderGrossTotal = 0;

            $allProductsArrays = [];


            foreach ($validatedPurchaseOrder['quantity'] as $key => $quantity) {

                $productCode = $validatedPurchaseOrder['product_code'][$key];
                $product = $this->productRepository->findOrFailProductByCodeWith($productCode, ['productVariants']);
                // $quantity =$validatedPurchaseOrder['quantity'][$key];
                $productVariantCode=null;
                if ($product->hasVariants()) {
                    //if product has variants ..variant must be selected
                    if (isset($validatedPurchaseOrder['product_variant_code'][$key])) {

                        $variantCodes = $product->productVariants()->pluck('product_variant_code')->toArray();
                        $productVariantCode = $validatedPurchaseOrder['product_variant_code'][$key];

                        if (!in_array($productVariantCode, $variantCodes)) {
                            throw new Exception('Variant of code ' . $productVariantCode . ' does not belongs to ' . $product->product_name, 422);
                        }
                    } else {
                        throw new Exception('Variant for ' . $product->product_name . ' must Be of selected', 422);
                    }
                }

                //check same product variant and package cannot repeat
                foreach($allProductsArrays as $productArray){
                      if($productArray['product_code']==$productCode
                          && $productArray['product_variant_code']==$productVariantCode
                          && $productArray['package_code'] == $validatedPurchaseOrder['package_code'][$key]
                      ){
                         throw  new Exception('Same Product and Variant with Same Package Cannot add at a time! Product Name:'.$product->product_name);
                      };
                }

                array_push($allProductsArrays ,[
                    'product_code'=>$productCode,
                    'product_variant_code'=>$productVariantCode,
                    'package_code'=>$validatedPurchaseOrder['package_code'][$key]]);


                $productPriceList = $productPriceHelper->findOrFailProductPriceList($productCode,$productVariantCode);

               // $productPrice = $productPriceHelper->getProductWarehousePrice($productCode,$productVariantCode);

                $productPackagingDetail = $this->vendorProductPackagingRepository->findProductPackagingDetailByPackageCode(
                    $validatedPurchaseOrder['package_code'][$key],$productCode,$productVariantCode);


                if (!$productPackagingDetail){
                    throw new Exception('Purchase Order failed: package type does not exist for '.$product->product_name);
                }

                $productPackagingHistory = $this->vendorProductPackagingHistoryRepository->getLatestProductPackagingHistoryByPackageCode(
                    $validatedPurchaseOrder['package_code'][$key],$productCode,$productVariantCode);

                if (!$productPackagingHistory){
                    throw new Exception('Purchase Order failed: package history does not exist for '.$product->product_name);
                }

                $microQuantity = ProductUnitPackagingHelper::convertToMicroUnitQuantity(
                    $validatedPurchaseOrder['package_code'][$key],
                    $productPackagingHistory,
                    $quantity
                );

                $productPrice = (new ProductPackagingPriceHelper())->calculateProductPackagePrice(
                    $validatedPurchaseOrder['package_code'][$key],$productPackagingDetail,$productCode,$productVariantCode
                );

                if (!$productPrice){
                    throw new Exception('Price not found for the product '.$product->product_name);
                }


                if($product->isTaxable()){
                    $unitRate = $productPrice / ( (1 + (Warehouse::VAT_PERCENTAGE_VALUE/100) )) ;
                    $orderedProductsTaxableTotal += $unitRate * $quantity;
                }else{
                    $unitRate = $productPrice;
                    $orderedProductsNonTaxableTotal += $unitRate * $quantity;
                }


                array_push($purchaseOrderDetails, [
                    'product_code' => $productCode,
                    'product_variant_code' => $productVariantCode,
                    'package_code' => $validatedPurchaseOrder['package_code'][$key],
                    'product_packaging_history_code' => $productPackagingHistory->product_packaging_history_code,
                    'is_taxable_product' => $product->is_taxable,
                    'quantity' => $microQuantity,
                    'package_quantity' => $quantity,
                    'unit_rate' => $unitRate,
                    'mrp' => $productPriceList['mrp'],
                    'admin_margin_type' => $productPriceList['admin_margin_type'],
                    'admin_margin_value' => $productPriceList['admin_margin_value'],
                    'wholesale_margin_type' => $productPriceList['wholesale_margin_type'],
                    'wholesale_margin_value' => $productPriceList['wholesale_margin_value'],
                    'retail_margin_type' => $productPriceList['retail_store_margin_type'],
                    'retail_margin_value' => $productPriceList['retail_store_margin_value'],

                ]);
            }

            $validatedPurchaseOrder['warehouse_code'] = $warehouseCode;
            if ($validatedPurchaseOrder['submit_type'] == 'sent') {
                $validatedPurchaseOrder['order_date'] = Carbon::now();
                $validatedPurchaseOrder['status'] = 'sent';
            } else {
                $validatedPurchaseOrder['status'] = 'draft';
            }

          //  dd($purchaseOrderDetails);
            //dd('last');
            // dd($validatedPurchaseOrder['total_amount']);
            //  $validatedPurchaseOrder['total_amount'] = array_sum($validatedPurchaseOrder['total_amount']);
            $orderGrossTotal = ($orderedProductsTaxableTotal + (Warehouse::VAT_PERCENTAGE_VALUE/100)*$orderedProductsTaxableTotal)  + $orderedProductsNonTaxableTotal;
            $validatedPurchaseOrder['total_amount'] = $orderGrossTotal;

            DB::beginTransaction();
            $purchaseOrder = $this->warehousePurchaseOrderRepository->storeWarehousePurchaseOrder($validatedPurchaseOrder, $purchaseOrderDetails);

            DB::commit();
            return $purchaseOrder;

        } catch (Exception $exception) {
            DB::rollBack();
            throw($exception);
        }
    }

    public function updateWarehousePurchaseOrderReceivedQuantity($validatedData,$warehouseOrderCode)
    {
        try {
            if (count(array_filter($validatedData['received_quantity'])) < 1) {
                throw new Exception('At least one received quantity must be filled');
            }

            //dd($validatedData);
            $authWarehouseCode = getAuthWarehouseCode();

            $with = ['purchaseOrderDetails'];
            $warehousePurchaseOrder = $this->warehousePurchaseOrderRepository->
            findOrFailPurchaseOrderByWarehouseCode($authWarehouseCode, $warehouseOrderCode, $with);

            if($warehousePurchaseOrder->getOrderStatus() == 'draft'){
                throw new Exception('Cannot update for draft orders');
            }

            $warehousePurchaseOrderDetailCodes = $warehousePurchaseOrder->purchaseOrderDetails->pluck('warehouse_order_detail_code')->toArray();

            DB::beginTransaction();
            foreach ($validatedData['warehouse_order_detail_code'] as $key => $orderDetailCode) {

                if (!in_array($orderDetailCode,$warehousePurchaseOrderDetailCodes)){
                    throw new Exception('Invalid order detail');
                }

                if ($validatedData['received_quantity'][$key] > 0) {
                    $receivedQuantity = $validatedData['received_quantity'][$key];

                    $purchaseOrderDetail = $this->purchaseOrderDetailRepository
                        ->findOrFailByCode($orderDetailCode, ['warehousePurchaseOrderReceivedDetail']);


                    if ($purchaseOrderDetail->hasBeenReceived()) {
                        // throw new Exception('Quantity already received');
                        continue;
                    }

                    if ($purchaseOrderDetail->quantity < $receivedQuantity) {
                        throw new Exception('Received quantity cannot be greater than ordered quantity');
                    }

                    //warehouse_purchase_order_received_details tbl store
                    $receivedDetails=$this->purchaseOrderDetailRepository->updateReceivedQuantity($purchaseOrderDetail, $receivedQuantity);


                    $warehouseProductMaster = $this->warehouseProductMasterRepository
                        ->findProductByWarehouseCode($authWarehouseCode, $purchaseOrderDetail->product_code, $purchaseOrderDetail->product_variant_code);

                    if (!$warehouseProductMaster) {
                        $validatedWarehouseProduct['warehouse_code'] = $authWarehouseCode;
                        $validatedWarehouseProduct['product_code'] = $purchaseOrderDetail->product_code;
                        $validatedWarehouseProduct['product_variant_code'] = $purchaseOrderDetail->product_variant_code;
                        $validatedWarehouseProduct['vendor_code'] = $warehousePurchaseOrder->vendor_code;

                        //warehouse_product_master tbl store
                        $warehouseProductMaster = $this->warehouseProductMasterRepository->storeWarehouseProduct($validatedWarehouseProduct);
                    }

                    //warehouse_product_stock tbl
                    $validatedWarehouseProductStock['warehouse_product_master_code']=$warehouseProductMaster->warehouse_product_master_code;
                    $validatedWarehouseProductStock['quantity']=$receivedQuantity;
                    $validatedWarehouseProductStock['action']='purchase';
                    $warehouseProductStock=$this->warehouseProductStockRepository
                        ->storeWarehouseProductStock($validatedWarehouseProductStock);


                    //warehouse_purchase_stocks tbl
                    $validatedWarehousePurchaseStock['warehouse_product_stock_code'] =$warehouseProductStock->warehouse_product_stock_code;
                    $validatedWarehousePurchaseStock['warehouse_order_code'] =$warehouseOrderCode;
                    $this->warehousePurchaseStockRepository->storeWarehousePurchaseStock($validatedWarehousePurchaseStock);

                }

            }
            $this->warehousePurchaseOrderRepository->updateStatus($warehousePurchaseOrder, 'received');
            DB::commit();

            //dd($warehousePurchaseOrder);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function newUpdateWarehousePurchaseOrderReceivedQuantity($validatedData,$warehouseOrderCode)
    {
        try {
            $authWarehouseCode = getAuthWarehouseCode();

            $with = ['purchaseOrderDetails'];
            $warehousePurchaseOrder = $this->warehousePurchaseOrderRepository->
            findOrFailPurchaseOrderByWarehouseCode($authWarehouseCode, $warehouseOrderCode, $with);

            if($warehousePurchaseOrder->getOrderStatus() != 'sent'){
                throw new Exception('Cannot update for draft or already received orders');
            }

            $warehouseOrderDetails = $warehousePurchaseOrder->purchaseOrderDetails->map(function ($purchaseOrderDetail){
                     $purchaseOrderDetail->combination_name = $purchaseOrderDetail->product_code.$purchaseOrderDetail->product_variant_code;
                     return $purchaseOrderDetail;
            });

            $productVariantCombinationNames = $warehouseOrderDetails->pluck('combination_name')->toArray();
                                                                               // ->pluck('product_variant_combination')->toArray()
            DB::beginTransaction();
            foreach ($validatedData['product_code'] as $key => $productCode) {

                $productVariantCode = $validatedData['product_variant_code'][$key];

                if (!in_array($productCode.$productVariantCode,$productVariantCombinationNames)){
                    throw new Exception('Invalid Product Code and Variant  code');
                }

                $purchaseOrderDetail = $this->purchaseOrderDetailRepository
                    ->getOrderDetailsOfProductByOrderCodeProductCodeAndVariantCode(
                        $warehouseOrderCode,
                        $productCode,
                        $productVariantCode,
                        [
                            'product',
                            'productVariant',
                            'productPackagingHistory'
                        ]
                    );

              //  dd($purchaseOrderDetail);


                if (!$purchaseOrderDetail->productPackagingHistory){
                    throw new Exception('Product packaging not found for '
                        .$purchaseOrderDetail->product->product_name.' '
                        .(($purchaseOrderDetail->productVariant)? $purchaseOrderDetail->productVariant->product_variant_name:''));
                }

                $productPackagingHistory= $purchaseOrderDetail->productPackagingHistory;

                $microOrderedQuantity = $purchaseOrderDetail->total_micro_quantity;

                $receivedMicroQuantity =isset($validatedData['micro_received_quantity'][$key])?
                    $validatedData['micro_received_quantity'][$key] : 0;
                $receivedUnitQuantity =isset($validatedData['unit_received_quantity'][$key])?
                    $validatedData['unit_received_quantity'][$key] : 0;
                $receivedMacroQuantity =isset($validatedData['macro_received_quantity'][$key])?
                    $validatedData['macro_received_quantity'][$key] : 0;
                $receivedSuperQuantity =isset($validatedData['super_received_quantity'][$key])?
                    $validatedData['super_received_quantity'][$key] : 0;

              //  dd($receivedMicroQuantity,$receivedUnitQuantity,$receivedMacroQuantity,$receivedSuperQuantity);

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


                if ($receivedQuantity > $microOrderedQuantity) {
                    throw new Exception('Received quantity cannot be greater than ordered quantity of product '
                    .$purchaseOrderDetail->product->product_name.' '
                    .(($purchaseOrderDetail->productVariant)? $purchaseOrderDetail->productVariant->product_variant_name:''));
                }

//                //warehouse_purchase_order_received_details tbl store
//                $receivedDetails =$this->purchaseOrderDetailRepository->updateReceivedQuantity($purchaseOrderDetail, $receivedQuantity);

                $warehouseProductMaster = $this->warehouseProductMasterRepository
                    ->findProductByWarehouseCode($authWarehouseCode, $purchaseOrderDetail->product_code, $purchaseOrderDetail->product_variant_code);
               //  dd($warehouseProductMaster);
                if (!$warehouseProductMaster && $receivedQuantity > 0) {
                    $validatedWarehouseProduct['warehouse_code'] = $authWarehouseCode;
                    $validatedWarehouseProduct['product_code'] = $purchaseOrderDetail->product_code;
                    $validatedWarehouseProduct['product_variant_code'] = $purchaseOrderDetail->product_variant_code;
                    $validatedWarehouseProduct['vendor_code'] = $warehousePurchaseOrder->vendor_code;

                    //warehouse_product_master tbl store
                    $warehouseProductMaster = $this->warehouseProductMasterRepository->storeWarehouseProduct($validatedWarehouseProduct);
                }

                if($receivedQuantity > 0){

                    foreach($allReceivedPackages as $key => $allReceivedPackage){
                        //insertion in seceive table
                        $validatedPurchaseReceiveData = [];
                        $validatedPurchaseReceiveData['warehouse_order_code'] = $purchaseOrderDetail->warehouse_order_code;
                        $validatedPurchaseReceiveData['product_code'] = $purchaseOrderDetail->product_code;
                        $validatedPurchaseReceiveData['product_variant_code'] = $purchaseOrderDetail->product_variant_code;
                        $validatedPurchaseReceiveData['received_quantity'] = $allReceivedPackage['micro_quantity'];
                        $validatedPurchaseReceiveData['package_quantity'] = $allReceivedPackage['package_quantity'];
                        $validatedPurchaseReceiveData['package_code'] = $key;
                        $validatedPurchaseReceiveData['product_packaging_history_code'] = $productPackagingHistory->product_packaging_history_code;
                        $warehousePurchaseOrderReceiveDetails = $this->warehousePurchaseOrderReceiveDetailsRepository->savePurchaseOrderReceiveDetail($validatedPurchaseReceiveData);

                        //warehouse product stock change table
                        $validatedWarehouseProductStock = [];
                        $validatedWarehouseProductStock['warehouse_product_master_code'] = $warehouseProductMaster->warehouse_product_master_code;
                        $validatedWarehouseProductStock['quantity'] = $allReceivedPackage['micro_quantity'];
                        $validatedWarehouseProductStock['package_qty'] = $allReceivedPackage['package_quantity'];
                        $validatedWarehouseProductStock['package_code'] = $key;
                        $validatedWarehouseProductStock['action'] = 'purchase';
                        $validatedWarehouseProductStock['product_packaging_history_code'] = $productPackagingHistory->product_packaging_history_code;
                        $validatedWarehouseProductStock['reference_code'] = $warehouseOrderCode;

                        $warehouseProductStock = $this->warehouseProductStockRepository
                            ->storeWarehouseProductStock($validatedWarehouseProductStock);

                    }

                    $currentStock = $warehouseProductMaster->current_stock + (int) $receivedQuantity;
                    $this->warehouseProductMasterRepository->updateWarehouseProductCurrentStock($warehouseProductMaster,$currentStock);
                }

            }

            $this->warehousePurchaseOrderRepository->updateStatus($warehousePurchaseOrder, 'received');
            DB::commit();

            //dd($warehousePurchaseOrder);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function returnWarehousePurchaseOrder($validatedData,$warehouseOrderDetailCode){
        try{
            $with =[
                'warehousePurchaseOrder',
                'warehousePurchaseOrderReceivedDetail',
                'warehousePurchaseReturn'
            ];
            $purchaseOrderDetail = $this->purchaseOrderDetailRepository->findOrFailByCode($warehouseOrderDetailCode,$with);
            $authWarehouseCode = getAuthWarehouseCode();

            if ($authWarehouseCode != $purchaseOrderDetail->warehousePurchaseOrder->warehouse_code){
                throw new Exception('Purchase order not found for the warehouse');
            }
            if($purchaseOrderDetail->warehousePurchaseReturn){
                throw new Exception('Already requested for the order');
            }
            if (!$purchaseOrderDetail->hasBeenReceived()){
                throw new Exception('Purchase order has not been received yet');
            }

            if ($purchaseOrderDetail->getReceivedQuantity() < $validatedData['return_quantity']){
                throw new Exception('Return Quantity cannot be greater than received quantity');
            }
            $warehouseProductStock = WarehouseProductStockHelper::findWarehouseProductStockByWarehouseCode($authWarehouseCode,
                $purchaseOrderDetail->product_code,$purchaseOrderDetail->product_variant_code);
            if(!$warehouseProductStock){
                throw new Exception('Product stock not found for warehouse');
            }

            if ($warehouseProductStock->current_stock < $validatedData['return_quantity']){
                throw new Exception('You do not have enough stock to return');
            }

            $validatedData['warehouse_order_code']= $purchaseOrderDetail->warehouse_order_code;
            $validatedData['vendor_code']= $purchaseOrderDetail->warehousePurchaseOrder->vendor_code;
            $validatedData['warehouse_order_detail_code']= $purchaseOrderDetail->warehouse_order_detail_code;
            $validatedData['status'] ='pending';
            DB::beginTransaction();
           $warehousePurchaseReturn= $this->warehousePurchaseReturnRepository->createWarehousePurchaseReturn($validatedData);
           DB::commit();
           return $warehousePurchaseReturn;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}

