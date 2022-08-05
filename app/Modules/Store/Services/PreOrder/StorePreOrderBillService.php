<?php


namespace App\Modules\Store\Services\PreOrder;


use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderDetailHelper;

use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Repositories\PreOrder\StorePreOrderRepository;
use Carbon\Carbon;
use Exception;
class StorePreOrderBillService
{
    private $storePreOrderRepository;

    public function __construct(StorePreOrderRepository $storePreOrderRepository){
        $this->storePreOrderRepository = $storePreOrderRepository;
    }

    public function getStorePreOrderDetailForPdf($storePreOrderCode,$warehouseCode)
    {

        try {
            $with = [
                'store:store_code,store_name,store_contact_phone,store_contact_mobile,pan_vat_type,pan_vat_no,store_landmark_name',
                'warehousePreOrderListing.warehouse:warehouse_code,warehouse_name',
                'warehousePreOrderListing:warehouse_preorder_listing_code,warehouse_code',
                'storePreOrderView'
            ];
            $storePreOrder = $this->storePreOrderRepository->findOrFailByWarehouseCode($warehouseCode, $storePreOrderCode, $with);
            $orderInfo['store_name'] =$storePreOrder['store']['store_name'];
            $orderInfo['invoice_num'] = $storePreOrder['store_preorder_code'];
            $orderInfo['store_vat_pan_type'] = ucwords($storePreOrder['store']['pan_vat_type']);
            $orderInfo['store_vat_pan'] = $storePreOrder['store']['pan_vat_no'];
            $orderInfo['store_contact_num'] = $storePreOrder->store->store_contact_phone . '/' . $storePreOrder->store->store_contact_mobile;
            $orderInfo['store_address'] =  ucwords($storePreOrder->store->store_landmark_name);
            $orderInfo['transaction_date'] =date('Y-m-d', strtotime($storePreOrder['created_at']));
            $orderInfo['date_of_bill_issue'] =getNepTimeZoneDateTime(Carbon::now());
            $orderInfo['warehouse_name'] = $storePreOrder->warehousePreOrderListing->warehouse->warehouse_name;

            $storePreOrderDetails = StorePreOrderDetailHelper::getStorePreOrderAcceptedDetailForWarehouse($storePreOrderCode,$warehouseCode);
            $storePreOrderDetails = $storePreOrderDetails->map(function ($storePreOrderDetail) use ($storePreOrder) {
                $storePreOrderDetail->amount = $storePreOrderDetail->quantity * ($storePreOrderDetail->unit_rate);

               // $packageOrder =ProductUnitPackageDetail::MICRO_PACKAGE_ORDER_VALUE;
                $packageMicroQuantity = 1;

                if (isset($storePreOrderDetail->package_code) && ($storePreOrderDetail->package_code == $storePreOrderDetail->super_unit_code)){
                   // $packageOrder =ProductUnitPackageDetail::SUPER_PACKAGE_ORDER_VALUE;
                    $packageMicroQuantity = $storePreOrderDetail->micro_to_unit_value
                        * $storePreOrderDetail->unit_to_macro_value * $storePreOrderDetail->macro_to_super_value;

                }
                elseif (isset($storePreOrderDetail->package_code) && ($storePreOrderDetail->package_code == $storePreOrderDetail->macro_unit_code)){
                   // $packageOrder =ProductUnitPackageDetail::MACRO_PACKAGE_ORDER_VALUE;
                    $packageMicroQuantity =  $storePreOrderDetail->micro_to_unit_value * $storePreOrderDetail->unit_to_macro_value;

                }
                elseif (isset($storePreOrderDetail->package_code) && ($storePreOrderDetail->package_code == $storePreOrderDetail->unit_code)){
                   // $packageOrder =ProductUnitPackageDetail::UNIT_PACKAGE_ORDER_VALUE;
                    $packageMicroQuantity =  $storePreOrderDetail->micro_to_unit_value;
                }

               // $storePreOrderDetail->package_order = $packageOrder;

                $productPackagingUnitsArr =[
                    $storePreOrderDetail->super_unit_code ,
                    $storePreOrderDetail->macro_unit_code ,
                    $storePreOrderDetail->unit_code ,
                    $storePreOrderDetail->micro_unit_code
                ];

                $storePreOrderDetail->package_order = ProductUnitPackageDetail::determinePackagingBreakingLevel($productPackagingUnitsArr,$storePreOrderDetail->package_code);
                $storePreOrderDetail->package_micro_quantity = (int)$packageMicroQuantity;
                return $storePreOrderDetail;
            });


            $storePreOrderDetails = $storePreOrderDetails->groupBy('is_taxable')
                ->keyBy(function ($value, $key) {
                    if ($key == 0) {
                        return 'non_taxable';
                    } else {
                        return 'taxable';
                    }
                });

            $fullOrderDetailsWithChunk=collect();
            if (isset($storePreOrderDetails['taxable'])){
                $fullOrderDetailsWithChunk['taxable']=collect($storePreOrderDetails['taxable']->chunk(14));
            }
            if (isset($storePreOrderDetails['non_taxable'])){
                $fullOrderDetailsWithChunk['non_taxable'] =collect($storePreOrderDetails['non_taxable']->chunk(14));
            }

            foreach ($fullOrderDetailsWithChunk as $key => $fullOrderDetails) {

                foreach($fullOrderDetails as $item=>$fullOrderDetail) {
                    $taxableAmount = 0;
                    $vat = '';

                    $subTotal = $fullOrderDetail->sum('amount');
                    $totalQty=$fullOrderDetail->sum('quantity');

                    if ($key == 'taxable') {
                        $taxableAmount = (13 / 100) * $subTotal;
                        $grandTotal = roundPrice($subTotal + $taxableAmount);
                        $vat = 13 . '%';
                    } else {
                        $grandTotal = $subTotal;
                    }

                    $fullOrderDetailsWithChunk[$key][$item] = [
                        'sub_total' => $subTotal,
                        'store_order_details' => $fullOrderDetail,
                        'grand_total' => $grandTotal,
                        'taxable_amount' => $taxableAmount == 0 ? '' : $taxableAmount,
                        'vat' => $vat,
                        'total_qty'=>$totalQty
                    ];
                }

            }

            return[
                'order_info' =>$orderInfo,
                'store_preorder_details'=>$fullOrderDetailsWithChunk
            ];

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getStorePreOrderDetailForExcel($storePreOrderCode,$warehouseCode)
    {

        try {
            $authWarehouseCode = $warehouseCode;
            $with = [
                'store:store_code,store_name,store_contact_phone,store_contact_mobile',
                'warehousePreOrderListing.warehouse:warehouse_code,warehouse_name',
                'warehousePreOrderListing:warehouse_preorder_listing_code,warehouse_code',
                // 'storePreOrderView',
                //  'storePreOrderStatusLogs.updatedBy:user_code,name',
                //'storePreOrderStatusLogs:store_preorder_code,status,remarks,updated_by,created_at'
                /* 'storePreOrderDetails',
                 'storePreOrderDetails.warehousePreOrderProduct',
                 'storePreOrderDetails.warehousePreOrderProduct.product',
                 'storePreOrderDetails.warehousePreOrderProduct.productVariant',*/
            ];

            $storePreOrder = $this->storePreOrderRepository->findOrFailByWarehouseCode($authWarehouseCode, $storePreOrderCode, $with);
            $storePreOrderDetails = StorePreOrderDetailHelper::getStorePreOrderAcceptedDetailForWarehouse($storePreOrderCode,$authWarehouseCode);

            $storePreOrderDetails = $storePreOrderDetails->map(function ($storePreOrderDetail) use ($storePreOrder) {

                if ($storePreOrderDetail->is_taxable == 1) {
                    $taxUnitRate = $storePreOrderDetail->unit_rate + ($storePreOrderDetail->unit_rate * (StorePreOrder::VAT_PERCENTAGE_VALUE/100));
                    $taxSubTotal = $storePreOrderDetail->quantity * $taxUnitRate;
                    $taxPercent =StorePreOrder::VAT_PERCENTAGE_VALUE.'%';
                } else {
                    $taxUnitRate = $storePreOrderDetail->unit_rate;
                    $taxSubTotal = $storePreOrderDetail->quantity * ($storePreOrderDetail->unit_rate);
                    $taxPercent = '-';
                }
                $storePreOrderDetail->sub_total = $storePreOrderDetail->quantity * ($storePreOrderDetail->unit_rate);
                $storePreOrderDetail->delivery_status_name = $storePreOrderDetail->delivery_status == 1 ? 'Accepted' : 'Rejected';
                $storePreOrderDetail->tax_unit_rate = $taxUnitRate;
                $storePreOrderDetail->tax_sub_total = $taxSubTotal;
                $storePreOrderDetail->tax_percent = $taxPercent;
                $storePreOrderDetail->store_name = $storePreOrder->store->store_name;
                $storePreOrderDetail->store_preorder_code = $storePreOrder->store_preorder_code;

              /*  $packageOrder =ProductUnitPackageDetail::MICRO_PACKAGE_ORDER_VALUE;
                if ($storePreOrderDetail->package_code == $storePreOrderDetail->super_unit_code){
                    $packageOrder =ProductUnitPackageDetail::SUPER_PACKAGE_ORDER_VALUE;
                }
                elseif ($storePreOrderDetail->package_code == $storePreOrderDetail->macro_unit_code){
                    $packageOrder =ProductUnitPackageDetail::MACRO_PACKAGE_ORDER_VALUE;
                }
                elseif ($storePreOrderDetail->package_code == $storePreOrderDetail->unit_code){
                    $packageOrder =ProductUnitPackageDetail::UNIT_PACKAGE_ORDER_VALUE;
                }*/

                $productPackagingUnitsArr =[
                    $storePreOrderDetail->super_unit_code ,
                    $storePreOrderDetail->macro_unit_code ,
                    $storePreOrderDetail->unit_code ,
                    $storePreOrderDetail->micro_unit_code
                ];

                $storePreOrderDetail->package_order = ProductUnitPackageDetail::determinePackagingBreakingLevel($productPackagingUnitsArr,$storePreOrderDetail->package_code);
                return $storePreOrderDetail;
            });

            $storePreOrderDetails = $storePreOrderDetails->groupBy('is_taxable')
                ->keyBy(function ($value, $key) {
                    if ($key == 0) {
                        return 'non_taxable';
                    } else {
                        return 'taxable';
                    }
                });

            $taxableOrderDetails = collect();
            $nonTaxableOrderDetails = collect();
            if (isset($storePreOrderDetails['taxable'])) {
                $taxableOrderDetails['tax_excluded_amount'] = $storePreOrderDetails['taxable']->sum('sub_total');
                $taxableOrderDetails['tax_amount'] = (13 / 100) * $taxableOrderDetails['tax_excluded_amount'];
                $taxableOrderDetails['total_amount'] = $taxableOrderDetails['tax_excluded_amount'] + $taxableOrderDetails['tax_amount'];
            } else {
                $storePreOrderDetails['taxable'] = collect();
            }

            if (isset($storePreOrderDetails['non_taxable'])) {
                $nonTaxableOrderDetails['total_amount']= roundPrice($storePreOrderDetails['non_taxable']->sum('sub_total'));
            } else {
                $storePreOrderDetails['non_taxable'] = collect();
            }

            return [
                'store_pre_order' => $storePreOrder,
                'taxable_order_details' => $taxableOrderDetails,
                'taxable_order_products' => $storePreOrderDetails['taxable'],
                'non_taxable_order_details' => $nonTaxableOrderDetails,
                'non_taxable_order_products' => $storePreOrderDetails['non_taxable']
            ];

        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
