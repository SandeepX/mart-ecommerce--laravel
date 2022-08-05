<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/26/2020
 * Time: 11:21 AM
 */

namespace App\Modules\Store\Services\Bill;

use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductUnitPackageDetail;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\SystemSetting\Models\GeneralSetting;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\App;

class StoreOrderBillService
{

    const VAT_PERCENTAGE = 13;
    private $warehouseRepository;

    public function __construct(WarehouseRepository $warehouseRepository){
        $this->warehouseRepository = $warehouseRepository;
    }

    public function generateStoreOrderPdf(StoreOrder $storeOrder,$pdfViewPath,$requestAction)
    {

        try {
            $allowedDeliveryStatuses = [
                'processing','dispatched','received','accepted','cancelled'
            ];

            if(!in_array($storeOrder->delivery_status,$allowedDeliveryStatuses)){
                throw new Exception('Cannot generate the bill');
            }

            $fullOrderDetailsWithChunk = $this->getStoreOrderDetails($storeOrder);


           //dd($fullOrderDetailsWithChunk);
            $pdf = PDF::loadView($pdfViewPath, compact('fullOrderDetailsWithChunk'));

            if ($requestAction == 'download'){
                return $pdf->download(time().'-'.$storeOrder->store_order_code.'-'.'allpasal_store_order.pdf');
            }

            if ($requestAction == 'api'){
                return $pdf->output();
            }
           // return $pdf->download('allpasal_store_order.pdf');
            //return $pdf->stream('allpasal_store_order.pdf');
         // return view($pdfViewPath,compact('fullStoreOrderDetails'));
            // dd($fullOrderDetails);
            return $pdf->stream(time().'-'.$storeOrder->store_order_code.'-'.'allpasal_store_order.pdf');
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public function getStoreOrderDetails(StoreOrder $storeOrder){
//        $fullOrderDetails = [];
//        $fullStoreOrderDetails = [];

        //$generalSetting = GeneralSetting::firstOrFail();
        $warehouse = $this->warehouseRepository->findOrFailByCode($storeOrder->wh_code);
//        if (!$storeOrder->hasBeenPaid()) {
//            throw new Exception('Payment has not been done for the order');
//        }

       // $storeOrderOfflinePayment = $storeOrder->getLatestOfflinePayment();
        $fullOrderDetailsWithChunk = [];
        $taxableStoreOrderDetails = $storeOrder->details()
            //->where('store_order_details.is_taxable_product', 1)->where('is_accepted',1)
            ->where('store_order_details.is_taxable_product', 1)
            ->where('store_order_details.acceptance_status','accepted')
            ->leftJoin('product_package_details',
                'product_package_details.product_code','=','store_order_details.product_code')
            ->leftJoin('package_types','package_types.package_code','=','product_package_details.package_code')
            ->leftJoin('package_types as ordered_package_types','ordered_package_types.package_code','=','store_order_details.package_code')
            ->leftJoin('product_packaging_history', function ($join) {
                $join->on('store_order_details.product_packaging_history_code', '=', 'product_packaging_history.product_packaging_history_code');
            })
            ->select(
                'store_order_details.*',
                'store_order_details.package_code as ordered_package_code',
                'product_package_details.package_code',
                'package_types.package_name',
                'ordered_package_types.package_name as ordered_package_name',
                'product_packaging_history.micro_unit_code',
                'product_packaging_history.micro_to_unit_value',
                'product_packaging_history.unit_code',
                'product_packaging_history.unit_to_macro_value',
                'product_packaging_history.macro_unit_code',
                'product_packaging_history.macro_to_super_value',
                'product_packaging_history.super_unit_code'
            )
            ->get()
            ->map(function ($storeOrderDetail) use ($storeOrder) {

                $packageMicroQuantity =1;

                if (isset($storeOrderDetail->ordered_package_code) && ($storeOrderDetail->ordered_package_code == $storeOrderDetail->super_unit_code)){
                    $packageMicroQuantity =($storeOrderDetail->micro_to_unit_value
                        * $storeOrderDetail->unit_to_macro_value * $storeOrderDetail->macro_to_super_value);
                }
                elseif ( isset ($storeOrderDetail->ordered_package_code) && ($storeOrderDetail->ordered_package_code == $storeOrderDetail->macro_unit_code)){
                    $packageMicroQuantity = ($storeOrderDetail->micro_to_unit_value * $storeOrderDetail->unit_to_macro_value);
                }
                elseif (isset($storeOrderDetail->ordered_package_code) && ($storeOrderDetail->ordered_package_code == $storeOrderDetail->unit_code)){
                    $packageMicroQuantity = ($storeOrderDetail->micro_to_unit_value);
                }


                $productPackagingUnitsArr =[
                    $storeOrderDetail->super_unit_code ,
                    $storeOrderDetail->macro_unit_code ,
                    $storeOrderDetail->unit_code ,
                    $storeOrderDetail->micro_unit_code
                ];

                $packageOrder = ProductUnitPackageDetail::determinePackagingBreakingLevel($productPackagingUnitsArr,$storeOrderDetail->ordered_package_code);

                return [
                    'ordered_package_name' => $storeOrderDetail->ordered_package_name,
                    'old_package_name' => $storeOrderDetail->package_name,
                    'package_order' => $packageOrder,
                    'package_micro_quantity' => intval($packageMicroQuantity),
                    'quantity' => $storeOrderDetail->quantity,
                    'unit_rate' => $storeOrderDetail->unit_rate,
                    'amount' => $storeOrderDetail->unit_rate * $storeOrderDetail->quantity,
                    'product_name' => $storeOrderDetail->product->product_name,
                    'unit'=>$storeOrderDetail->package_name,
                    'product_variant_name'=>isset($storeOrderDetail->productVariant) ? $storeOrderDetail->productVariant->product_variant_name :''
                ];
            });


           $taxableStoreOrderDetails=collect($taxableStoreOrderDetails->chunk(14));
        $nonTaxableStoreOrderDetails = $storeOrder->details()
            //->where('store_order_details.is_taxable_product', 0)->where('is_accepted',1)
            ->where('store_order_details.is_taxable_product', 0)->where('store_order_details.acceptance_status','accepted')
            ->leftJoin('product_package_details',
                'product_package_details.product_code','=','store_order_details.product_code')
            ->leftJoin('package_types','package_types.package_code','=','product_package_details.package_code')
            ->leftJoin('package_types as ordered_package_types','ordered_package_types.package_code','=','store_order_details.package_code')
            ->leftJoin('product_packaging_history', function ($join) {
                $join->on('store_order_details.product_packaging_history_code', '=', 'product_packaging_history.product_packaging_history_code');
            })
            ->select(
                'store_order_details.*',
                'store_order_details.package_code as ordered_package_code',
                'product_package_details.package_code',
                'package_types.package_name',
                'ordered_package_types.package_name as ordered_package_name',
                'product_packaging_history.micro_unit_code',
                'product_packaging_history.micro_to_unit_value',
                'product_packaging_history.unit_code',
                'product_packaging_history.unit_to_macro_value',
                'product_packaging_history.macro_unit_code',
                'product_packaging_history.macro_to_super_value',
                'product_packaging_history.super_unit_code'
            )
            ->get()->map(function ($storeOrderDetail) use ($storeOrder) {

                $packageMicroQuantity = 1;
                if (isset($storeOrderDetail->ordered_package_code) && ($storeOrderDetail->ordered_package_code == $storeOrderDetail->super_unit_code)){
                    $packageMicroQuantity = $storeOrderDetail->micro_to_unit_value
                        * $storeOrderDetail->unit_to_macro_value * $storeOrderDetail->macro_to_super_value;
                }
                elseif (isset($storeOrderDetail->ordered_package_code) && ($storeOrderDetail->ordered_package_code == $storeOrderDetail->macro_unit_code)){
                    $packageMicroQuantity =  $storeOrderDetail->micro_to_unit_value * $storeOrderDetail->unit_to_macro_value;

                }
                elseif (isset($storeOrderDetail->ordered_package_code) && ($storeOrderDetail->ordered_package_code == $storeOrderDetail->unit_code)){
                    $packageMicroQuantity =  $storeOrderDetail->micro_to_unit_value;
                }

                $productPackagingUnitsArr =[
                    $storeOrderDetail->super_unit_code ,
                    $storeOrderDetail->macro_unit_code ,
                    $storeOrderDetail->unit_code ,
                    $storeOrderDetail->micro_unit_code
                ];

                $packageOrder = ProductUnitPackageDetail::determinePackagingBreakingLevel($productPackagingUnitsArr,$storeOrderDetail->ordered_package_code);

                return [
                    'ordered_package_name' => $storeOrderDetail->ordered_package_name,
                    'old_package_name' => $storeOrderDetail->package_name,
                    'package_order' => $packageOrder,
                    'package_micro_quantity' => (int)$packageMicroQuantity,
                    'quantity' => $storeOrderDetail->quantity,
                    'unit_rate' => $storeOrderDetail->unit_rate,
                    'amount' => $storeOrderDetail->unit_rate * $storeOrderDetail->quantity,
                    'product_name' => $storeOrderDetail->product->product_name,
                    'unit'=>$storeOrderDetail->package_name,
                    'product_variant_name'=>isset($storeOrderDetail->productVariant) ? $storeOrderDetail->productVariant->product_variant_name :''
                ];
            });
        $nonTaxableStoreOrderDetails=collect($nonTaxableStoreOrderDetails->chunk(14));
        if (count($taxableStoreOrderDetails) > 0) {
            $fullOrderDetailsWithChunk['taxable'] = $taxableStoreOrderDetails;
        }
        if (count($nonTaxableStoreOrderDetails) > 0) {

            $fullOrderDetailsWithChunk['non_taxable'] = $nonTaxableStoreOrderDetails;
        }

        foreach ($fullOrderDetailsWithChunk as $key => $fullOrderDetails) {
            foreach($fullOrderDetails as $item=>$fullOrderDetail) {
                $taxableAmount = 0;
                $vat = '';

                $subTotal = $fullOrderDetail->sum('amount');
                $totalQty=$fullOrderDetail->sum('quantity');
                /* foreach ($fullOrderDetail as $order){
                     $subTotal= $subTotal+$order['amount'];
                 }*/

                if ($key == 'taxable') {
                    $taxableAmount = (self::VAT_PERCENTAGE / 100) * $subTotal;
                    $grandTotal = roundPrice($subTotal + $taxableAmount);
                    $vat = self::VAT_PERCENTAGE . '%';
                } else {
                    $grandTotal = $subTotal;
                }

                $fullOrderDetailsWithChunk[$key][$item] = [
                    'invoice_num' => $storeOrder['store_order_code'],
                    'store_name' => $storeOrder->store->store_name,
                    'store_vat_pan_type' => strtoupper($storeOrder->store->pan_vat_type),
                     'store_vat_pan' => $storeOrder->store->pan_vat_no,
                    //'store_vat_pan' => $storeOrder->store->firmKyc->business_pan_vat_number,
                    'store_address' => $storeOrder->store->store_landmark_name,
                    'store_contact_num' => $storeOrder->store->store_contact_phone . '/' . $storeOrder->store->store_contact_mobile,

                    'sub_total' => $subTotal,
                    'store_order_details' => $fullOrderDetail,
                    'transaction_date' => date('Y-m-d', strtotime($storeOrder['created_at'])),
                    'date_of_bill_issue' => getNepTimeZoneDateTime(Carbon::now()),
                    //'current_date' => date('Y-m-d', strtotime(Carbon::now())),
                    //  'payment_method' => ucwords($storeOrderOfflinePayment->payment_type),
                    'grand_total' => roundPrice($grandTotal),
                    'taxable_amount' => $taxableAmount == 0 ? '' : $taxableAmount,
                    'vat' => $vat,
                    'warehouse_name' => $warehouse->warehouse_name,
                    'warehouse_code' => $warehouse->warehouse_code,
                    'warehouse_vat_pan' => '609762431',
                    'warehouse_contact_num' => '9866622025 / 9866622365',
                    'warehouse_address' => $warehouse->landmark_name,
                    'store_code' => $storeOrder->store->store_code,
                    'total_qty'=>$totalQty,
                ];
            }


//            $fullStoreOrderDetails[$key]=[
//                'invoice_num' => $storeOrder['store_order_code'],
//                'store_code'=>$storeOrder['store_code'],
//                'store_name' => $storeOrder->store->store_name,
//                'store_vat_pan_type' => strtoupper($storeOrder->store->pan_vat_type),
//               // 'store_vat_pan' => $storeOrder->store->pan_vat_no,
//               'store_vat_pan' => $storeOrder->store->firmKyc->business_pan_vat_number,
//                'store_address' => $storeOrder->store->store_landmark_name,
//                'store_contact_num'=> $storeOrder->store->store_contact_phone.'/'.$storeOrder->store->store_contact_mobile,
//
//                'sub_total' => $subTotal,
//                'store_order_details' => $fullOrderDetail,
//                'transaction_date' => date('Y-m-d', strtotime($storeOrder['created_at'])),
//                //'current_date' => date('Y-m-d', strtotime(Carbon::now())),
//              //  'payment_method' => ucwords($storeOrderOfflinePayment->payment_type),
//                'grand_total' => $grandTotal,
//                'taxable_amount'=>$taxableAmount == 0 ? '' : $taxableAmount,
//                'vat'=>$vat,
//                'warehouse_name' => $warehouse->warehouse_name,
//                'warehouse_code' =>$warehouse->warehouse_code,
//                'warehouse_vat_pan'=> '609762431',
//                'warehouse_contact_num'=> '9866622025 / 9866622365',
//                'warehouse_address' => $warehouse->landmark_name,
//
//            ];

        }

       // dd($fullOrderDetailsWithChunk);


        return $fullOrderDetailsWithChunk;
    }
}
