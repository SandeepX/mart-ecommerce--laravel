<?php

namespace App\Modules\Store\Repositories\PreOrder;

use App\Modules\AlpasalWarehouse\Models\PreOrder\StorePreOrderDispatchDetail;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use App\Modules\Store\Models\PreOrder\StorePreOrderStatusLog;
use Carbon\Carbon;

class StorePreOrderRepository
{

    public function getStorePreOrderCode(
        $whPreOrderListingCode,
        $storeCode
    ){
       return StorePreOrder::where('warehouse_preorder_listing_code',$whPreOrderListingCode)
                    ->where('store_code',$storeCode)->first();
    }

    public function getStorePreOrderByPreOrderCode($store_pre_order_code,$with=[],$select='*'){

        return StorePreOrder::with($with)->select($select)->where('store_preorder_code',$store_pre_order_code)->firstOrFail();
    }

    public function getValidStorePreOrderByPreOrderCode($store_pre_order_code,$with=[],$select='*'){

        return StorePreOrder::with($with)->validPreOrder()->select($select)->where('store_preorder_code',$store_pre_order_code)->firstOrFail();
    }

    public function getStorePreOrdersByWarehousePreOrderCodes(array $warehousePreOrderCodes,$with=[],$select='*'){

        return StorePreOrder::
             with($with)->select($select)
            ->whereIn('warehouse_preorder_listing_code',$warehousePreOrderCodes)
            ->where('status','pending')
            ->latest()
            ->get();
    }

    public function findOrFailByWarehouseCode($warehouseCode,$storePreOrderCode,$with=[],$select='*'){

        return StorePreOrder::with($with)->select($select)
            ->where('store_preorder_code',$storePreOrderCode)
            ->whereHas('warehousePreOrderListing',function($query) use($warehouseCode){
                $query->where('warehouse_code',$warehouseCode);
            })->firstOrFail();
    }


    public function getStorePreOrderDetailByProductCodeAndVariantCode(
        $whPreOrderListingCode,
        $storeCode,
        $productCode,
        $productVariantCode,
        $packageCode
    ){
        $data = [
            'wh_pre_order_listing_code' => $whPreOrderListingCode,
            'store_code' => $storeCode
        ];
       return StorePreOrderDetail::join('store_preorder',
                 'store_preorder.store_preorder_code',
                 '=',
                 'store_preorder_details.store_preorder_code'
       )->join('warehouse_preorder_products',
                   'warehouse_preorder_products.warehouse_preorder_product_code',
                   '=',
                   'store_preorder_details.warehouse_preorder_product_code'
       )
          ->where('store_preorder.warehouse_preorder_listing_code', '=', $data['wh_pre_order_listing_code'])
         ->where('store_preorder.store_code', '=', $data['store_code'])
          ->where('warehouse_preorder_products.product_code','=',$productCode)
           ->where('warehouse_preorder_products.product_variant_code','=',$productVariantCode)
           ->where('store_preorder_details.package_code','=',$packageCode)
           ->first();
    }

    public function getExistingStorePreOrderDetail(
        $storePreOrderCode,
        $whPreOrderProductCode,
        $packageCode
    ){
        return StorePreOrderDetail::where('store_preorder_code',$storePreOrderCode)
                                  ->where('warehouse_preorder_product_code',$whPreOrderProductCode)
                                  ->where('package_code',$packageCode)->first();
    }

    public function saveProductInPreOrder(
        $validatedPreOrderData,
        $storePreOrderInWhPreOrderListingCode= null,
        $existingPreOrderDetail = null
    )
    {
        if($existingPreOrderDetail){
           $preOrderDetail = $existingPreOrderDetail;
            $this->updateQuantityInPreOrder(
                $existingPreOrderDetail,
                $validatedPreOrderData['quantity']
            );

        }else{
            if(!$storePreOrderInWhPreOrderListingCode){
                $storePreOrder = StorePreOrder::create([
                    'warehouse_preorder_listing_code' => $validatedPreOrderData['wh-preorder-listing-code'],
                    'store_code' => $validatedPreOrderData['store_code'],
                    'payment_status' => 0
                ]);

                StorePreOrderStatusLog::create([
                    'store_preorder_code' => $storePreOrder->store_preorder_code,
                    'status' => 'pending',
                    'remarks' =>'Store pre-order created'
                ]);

            }else{
                $storePreOrder = $storePreOrderInWhPreOrderListingCode;
            }


            $preOrderDetail= StorePreOrderDetail::create([
                'store_preorder_code' => $storePreOrder->store_preorder_code,
                'warehouse_preorder_product_code' => $validatedPreOrderData['warehouse_preorder_product_code'],
                'package_code' => $validatedPreOrderData['package_code'],
                'product_packaging_history_code' => $validatedPreOrderData['product_packaging_history_code'],
                'quantity'=>$validatedPreOrderData['quantity'],
                'initial_order_quantity'=>$validatedPreOrderData['quantity'],
                'is_taxable' =>$validatedPreOrderData['is_taxable'],
            ]);



        }
        return $preOrderDetail->fresh();

    }

    //done mostly by store //order quantity
    public function updateQuantityInPreOrder($storePreOrderDetail,$quantity){
        $storePreOrderDetail->update([
            'quantity' => $quantity,
            'initial_order_quantity' => $quantity,
            'updated_by' =>getAuthUserCode(),
            'updated_at' => Carbon::now()
        ]);
        return $storePreOrderDetail;
    }

    public function finalizeMassPreOrders(array $storePreOrdersCode){

        StorePreOrder::whereIn('store_preorder_code',$storePreOrdersCode)->update([
            'payment_status' => 1,
            'status' =>'finalized'
        ]);
    }

    public function cancelMassPreOrders(array $storePreOrdersCode){

        StorePreOrder::whereIn('store_preorder_code',$storePreOrdersCode)->update([
            'payment_status' => 0,
            'status' =>'cancelled'
        ]);
    }



//    public function getMinAndMaxPreorderProductLimitDetail($validatedPreOrderData)
//    {
//        return WarehousePreOrderProduct::where('warehouse_preorder_listing_code', $validatedPreOrderData['wh-preorder-listing-code']
//                                        ->where('product_code',$validatedPreOrderData['product_code'])
//                                        ->where('product_variant_code',$validatedPreOrderData['product_variant_code']))
//                                        ->first();
//    }

    public function updatePreOrderStatus(StorePreOrder $storePreOrder,$validatedData){
         $storePreOrder->update([
            'status'=>$validatedData['status']
        ]);

         return $storePreOrder->fresh();
    }

    public function massUpdatePreOrderStatus(array $storePreOrdersCode,$validatedData){
        StorePreOrder::whereIn('store_preorder_code',$storePreOrdersCode)->update([
            'status' =>$validatedData['status']
        ]);
    }

    public function deletePreOrderProduct($storePreOrderDetail)
    {
        $storePreOrderDetail->delete();
        return $storePreOrderDetail;
    }

    public function getUpdatablePreOrderDetail($storePreOrderCode,$warehousePreOrderProductCode){
        $currentTimeString=Carbon::now( 'Asia/Kathmandu')->toDateTimeString();
        return StorePreOrderDetail::whereHas('storePreOrder',function($query) use($currentTimeString){
            $query->where('store_code',getAuthStoreCode())
                  ->whereHas('warehousePreOrderListing',function($query) use ($currentTimeString){
                      $query
                          ->where('start_time',
                          '<=',
                         $currentTimeString
                      )
//                          ->where('end_time',
//                          '>=',
//                         $currentTimeString
//                      )
                          ->where('finalization_time',
                          '>=',
                         $currentTimeString
                      )->where('is_active','=',1)
                      ->where('status_type','!=','cancelled');
                  });
        })
            ->where('store_preorder_code',$storePreOrderCode)
            ->where('warehouse_preorder_product_code',$warehousePreOrderProductCode)
            ->whereHas('warehousePreOrderProduct',function ($query){
                $query->where('is_active',1);
            })
            ->first();
    }

    public function getNewUpdatablePreOrderDetail($storePreOrderDetailCode,$with=[]){
        $currentTimeString=Carbon::now( 'Asia/Kathmandu')->toDateTimeString();

        return StorePreOrderDetail::with($with)->whereHas('storePreOrder',function($query) use($currentTimeString){
            $query->where('store_code',getAuthStoreCode())
                ->where('status','pending')
                  ->whereHas('warehousePreOrderListing',function($query) use ($currentTimeString){
                      $query
                          ->where('start_time',
                          '<=',
                         $currentTimeString
                      )
//                          ->where('end_time',
//                          '>=',
//                         $currentTimeString
//                      )
                          ->where('finalization_time',
                          '>=',
                         $currentTimeString
                      )->where('is_active','=',1)
                      ->where('status_type','!=','cancelled');
                  });
        })
            ->where('store_preorder_detail_code',$storePreOrderDetailCode)
            #->where('warehouse_preorder_product_code',$warehousePreOrderProductCode)
            ->whereHas('warehousePreOrderProduct',function ($query){
                $query->where('is_active',1);
            })
            ->first();
    }

    public function getDeleteablePreOrderDetail($storePreOrderDetailCode){

        $currentTimeString=Carbon::now( 'Asia/Kathmandu')->toDateTimeString();

        return StorePreOrderDetail::whereHas('storePreOrder',function($query) use($currentTimeString){
            $query->where('store_code',getAuthStoreCode())
                ->where('status','pending')
                ->whereHas('warehousePreOrderListing',function($query) use ($currentTimeString){
                    $query
                        ->where('start_time',
                            '<=',
                            $currentTimeString
                        )
//                        ->where('end_time',
//                            '>=',
//                            $currentTimeString
//                        )
                        ->where('finalization_time',
                            '>=',
                            $currentTimeString
                        );
                });
        })
            ->where('store_preorder_detail_code',$storePreOrderDetailCode)
            ->first();
    }



    public function getStatusLogsofStorePreOrderCode($store_preorder_code,$with=[],$select='*'){

        $storePreOrderStatusLogs = StorePreOrderStatusLog::with($with)
            ->select($select)
            ->where('store_preorder_code',$store_preorder_code)
            ->get();

        return   $storePreOrderStatusLogs;
    }


    public function updateHasMergedByStorePreOrder($storePreOrder){
        return $storePreOrder->update(['has_merged'=>1]);
    }



    public function createStorePreOrderDispatchDetail($validatedData, $storePreOrderCode)
    {
        return StorePreOrderDispatchDetail::create([
            'store_preorder_code' => $storePreOrderCode,
            'driver_name' => $validatedData['driver_name'],
            'vehicle_type' => $validatedData['vehicle_type'],
            'contact_number'=>$validatedData['contact_number'],
            'vehicle_number' => $validatedData['vehicle_number'],
            'expected_delivery_time' => $validatedData['expected_delivery_time'],
            'created_by' => getAuthUserCode()
        ]);
    }

    public function updateStorePreOrderForEarlyFianlized(StorePreOrder $storePreOrder){
        return $storePreOrder->update([
                'early_finalized'=> 1
            ]);
    }

    public function updateStorePreOrderForEarlyCancelled(StorePreOrder $storePreOrder,$validatedData)
    {
        return $storePreOrder->update([
            'early_cancelled'=> 1,
            'status' => $validatedData['status']
        ]);
    }

}
