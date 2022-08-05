<?php


namespace App\Modules\AlpasalWarehouse\Services\PreOrder;

use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderPurchaseHelper;
use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Repositories\PreOrder\WarehousePreOrderRepository;
use App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder\WarehousePreOrderPurchaseRepository;
use App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder\WarehousePurchaseOrderRepository;
use App\Modules\Product\Helpers\ProductPriceHelper;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Vendor\Repositories\VendorRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderPurchaseService
{
    private $warehousePreOrderRepository,$productRepository,$vendorRepository;
    private $warehousePurchaseOrderRepository,$warehousePreOrderPurchaseRepository;

    public function __construct(WarehousePreOrderRepository $warehousePreOrderRepository,
                                ProductRepository $productRepository,
                                VendorRepository $vendorRepository,
                                WarehousePurchaseOrderRepository $warehousePurchaseOrderRepository,
                                WarehousePreOrderPurchaseRepository $warehousePreOrderPurchaseRepository){
        $this->warehousePreOrderRepository = $warehousePreOrderRepository;
        $this->productRepository = $productRepository;
        $this->vendorRepository = $vendorRepository;
        $this->warehousePurchaseOrderRepository = $warehousePurchaseOrderRepository;
        $this->warehousePreOrderPurchaseRepository = $warehousePreOrderPurchaseRepository;
    }

    public function saveWarehousePurchaseOrderFromPreOrder($validatedPurchaseOrderDetail,$warehousePreOrderListingCode,$vendorCode){
        try{
            $authWarehouseCode = getAuthWarehouseCode();

            $warehousePreOrder = $this->warehousePreOrderRepository->findOrFailPreOrderByWarehouseCode($warehousePreOrderListingCode,$authWarehouseCode);

            $hasOrderBeenPlaced = WarehousePreOrderPurchaseHelper::isPreOrderPurchasePlacedToVendor(
                $vendorCode,$authWarehouseCode,$warehousePreOrderListingCode);
            if ($hasOrderBeenPlaced){
                throw new Exception('Order has already been placed to vendor.');
            }
            $vendor = $this->vendorRepository->findOrFailVendorByCode($vendorCode);
            $purchaseOrderDetails = [];

            $orderedProductsTaxableTotal = 0;
            $orderedProductsNonTaxableTotal = 0;
            $orderGrossTotal = 0;
            $productPriceHelper = new ProductPriceHelper();
            $validatedPurchaseOrder =[
                'vendor_code' => $vendorCode,
                'warehouse_code' =>$authWarehouseCode,
                'order_date' =>Carbon::now(),
                'status'=>'sent',
                'order_source' =>'preorder'
            ];

            //dd($validatedPurchaseOrder);
            //dd($validatedPurchaseOrderDetail);


            foreach ($validatedPurchaseOrderDetail['quantity'] as $key => $quantity) {

                $productCode = $validatedPurchaseOrderDetail['product_code'][$key];
                $product = $this->productRepository->findOrFailProductByCodeWith($productCode,['productVariants']);
                // $quantity =$validatedPurchaseOrderDetail['quantity'][$key];
                if ($product->vendor_code != $vendorCode){
                    throw new Exception($product->product_name.' does not belongs to vendor '.$vendor->vendor_name);
                }

                //dd(1);
                if ($product->hasVariants()) {
                    //if product has variants ..variant must be selected
                    if (isset($validatedPurchaseOrderDetail['product_variant_code'][$key])) {

                        $variantCodes = $product->productVariants->pluck('product_variant_code')->toArray();
                        $inputVariantCode = $validatedPurchaseOrderDetail['product_variant_code'][$key];

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

                        // array_push($validatedPurchaseOrderDetail['total_amount'], roundPrice($productPrice * $quantity));
                        array_push($purchaseOrderDetails, [
                            'product_code' => $productCode,
                            'product_variant_code' => $inputVariantCode,
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

                    //  array_push($validatedPurchaseOrderDetail['total_amount'], roundPrice($productPrice * $quantity));
                    array_push($purchaseOrderDetails, [
                        'product_code' => $productCode,
                        'product_variant_code' => null,
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
            }

            $orderGrossTotal = ($orderedProductsTaxableTotal + roundPrice( (Warehouse::VAT_PERCENTAGE_VALUE/100)*$orderedProductsTaxableTotal) ) + $orderedProductsNonTaxableTotal;
            $validatedPurchaseOrder['total_amount'] = $orderGrossTotal;

            DB::beginTransaction();
            $purchaseOrder = $this->warehousePurchaseOrderRepository->storeWarehousePurchaseOrder($validatedPurchaseOrder, $purchaseOrderDetails);
            $this->warehousePreOrderPurchaseRepository->storeWarehousePurchaseOrder(
                [
                    'warehouse_order_code' => $purchaseOrder->warehouse_order_code,
                    'warehouse_preorder_listing_code' =>$warehousePreOrderListingCode
                ]
            );
            DB::commit();
            return $purchaseOrder;

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function findWarehousePreOrderPurchaseOfVendor($vendorCode,$warehouseCode,$warehousePreOrderListingCode){
        return $this->warehousePurchaseOrderRepository->findWarehousePreOrderPurchaseOfVendor($vendorCode,$warehouseCode,$warehousePreOrderListingCode);
    }
}
