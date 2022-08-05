<?php


namespace App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder;


use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseReturn;
use Illuminate\Support\Facades\DB;

class WarehousePurchaseOrderDetailRepository
{

    public function findOrFailByCode($warehouseOrderDetailCode,$with=[]){

        $orderDetail = PurchaseOrderDetail::with($with)->where('warehouse_order_detail_code',$warehouseOrderDetailCode)
            ->firstOrFail();

        return $orderDetail;
    }

    public function getOrderDetailsOfProductByOrderCodeProductCodeAndVariantCode($warehousePurchaseOrderCode,
                                                                                 $productCode,
                                                                                 $productVariantCode=null,
                                                                                 $with = []
                                                                        )
    {
       $orderDetail =  PurchaseOrderDetail::select('warehouse_order_details.*',DB::raw('SUM(quantity) as total_micro_quantity' ))->where('warehouse_order_code',$warehousePurchaseOrderCode)
                                            ->where('product_code',$productCode)
                                            ->where('product_variant_code',$productVariantCode)
                                            ->groupBy('warehouse_order_code','product_code','product_variant_code')
                                            ->firstOrFail();

       //dd($orderDetail);
       return $orderDetail;
    }


    public function updateReceivedQuantity(PurchaseOrderDetail $purchaseOrderDetail,$receivedQuantity){
       return $purchaseOrderDetail->warehousePurchaseOrderReceivedDetail()->updateOrCreate(
            ['warehouse_order_detail_code' => $purchaseOrderDetail->warehouse_order_detail_code],
            ['has_received' => 1, 'received_quantity' => $receivedQuantity]
        );
    }

}
