<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/10/2020
 * Time: 3:13 PM
 */

namespace App\Modules\AlpasalWarehouse\Services\Bill;

use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;
use App\Modules\SystemSetting\Models\GeneralSetting;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;

class WarehouseOrderBillService
{

    const VAT_PERCENTAGE = 13;

    public function generateWarehouseOrderBillPdf(WarehousePurchaseOrder $warehousePurchaseOrder,$pdfViewPath,$requestAction)
    {

        try {
            if ($warehousePurchaseOrder->getOrderStatus() == 'draft') {

                throw new Exception('Bill not available for draft items');
            }
            $fullWarehouseOrderDetails = $this->getWarehousePurchaseOrderDetails($warehousePurchaseOrder);

            $pdf = PDF::loadView($pdfViewPath, compact('fullWarehouseOrderDetails'));


            if ($requestAction == 'download'){
                return $pdf->download('allpasal_store_order.pdf');
            }

            if ($requestAction == 'api'){
                return $pdf->output();
            }
            // return $pdf->download('allpasal_store_order.pdf');
            //return $pdf->stream('allpasal_store_order.pdf');
            // return view($pdfViewPath,compact('fullStoreOrderDetails'));
            // dd($fullOrderDetails);
            return $pdf->stream('allpasal_store_order.pdf');
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public function getWarehousePurchaseOrderDetails(WarehousePurchaseOrder $warehousePurchaseOrder){
        $fullOrderDetails = [];
        $fullWarehouseOrderDetails = [];

        $generalSetting = GeneralSetting::firstOrFail();


        $taxableWarehouseOrderDetails = $warehousePurchaseOrder->purchaseOrderDetails()
            ->where('warehouse_order_details.is_taxable_product', 1)
            ->get()->map(function ($purchaseOrderDetail) {
                //$vatRate= (self::VAT_PERCENTAGE / 100) *$purchaseOrderDetail->unit_rate;
               // $unitRateWithoutVat = $purchaseOrderDetail->unit_rate - $vatRate;
                return [
                    'quantity' => $purchaseOrderDetail->quantity,
                   // 'unit_rate' => $unitRateWithoutVat,
                    'unit_rate' => $purchaseOrderDetail->unit_rate,
                   'amount' => $purchaseOrderDetail->unit_rate * $purchaseOrderDetail->quantity,
                    //'amount' => $unitRateWithoutVat *$purchaseOrderDetail->quantity,
                    'product_name' => $purchaseOrderDetail->product->product_name,
                    'product_variant_name'=>isset($purchaseOrderDetail->productVariant) ? $purchaseOrderDetail->productVariant->product_variant_name :''
                ];
            })->toArray();

        $nonTaxableWarehouseOrderDetails = $warehousePurchaseOrder->purchaseOrderDetails()
            ->where('warehouse_order_details.is_taxable_product', 0)
            ->get()->map(function ($purchaseOrderDetail) {
                return [
                    'quantity' => $purchaseOrderDetail->quantity,
                    'unit_rate' => $purchaseOrderDetail->unit_rate,
                    'amount' => $purchaseOrderDetail->unit_rate * $purchaseOrderDetail->quantity,
                    'product_name' => $purchaseOrderDetail->product->product_name,
                    'product_variant_name'=>isset($purchaseOrderDetail->productVariant) ? $purchaseOrderDetail->productVariant->product_variant_name :''
                ];
            })->toArray();

        // dd($taxableStoreOrderDetails);
        if (count($taxableWarehouseOrderDetails) > 0) {
            $fullOrderDetails['taxable'] = $taxableWarehouseOrderDetails;
        }
        if (count($nonTaxableWarehouseOrderDetails) > 0) {

            $fullOrderDetails['non_taxable'] = $nonTaxableWarehouseOrderDetails;
        }

        //dd($fullOrderDetails);
        // dd($fullOrderDetails['non_taxable']);
        foreach ($fullOrderDetails as $key => $fullOrderDetail) {

            $taxableAmount =0;
            $vat ='';

            $subTotal= roundPrice(array_sum(array_column($fullOrderDetail,'amount')));
            if ($key == 'taxable') {
                $taxableAmount=roundPrice((self::VAT_PERCENTAGE / 100) * $subTotal);
                $grandTotal = roundPrice($subTotal + $taxableAmount);
                $vat=self::VAT_PERCENTAGE.'%';
            }
            else{
                $grandTotal= $subTotal;
            }

            $fullWarehouseOrderDetails[$key]=[
                'invoice_num' => $warehousePurchaseOrder['warehouse_order_code'],
                'vendor_name'=>$warehousePurchaseOrder->vendor->vendor_name,
                'vendor_code'=>$warehousePurchaseOrder->vendor->vendor_code,
                'vendor_contact'=>$warehousePurchaseOrder->vendor->contact_landline,
                'vendor_address'=>$warehousePurchaseOrder->vendor->vendor_landmark,
                'vendor_pan_vat'=>$warehousePurchaseOrder->vendor->pan?$warehousePurchaseOrder->vendor->pan:$warehousePurchaseOrder->vendor->vat,
                'warehouse_name' => $warehousePurchaseOrder->warehouse->warehouse_name,
                'warehouse_address' => $warehousePurchaseOrder->warehouse->landmark_name,
                'warehouse_code' => $warehousePurchaseOrder->warehouse->warehouse_code,
                'sub_total' => $subTotal,
                'warehouse_order_details' => $fullOrderDetail,
                'transaction_date' => date('Y-m-d', strtotime($warehousePurchaseOrder['order_date'])),
                //'current_date' => date('Y-m-d', strtotime(Carbon::now())),
                'grand_total' => $grandTotal,
                'taxable_amount'=>$taxableAmount == 0 ? '' : $taxableAmount,
                'vat'=>$vat,
                'warehouse_vat_pan'=> '609762431',
                'warehouse_contact_num'=>'9866622025 / 9866622365'
            ];

        }


      //  dd($fullWarehouseOrderDetails);
        return $fullWarehouseOrderDetails;
    }
}
