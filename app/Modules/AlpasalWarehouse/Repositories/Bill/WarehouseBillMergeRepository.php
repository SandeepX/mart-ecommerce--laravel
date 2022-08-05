<?php


namespace App\Modules\AlpasalWarehouse\Repositories\Bill;

use App\Modules\AlpasalWarehouse\Models\BillMerge\BillMergeDetail;
use App\Modules\AlpasalWarehouse\Models\BillMerge\BillMergeMaster;
use App\Modules\AlpasalWarehouse\Models\BillMerge\BillMergeProduct;
use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreOrder;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WarehouseBillMergeRepository extends RepositoryAbstract
{
    public function findByCode($code){
        $billMergeMaster = BillMergeMaster::with($this->with)
            ->select($this->select)
            ->where('bill_merge_master_code',$code)
            ->orderBy($this->orderByColumn,$this->orderDirection)
            ->first();

        return $billMergeMaster;
    }
    public function getAllMergedOrders($warehouseCode,$filterParameters)
    {
        $mergedOrders=BillMergeMaster::where('warehouse_code',$warehouseCode)
            ->when(isset($filterParameters['store_name']), function ($query) use ($filterParameters) {
                $query->whereHas('store', function ($query) use ($filterParameters) {
                    $query->where('stores_detail.store_name', 'like', '%' . $filterParameters['store_name'] . '%');
                });
            })
            ->latest()->paginate(10);

        return $mergedOrders;
    }
    public function getAllStoresOfWarehouse($warehouseCode)
    {
        $stores=Store::join('store_warehouse',function($join) use($warehouseCode){
            $join->on('store_warehouse.store_code','stores_detail.store_code')
                ->where('store_warehouse.warehouse_code',$warehouseCode);
        })
            ->get();
        return $stores;
    }

    public function getAllStoreOrdersOfWarehouse($storeCode,$warehouseCode)
    {
        $storeOrders=StoreOrder::where('store_code',$storeCode)
            ->where('wh_code',$warehouseCode)
            ->where('has_merged',0)
            ->whereNotIn('delivery_status',['ready_to_dispatch','dispatched','cancelled'])

            ->get();
        return $storeOrders;
    }

    public function getAllStorePreOrdersOfWarehouse($storeCode,$warehouseCode)
    {
        $storePreOrders=StorePreOrder::join('stores_detail',function($join) use($storeCode){
            $join->on('stores_detail.store_code','store_preorder.store_code')
                ->where('store_preorder.store_code',$storeCode);
        })
            ->join('store_warehouse',function($join) use($warehouseCode){
                $join->on('store_warehouse.store_code','stores_detail.store_code')
                    ->where('store_warehouse.warehouse_code',$warehouseCode);
            })
            ->where('store_preorder.status','finalized')
            ->where('has_merged',0)
            ->get();
        return $storePreOrders;
    }

    public function getStoreOrdersByCode($storeCode,$warehouseCode,$storeOrderCode)
    {
        $storeOrders=StoreOrder::select(
            'store_orders.store_order_code',
            'store_orders.has_merged',
            'store_orders.delivery_status as order_status',
            'store_order_details.product_code',
            'products_master.product_name',
            'store_order_details.product_variant_code',
            'store_order_details.initial_order_quantity',
            'store_order_details.quantity',
            'store_order_details.package_code',
            'store_order_details.product_packaging_history_code',
            'store_order_details.unit_rate',
            'store_order_details.is_taxable_product as is_taxable',
            'store_order_details.acceptance_status as status',
        )
        ->join('store_order_details',function($join) use($storeOrderCode,$storeCode,$warehouseCode){
           $join->on('store_order_details.store_order_code','store_orders.store_order_code')
               ->where('store_orders.store_order_code',$storeOrderCode)
               ->where('store_orders.store_code',$storeCode)
               ->where('store_orders.wh_code',$warehouseCode)
                ->whereNull('store_order_details.deleted_at');
        })
            ->join('products_master',function($join){
             $join->on('products_master.product_code','store_order_details.product_code');
            })
        ->get();
        return $storeOrders;
    }

    public function getStorePreOrdersByCode($storeCode,$warehouseCode,$storePreOrderCode)
    {
        $storePreOrders=StorePreOrder::select(
            'store_preorder.store_preorder_code',
            'store_preorder.has_merged',
            'store_preorder.status as order_status',
            'warehouse_preorder_products.product_code',
            'products_master.product_name',
            'warehouse_preorder_products.product_variant_code',
            'store_preorder_details.initial_order_quantity',
            'store_preorder_details.quantity',
            'store_preorder_details.package_code',
            'store_preorder_details.product_packaging_history_code',
            'store_preorder_details.is_taxable',
            'store_preorder_details.delivery_status as status',
            'store_pre_order_detail_view.unit_rate'
        )
        ->join('stores_detail',function($join) use($storeCode){
            $join->on('stores_detail.store_code','store_preorder.store_code')
                ->where('store_preorder.store_code',$storeCode);
        })
            ->join('store_warehouse',function($join) use($warehouseCode){
                $join->on('store_warehouse.store_code','stores_detail.store_code')
                    ->where('store_warehouse.warehouse_code',$warehouseCode);
            })
            ->join('store_preorder_details',function($join){
                $join->on('store_preorder_details.store_preorder_code','store_preorder.store_preorder_code')
                ->whereNull('store_preorder_details.deleted_at');
            })
            ->join('warehouse_preorder_products',function($join){
                $join->on('warehouse_preorder_products.warehouse_preorder_product_code','store_preorder_details.warehouse_preorder_product_code')
                ->whereNull('warehouse_preorder_products.deleted_at');
            })
            ->join('products_master',function($join){
                $join->on('products_master.product_code','warehouse_preorder_products.product_code');
            })
            ->join('store_pre_order_detail_view',function($join){
                $join->on('store_pre_order_detail_view.store_preorder_detail_code','store_preorder_details.store_preorder_detail_code');
            })
            ->where('store_preorder.store_preorder_code',$storePreOrderCode)
            ->get();
        return $storePreOrders;
    }

    public function createStoreBillMergeMaster($warehouseCode,$storeCode)
    {
        $billMerge=BillMergeMaster::create([
            'warehouse_code'=>$warehouseCode,
            'store_code'=>$storeCode
        ]);
        return $billMerge;
    }
    public function createStoreBillMergeProduct($billMergeMasterCode,$billMergeDetailCode,$mergedOrder)
    {

        if($mergedOrder->status===1)
        {
            $mergedOrder->status="accepted";
        }
        elseif($mergedOrder->status===0){
            $mergedOrder->status="rejected";
        }elseif($mergedOrder->status==='pending'){
            $mergedOrder->status="accepted";
        }

        $subtotal=($mergedOrder->quantity * $mergedOrder->unit_rate);


        $billMergeProduct=BillMergeProduct::create([
            'bill_merge_master_code'=>$billMergeMasterCode,
            'bill_merge_details_code'=>$billMergeDetailCode,
            'product_code'=>$mergedOrder->product_code,
            'product_variant_code'=>$mergedOrder->product_variant_code,
            'initial_order_quantity'=>($mergedOrder->initial_order_quantity) ? $mergedOrder->initial_order_quantity : $mergedOrder->quantity,
            'package_code'=>$mergedOrder->package_code,
            'product_packaging_history_code'=>$mergedOrder->product_packaging_history_code,
            'quantity'=>$mergedOrder->quantity,
            'is_taxable'=>$mergedOrder->is_taxable,
            'unit_rate'=>$mergedOrder->unit_rate,
            'subtotal'=>$subtotal,
            'status'=>$mergedOrder->status,
        ]);

        return $billMergeProduct;
    }
    public function createStoreBillMergeDetailForStoreOrder($billMergeMasterCode,$storeOrderCode)
    {
        $billMergeDetailForStoreOrder=BillMergeDetail::create([
            'bill_merge_master_code'=>$billMergeMasterCode,
            'bill_code'=>$storeOrderCode,
            'bill_type'=>"cart",
        ]);
        return $billMergeDetailForStoreOrder;
    }

    public function createStoreBillMergeDetailForStorePreOrder($billMergeMasterCode,$storePreOrderCode)
    {
        $billMergeDetailForStorePreOrder=BillMergeDetail::create([
            'bill_merge_master_code'=>$billMergeMasterCode,
            'bill_code'=>$storePreOrderCode,
            'bill_type'=>"preorder",
        ]);
        return $billMergeDetailForStorePreOrder;
    }

    public function getProductsByBillMergeMasterCode($billMergeMasterCode,$with=[])
    {
        $mergedProducts=BillMergeProduct::with($with)
            ->select(
                'bill_merge_product.bill_merge_product_code',
                'products_master.product_name',
                'product_variants.product_variant_name',
                'bill_merge_product.product_code',
                'bill_merge_product.product_variant_code',
                'bill_merge_product.package_code',
                'bill_merge_product.bill_merge_details_code',
                'vendors_detail.vendor_name',
                'bill_merge_product.initial_order_quantity',
                'bill_merge_product.quantity',
                'bill_merge_product.status',
                'bill_merge_product.subtotal',
                'bill_merge_product.is_taxable',
                'warehouse_product_master.current_stock',
                'warehouse_product_master.warehouse_product_master_code',
                'bill_merge_master.warehouse_code',
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
            ->join('products_master',function($join){
                $join->on('products_master.product_code','bill_merge_product.product_code');
            })
            ->join('vendors_detail',function($join){
                $join->on('vendors_detail.vendor_code','products_master.vendor_code');
            })
            ->leftJoin('product_variants',function ($join){
                $join->on('product_variants.product_variant_code','=','bill_merge_product.product_variant_code');
            })
            ->leftJoin('bill_merge_master',function($join){
                $join->on('bill_merge_master.bill_merge_master_code','=','bill_merge_product.bill_merge_master_code');
            })
          ->leftjoin('warehouse_product_master',function($join){
                $join->on(function($join){
                    $join->on('warehouse_product_master.product_code','=', 'bill_merge_product.product_code');
                    $join->on(function ($q){
                        $q->on('warehouse_product_master.product_variant_code','=', 'bill_merge_product.product_variant_code')
                            ->orWhere(function($q){
                                $q->where('warehouse_product_master.product_variant_code',null)
                                    ->where('bill_merge_product.product_variant_code',null);
                            });
                    });
                    $join->on('warehouse_product_master.warehouse_code','=','bill_merge_master.warehouse_code');
                });
            })
           // ->leftjoin('warehouse_product_stock_view','warehouse_product_stock_view.code','=','warehouse_product_master.warehouse_product_master_code')
            ->where('bill_merge_product.bill_merge_master_code',$billMergeMasterCode)
            ->leftjoin('product_package_details',
                'product_package_details.product_code','=','products_master.product_code')
            ->leftJoin('package_types','package_types.package_code','=','product_package_details.package_code')
            ->leftJoin('package_types as ordered_package_types','ordered_package_types.package_code','=','bill_merge_product.package_code')
            ->leftJoin('product_packaging_history', function ($join) {
                $join->on('bill_merge_product.product_packaging_history_code', '=', 'product_packaging_history.product_packaging_history_code');
            })
            ->get();
        return $mergedProducts;
    }

    public function findBillMergeMasterByCode($billMergeMasterCode,$with=[]){
        return BillMergeMaster::with($with)
            ->where('bill_merge_master_code',$billMergeMasterCode)
            ->latest()
            ->firstOrFail();
    }

    public function findorFailBillMergeDetailByCode($billMergeDetailCode){
          return BillMergeDetail::where('bill_merge_details_code',$billMergeDetailCode)
              ->latest()
              ->firstorFail();
    }

    public function findorFailBillMergeProductByCode($billMergeProductCode){
        return BillMergeProduct::where('bill_merge_product_code',$billMergeProductCode)
            ->latest()
            ->firstorFail();
    }


    public function updateBillMergeProduct(BillMergeProduct $billMergeProduct,$validatedData){

        $validatedData['subtotal'] = $validatedData['quantity'] * $billMergeProduct->unit_rate;

       return  $billMergeProduct->update($validatedData);
    }

    public function getBillMergeDetailByMasterCode($billMergeMasterCode){
        return BillMergeDetail::where('bill_merge_master_code',$billMergeMasterCode)
            ->latest()
            ->get();
    }

    public function updateBillMergeStatus($billMergeMaster,$validatedData){
        $billMergeMaster->update($validatedData);
        return $billMergeMaster->fresh();
    }

    public function getProductsByDetailsCode($billMergeDetailCode){
        return BillMergeProduct::where('bill_merge_details_code',$billMergeDetailCode)
            ->latest()
            ->get();
    }

}
