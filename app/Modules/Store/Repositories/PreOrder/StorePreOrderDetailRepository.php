<?php


namespace App\Modules\Store\Repositories\PreOrder;


use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetailView;
use Carbon\Carbon;

class StorePreOrderDetailRepository
{

    public function findOrFailByStorePreOrderCode($storePreOrderCode,$storePreOrderDetailCode,$with=[]){
        return StorePreOrderDetail::with($with)->where('store_preorder_code',$storePreOrderCode)
            ->where('store_preorder_detail_code',$storePreOrderDetailCode)->firstOrFail();
    }

    //by warehouse admin
    public function updatePreOrderDetailDeliveryStatus(StorePreOrderDetail $storePreOrderDetail,$validatedData){

        return $storePreOrderDetail->update([
            'quantity'=> $validatedData['quantity'],
            'delivery_status'=> $validatedData['delivery_status'],
            'admin_updated_by' => getAuthUserCode(),
            'admin_updated_at' => Carbon::now(),
        ]);
    }

    public function getStorePreOrderDetailsFromViewByStorePreOrderCode($storePreOrderCode,$with=[]){
        return StorePreOrderDetailView::with($with)
            ->where('store_preorder_code',$storePreOrderCode)
            ->whereNull('deleted_at')
           ->get();
    }

    public function findPreOrderDetailByOrderProductAndVariantCode($storePreOrderCode,$productCode,$variantCode){
    //dd($storePreOrderCode,$productCode,$variantCode);
        $storePreOrderDetail =  StorePreOrderDetail::where('store_preorder_code',$storePreOrderCode)
            ->whereHas('warehousePreOrderProduct',function ($query) use ($productCode,$variantCode){
                $query->where('product_code',$productCode)
                    ->where('product_variant_code',$variantCode);
            })
            ->latest()
            ->firstorFail();
       return $storePreOrderDetail;
     }


    public function findPreOrderDetailByOrderProductAndVariantCodeWithPackageCode($storePreOrderCode,$productCode,$variantCode,$packageCode){
        //dd($storePreOrderCode,$productCode,$variantCode);
        $storePreOrderDetail =  StorePreOrderDetail::where('store_preorder_code',$storePreOrderCode)
            ->whereHas('warehousePreOrderProduct',function ($query) use ($productCode,$variantCode){
                $query->where('product_code',$productCode)
                    ->where('product_variant_code',$variantCode);
            })
            ->where('package_code',$packageCode)
            ->latest()
            ->firstorFail();
        return $storePreOrderDetail;
    }


    public function updateStatusAndQuantityInBillMerge($storePreOrderDetail,$validatedData){

        return $storePreOrderDetail->update([
            'quantity'=>$validatedData['quantity'],
            'delivery_status'=>($validatedData['status']=='accepted') ? 1 : 0
        ]);
    }

    public function getStorePreoderDetailByWarehousePreorderProductCode($warehousePreorderProductCode)
    {
        return StorePreOrderDetail::where('warehouse_preorder_product_code',$warehousePreorderProductCode)
            ->count();
    }
}
