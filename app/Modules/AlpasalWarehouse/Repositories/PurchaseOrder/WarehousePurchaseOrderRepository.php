<?php

namespace App\Modules\AlpasalWarehouse\Repositories\PurchaseOrder;

use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;

use Exception;
class WarehousePurchaseOrderRepository
{

    public function findPurchaseOrderByCode($purchaseOrderCode)
    {
        return WarehousePurchaseOrder::findOrFail($purchaseOrderCode);
    }

    public function findOrFailPurchaseOrderByWarehouseCode($warehouseCode,$purchaseOrderCode,$with=[])
    {
        return WarehousePurchaseOrder::with($with)->where('warehouse_code',$warehouseCode)
            ->where('warehouse_order_code',$purchaseOrderCode)->firstOrFail();
    }

    public function getAllWarehousePurchaseOrdersByAdmin()
    {
        return WarehousePurchaseOrder::all();
    }

    public function filterWarehousePurchaseOrdersByStatus($status)
    {
        return WarehousePurchaseOrder::where('sent_status', $status)->get();
    }

    public function filterWarehousePurchaseOrdersByReceivedStatus($status)
    {
        return WarehousePurchaseOrder::whereHas('receivedByVendor', function($receivedOrder) use($status){
                                            return $receivedOrder->where('order_received_status', $status);
                                        })->get();
    }

    public function oldStoreWarehousePurchaseOrder($validatedPurchaseOrder){

        $purchaseOrder = [
            'order_date' => date('Y-m-d'),
            'warehouse_code' => $validatedPurchaseOrder['warehouse_code'],
            'vendor_code' => $validatedPurchaseOrder['vendor_code'],
            'user_code' => getAuthUserCode(),
            'sent_status' => $validatedPurchaseOrder['submit_type'],
            'sent_date' => $validatedPurchaseOrder['sent_date'],
        ];
        $purchaseOrder = WarehousePurchaseOrder::create($purchaseOrder);

        //Insert Into order Details Table
        foreach($validatedPurchaseOrder['product_code'] as $key => $productCode){
            $orderDetails = [
                'product_code' => $productCode,
                'product_variant_code' => $validatedPurchaseOrder['product_variant_code'][$key],
                'package_quantity' => $validatedPurchaseOrder['qty'][$key]

            ];
            $purchaseOrder->details()->create($orderDetails);
        }


        return $purchaseOrder;


    }

    public function storeWarehousePurchaseOrder($validatedPurchaseOrder,array $validatedPurchaseOrderDetails){

        $purchaseOrder=WarehousePurchaseOrder::create($validatedPurchaseOrder)->fresh();

        $purchaseOrder->purchaseOrderDetails()->createMany($validatedPurchaseOrderDetails);
        return $purchaseOrder;

    }

    public function updateStatus(WarehousePurchaseOrder $warehousePurchaseOrder,$status){

        if(!in_array($status,WarehousePurchaseOrder::STATUSES)){
            throw new Exception('Invalid warehouse purchase order status');
        }
       // $warehousePurchaseOrder->updated_by = getAuthUserCode();
        $warehousePurchaseOrder->status=$status;
        $warehousePurchaseOrder->save();
        return $warehousePurchaseOrder;
    }

    public function findWarehousePreOrderPurchaseOfVendor($vendorCode,$warehouseCode,$warehousePreOrderListingCode){

        $warehousePurchaseOrders = WarehousePurchaseOrder::where('vendor_code',$vendorCode)
            ->where('warehouse_code',$warehouseCode)->where('order_source','preorder')
            ->join('warehouse_preorder_purchase_orders', function ($join) {
                $join->on(
                    'warehouse_preorder_purchase_orders.warehouse_order_code',
                    '=',
                    'warehouse_orders.warehouse_order_code');
            })->where(
                'warehouse_preorder_purchase_orders.warehouse_preorder_listing_code',
                $warehousePreOrderListingCode
            )
            ->firstorFail();

        return $warehousePurchaseOrders;
    }


}

