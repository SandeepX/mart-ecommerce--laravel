<?php


namespace App\Modules\AlpasalWarehouse\Repositories\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetailView;
use App\Modules\Store\Models\PreOrder\StorePreOrderView;
use App\Modules\Store\Models\Store;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderRepository
{

    public function getWarehousePreOrdersByWarehouseCode($warehouseCode,$with=[]){

        return WarehousePreOrderListing::with($with)->where('warehouse_code',$warehouseCode)->latest()->get();
    }

    public function getLatestWHPreOrderListing($warehouseCode,$with=[]){

        return WarehousePreOrderListing::with($with)->where('warehouse_code',$warehouseCode)->latest()->first();
    }

    public function getDisplayablePreOrdersByWarehouseCode($warehouseCode, $with = [])
    {
        return WarehousePreOrderListing::with($with)
            ->where('warehouse_code', $warehouseCode)
            ->displayable()
            ->whereHas('warehousePreOrderProducts')
            ->orderBy('start_time','ASC')
            ->get();
    }


    public function getDisplayableLimitedPreOrdersByWarehouseCode(
        $warehouseCode,
        $paginateBy,
        $with = []
    )
    {
        $query =  WarehousePreOrderListing::with($with)
            ->where('warehouse_code', $warehouseCode)
            ->displayable()
            ->whereHas('warehousePreOrderProducts')
            ->orderBy('start_time','DESC');

        if(isset($paginateBy)){
            return $query->paginate($paginateBy);
        }
          return $query->get();

    }

    public function getFinalizablePreOrdersByWarehouseCode($warehouseCode, $with = [])
    {
        return WarehousePreOrderListing::with($with)
            ->where('warehouse_code', $warehouseCode)
            ->finalizable()
            ->orderBy('start_time','ASC')
            ->get();
    }

    public function getPreOrderableListingsByWarehouseCode($warehouseCode, $with = [])
    {
        return WarehousePreOrderListing::with($with)
            ->where('warehouse_code', $warehouseCode)
            ->preOrderable()
            ->orderBy('start_time','ASC')
            ->get();
    }

    public function getPreOrderableListingByListingCode($warehouseCode,$wareheousePreOrderListingCode, $with = [])
    {
        return WarehousePreOrderListing::with($with)
            ->where('warehouse_code', $warehouseCode)
            ->preOrderable()
            ->where('warehouse_preorder_listing_code',$wareheousePreOrderListingCode)
            ->first();
    }


    public function findOrFailPreOrderByCode($warehousePreOrderCode,$with=[]){
        return WarehousePreOrderListing::with($with)->where('warehouse_preorder_listing_code',$warehousePreOrderCode)->firstOrFail();
    }

    public function findOrFailPreOrderByWarehouseCode($warehousePreOrderCode,$warehouseCode,$with=[]){


        $warehousePreOrderListing = WarehousePreOrderListing::with($with)
            ->where('warehouse_preorder_listing_code',$warehousePreOrderCode)
            ->where('warehouse_code',$warehouseCode)
            ->first();

        if(!$warehousePreOrderListing){
            throw new \Exception('No Such Pre Order Listing Found !');
        }
        return $warehousePreOrderListing;

    }

    public function findOrFailWithLockPreOrderByWarehouseCode($warehousePreOrderCode,$warehouseCode,$with=[]){

        $warehousePreOrderListing = WarehousePreOrderListing::with($with)
            ->where('warehouse_preorder_listing_code',$warehousePreOrderCode)
            ->where('warehouse_code',$warehouseCode)
            ->lockForUpdate()
            ->first();

        if(!$warehousePreOrderListing){
            throw new \Exception('No Such Pre Order Listing Found !');
        }
        return $warehousePreOrderListing;

    }




    public function getPaginatedWarehousePreOrdersByWarehouseCode($warehouseCode,$filterParameters,$paginateBy=10,$with=[]){

        return WarehousePreOrderListing::with($with)->where('warehouse_code',$warehouseCode)
            ->when(isset($filterParameters['pre_order_name']), function ($query) use ($filterParameters) {
                    $query->where('pre_order_name', 'like', '%' . $filterParameters['pre_order_name'] . '%');
            })
            ->latest()->paginate($paginateBy);
    }

    public function create($validatedData){
        return WarehousePreOrderListing::create($validatedData)->fresh();
    }

    public function update(WarehousePreOrderListing $warehousePreOrder,$validatedData){
        $warehousePreOrder->update($validatedData);
        return $warehousePreOrder->fresh();
    }

    public function updateActiveStatus($validated,WarehousePreOrderListing $warehousePreOrder){

        $authUserCode = getAuthUserCode();
        // $validated['updated_by'] = $authUserCode;
        $warehousePreOrder->updated_by=$authUserCode;
        $warehousePreOrder->is_active=$validated['is_active'];
        $warehousePreOrder->save();
        return $warehousePreOrder;
    }


    public function finalizeMassPreOrders(array $warehousePreOrdersListingCodes){
        WarehousePreOrderListing::whereIn('warehouse_preorder_listing_code',$warehousePreOrdersListingCodes)
            ->update(['is_finalized' => 1,'status_type'=>'finalized']);
    }

    public function cancelMassPreOrders(array $warehousePreOrdersListingCodes){
        WarehousePreOrderListing::whereIn('warehouse_preorder_listing_code',$warehousePreOrdersListingCodes)
            ->update([
                'status_type' => 'cancelled'
            ]);
    }

    public function cancelPreOrder(WarehousePreOrderListing $warehousePreOrderListing,$validatedData){
        $warehousePreOrderListing ->update([
                'status_type' => 'cancelled',
                'remarks' =>$validatedData['remarks']
            ]);
    }


    public function getWarehousesHavingPreOrder($filterParameters,$paginatedBy){
        $warehouses=Warehouse::withCount('warehousePreOrderListings')
            ->join('warehouse_preorder_listings',
                'warehouse_preorder_listings.warehouse_code',
                '=','warehouses.warehouse_code')
            ->groupBy('warehouse_preorder_listings.warehouse_code')
            ->addSelect(DB::raw('MAX(warehouse_preorder_listings.created_at) as last_created_at'))
            ->when(isset($filterParameters['warehouse_name']),function ($query) use($filterParameters){
                $query->where('warehouse_name','like','%'.$filterParameters['warehouse_name'] . '%');
            })
        ->paginate($paginatedBy);
       return $warehouses;
    }
    public function getPreOrdersInWarehouse($warehouseCode,$paginatedBy){

        $preOrders= WarehousePreOrderListing::withCount('warehousePreOrderProducts')
        ->where('warehouse_code',$warehouseCode)
            ->orderBy('start_time','DESC')
            ->paginate($paginatedBy);

        return $preOrders;
    }
    public function getProductsInPreOrder($filterParameters,$preOrderListingCode,$paginatedBy)
    {
        $productsInPreOrder=WarehousePreOrderProduct::where('warehouse_preorder_listing_code', $preOrderListingCode)
            ->with(['warehousePreOrderListing'=>function($query){
                $query->select('warehouse_preorder_listing_code','start_time','end_time','finalization_time');
            }])
            ->leftJoin('products_master','products_master.product_code','=','warehouse_preorder_products.product_code')
            ->leftJoin('vendors_detail','vendors_detail.vendor_code','=','products_master.vendor_code')
            ->leftJoin('product_variants','product_variants.product_variant_code','=','warehouse_preorder_products.product_variant_code')

            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('warehouse_preorder_products.is_active', $filterParameters['status']);
            })
            ->when(isset($filterParameters['product_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_preorder_products.product_code', $filterParameters['product_code']);
            })
            ->when(isset($filterParameters['product_variant_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_preorder_products.product_variant_code', $filterParameters['product_variant_code']);
            })
            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->where('vendors_detail.vendor_code', $filterParameters['vendor_code']);
            })
            ->select('warehouse_preorder_products.warehouse_preorder_product_code',
                'warehouse_preorder_products.is_active',
                'warehouse_preorder_products.product_code',
                'products_master.product_name',
                'product_variants.product_variant_name',
                'vendors_detail.vendor_name',
                'vendors_detail.vendor_code',
                'warehouse_preorder_products.warehouse_preorder_listing_code',
                'product_variants.product_variant_code'
            )
            ->addSelect(DB::raw(
                'ROUND(
            mrp- (
                 CASE wholesale_margin_type when "p"
                 Then
                     (wholesale_margin_value/100)*mrp
                 Else
                     wholesale_margin_value End
             )
             -
             (
                 CASE retail_margin_type when "p"
                 Then
                     (retail_margin_value/100)*mrp
                 Else
                     retail_margin_value End
             )
           ,2) as product_price'))
            ->paginate($paginatedBy);

        $allProducts = WarehousePreOrderProduct::where('warehouse_preorder_listing_code', $preOrderListingCode)
            ->with(['warehousePreOrderListing'=>function($query){
                $query->select('warehouse_preorder_listing_code','start_time','end_time','finalization_time');
            }])
            ->leftJoin('products_master','products_master.product_code','=','warehouse_preorder_products.product_code')
            ->leftJoin('vendors_detail','vendors_detail.vendor_code','=','products_master.vendor_code')
            ->leftJoin('product_variants','product_variants.product_variant_code','=','warehouse_preorder_products.product_variant_code')
            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->where('vendors_detail.vendor_code', $filterParameters['vendor_code']);
            })
            ->addSelect(DB::raw(
                'ROUND(
            mrp- (
                 CASE wholesale_margin_type when "p"
                 Then
                     (wholesale_margin_value/100)*mrp
                 Else
                     wholesale_margin_value End
             )
             -
             (
                 CASE retail_margin_type when "p"
                 Then
                     (retail_margin_value/100)*mrp
                 Else
                     retail_margin_value End
             )
           ,2) as product_price'))
            ->get();

        $total_amount = $allProducts->sum('product_price');

        return [
            'productsInPreOrder'=>$productsInPreOrder,
            'total_amount'=>$total_amount
        ];
    }

    public function getProductsInPreOrderByVendorCode($filterParameters,$preOrderListingCode,$vendorCode ,$paginateBy){
        $productsInPreOrder=WarehousePreOrderProduct::where('warehouse_preorder_listing_code', $preOrderListingCode)
            ->with(['warehousePreOrderListing'=>function($query){
                $query->select('warehouse_preorder_listing_code','start_time','end_time','finalization_time');
            }])
            ->leftJoin('products_master','products_master.product_code','=','warehouse_preorder_products.product_code')
            ->leftJoin('vendors_detail','vendors_detail.vendor_code','=','products_master.vendor_code')
            ->leftJoin('product_variants','product_variants.product_variant_code','=','warehouse_preorder_products.product_variant_code')

            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('warehouse_preorder_products.is_active', $filterParameters['status']);
            })
            ->when(isset($filterParameters['product_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_preorder_products.product_code', $filterParameters['product_code']);
            })
            ->when(isset($filterParameters['product_variant_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_preorder_products.product_variant_code', $filterParameters['product_variant_code']);
            })
            ->when(isset($filterParameters['vendor_code']) || isset($vendorCode), function ($query) use ($filterParameters,$vendorCode) {
               if($vendorCode) {
                   $query->where('vendors_detail.vendor_code', $vendorCode);
               }else{
                   $query->where('vendors_detail.vendor_code', $filterParameters['vendor_code']);
               }
            })
            ->select('warehouse_preorder_products.warehouse_preorder_product_code',
                'warehouse_preorder_products.is_active',
                'warehouse_preorder_products.product_code',
                'products_master.product_name',
                'product_variants.product_variant_name',
                'vendors_detail.vendor_name',
                'vendors_detail.vendor_code',
                'warehouse_preorder_products.warehouse_preorder_listing_code',
                'product_variants.product_variant_code'
            )
            ->addSelect(DB::raw(
                'ROUND(
            mrp- (
                 CASE wholesale_margin_type when "p"
                 Then
                     (wholesale_margin_value/100)*mrp
                 Else
                     wholesale_margin_value End
             )
             -
             (
                 CASE retail_margin_type when "p"
                 Then
                     (retail_margin_value/100)*mrp
                 Else
                     retail_margin_value End
             )
            ) as product_price,2'))
            ->paginate($paginateBy);

        return $productsInPreOrder;
    }

    public function delete(WarehousePreOrderListing $warehousePreOrder) {
        $warehousePreOrder->delete();
        return $warehousePreOrder;
    }
    public function getWarehouseByCode($warehouseCode)
    {
        $warehouse=Warehouse::where('warehouse_code',$warehouseCode)->first();
        return $warehouse;
    }
    public function getPreOrderByPreOrderListingCode($preOrderListingCode)
    {
        $preOrder=WarehousePreOrderListing::where('warehouse_preorder_listing_code',$preOrderListingCode)->first();
        return $preOrder;
    }
    public function getVendorsList($preOrderListingCode)
    {
        $vendors=WarehousePreOrderProduct::where('warehouse_preorder_listing_code', $preOrderListingCode)
            ->with(['warehousePreOrderListing'=>function($query){
                $query->select('warehouse_preorder_listing_code','start_time','end_time','finalization_time');
            }])
            ->leftJoin('products_master','products_master.product_code','=','warehouse_preorder_products.product_code')
            ->leftJoin('vendors_detail','vendors_detail.vendor_code','=','products_master.vendor_code')
            ->select('vendors_detail.vendor_code','vendors_detail.vendor_name')
            ->get();

        return $vendors;
    }

    public function findStoreByCode($filterParameters)
    {
        $store=Store::select(
            'stores_detail.store_name',
            'stores_detail.store_code',
            'stores_detail.status'
        )
            ->where('store_code',$filterParameters['store_code'])
            ->firstOrFail();
        return $store;
    }
    public function findPreorderByCode($filterParameters)
    {
        $preOrder=StorePreOrder::select(
            'store_preorder.store_preorder_code',
            'warehouse_preorder_listings.pre_order_name',
            'store_preorder.status',
            'warehouses.warehouse_name',
            'warehouse_preorder_listings.warehouse_preorder_listing_code',
            'warehouse_preorder_listings.start_time',
            'warehouse_preorder_listings.end_time',
            'warehouse_preorder_listings.finalization_time'
        )
            ->join('store_pre_orders_view',
                'store_pre_orders_view.store_preorder_code','store_preorder.store_preorder_code')
            ->join('store_pre_order_detail_view',function ($join){
                $join->on( 'store_pre_order_detail_view.store_preorder_code','store_pre_orders_view.store_preorder_code')
                    ->where('store_pre_order_detail_view.delivery_status',1)
                    ->whereNull('store_pre_order_detail_view.deleted_at');
            })
            ->join('warehouse_preorder_listings',
            'warehouse_preorder_listings.warehouse_preorder_listing_code','store_preorder.warehouse_preorder_listing_code')
            ->join('warehouses',
            'warehouses.warehouse_code','warehouse_preorder_listings.warehouse_code')
            ->addSelect(DB::raw('COUNT(store_pre_order_detail_view.warehouse_preorder_product_code) as total_products'))
            //->addSelect(DB::raw('COUNT(case warehouse_preorder_products.is_active when 0 then warehouse_preorder_products.product_code ELSE 0 end) as total_deactive_products'))
            ->where('store_pre_orders_view.store_code',$filterParameters['store_code'])
            ->where('store_pre_order_detail_view.store_preorder_code',$filterParameters['preorder_code'])
            ->firstOrFail();
        return $preOrder;
    }
    public function getPreOrderProducts($filterParameters)
    {
        $preOrderProducts=StorePreOrder::select(
            'products_master.product_code',
            'products_master.product_name',
            'products_master.is_taxable',
            'store_pre_order_detail_view.*',
            //'store_pre_order_detail_view.unit_rate',
            'store_pre_orders_view.total_price'
        )
            ->join('store_pre_orders_view',
            'store_pre_orders_view.store_preorder_code','store_preorder.store_preorder_code')
            ->join('store_pre_order_detail_view',function ($join){
                $join->on( 'store_pre_order_detail_view.store_preorder_code','store_pre_orders_view.store_preorder_code')
                ->where('store_pre_order_detail_view.delivery_status',1)
                ->whereNull('store_pre_order_detail_view.deleted_at');
            })
//            ->join('warehouse_preorder_listings',
//                'warehouse_preorder_listings.warehouse_preorder_listing_code','store_pre_orders_view.warehouse_preorder_listing_code')
            ->join('warehouse_preorder_products',
                'warehouse_preorder_products.warehouse_preorder_product_code','store_pre_order_detail_view.warehouse_preorder_product_code')
            ->join('products_master',
            'products_master.product_code','warehouse_preorder_products.product_code')
            ->where('store_preorder.store_preorder_code',$filterParameters['preorder_code'])
            ->where('store_preorder.store_code',$filterParameters['store_code'])
            ->get();
        return $preOrderProducts;
    }
    public function getPreOrderAmount($filterParameters)
    {
        $amount=StorePreOrder::select(
            'store_preorder.store_preorder_code',
            'store_pre_orders_view.total_price'

        )
            ->join('store_pre_orders_view',
                'store_pre_orders_view.store_preorder_code','store_preorder.store_preorder_code')
            ->join('store_pre_order_detail_view',
                'store_pre_order_detail_view.store_preorder_code','store_preorder.store_preorder_code')
            ->where('store_pre_orders_view.store_preorder_code',$filterParameters['preorder_code'])
            ->where('store_pre_orders_view.store_code',$filterParameters['store_code'])
            ->firstOrFail();

        return $amount;
    }
    public function deletedProducts($filterParameters)
    {
        $deletedProducts=StorePreOrderView::select(
            'store_pre_orders_view.store_preorder_code'

        )
            ->join('store_pre_order_detail_view',function ($join){
                $join->on( 'store_pre_order_detail_view.store_preorder_code','store_pre_orders_view.store_preorder_code')
                    ->whereNotNull('store_pre_order_detail_view.deleted_at');
            })
            ->addSelect(DB::raw('COUNT(store_pre_order_detail_view.warehouse_preorder_product_code) as total_deleted_products'))
            ->where('store_pre_orders_view.store_preorder_code',$filterParameters['preorder_code'])
            ->where('store_pre_orders_view.store_code',$filterParameters['store_code'])
            ->get();
        return $deletedProducts;
    }
    public function deactiveProducts($filterParameters)
    {
        $deactiveProducts=StorePreOrderView::select(
            'store_pre_orders_view.store_preorder_code'
        )
            ->join('store_pre_order_detail_view',function ($join){
                $join->on( 'store_pre_order_detail_view.store_preorder_code','store_pre_orders_view.store_preorder_code')
                    ->whereNull('store_pre_order_detail_view.deleted_at');
            })
            ->join('warehouse_preorder_products',function ($join){
                $join->on( 'store_pre_order_detail_view.warehouse_preorder_product_code','warehouse_preorder_products.warehouse_preorder_product_code');
            })
            //->addSelect(DB::raw('COUNT(store_pre_order_detail_view.delivery_status) as total_deactive_products'))
            ->addSelect(DB::raw('COUNT(case store_pre_order_detail_view.delivery_status
             when 1 then store_pre_order_detail_view.store_preorder_detail_code  end)
              as total_active_products'))
            ->addSelect(DB::raw('COUNT(case store_pre_order_detail_view.delivery_status
            when 0 then store_pre_order_detail_view.id  end)
            as total_deactive_products_in_preorder'))
            ->addSelect(DB::raw('COUNT(case warehouse_preorder_products.is_active
            when 0 then warehouse_preorder_products.id  end)
            as total_deactive_products'))
            ->where('store_pre_orders_view.store_preorder_code',$filterParameters['preorder_code'])
            ->where('store_pre_orders_view.store_code',$filterParameters['store_code'])
            ->get();
        return $deactiveProducts;
    }
    public function activeProducts($filterParameters)
    {
        $activeProducts=StorePreOrderView::select(
            'store_pre_order_detail_view.store_preorder_detail_code',
            'store_pre_order_detail_view.warehouse_preorder_product_code'

        )
            ->join('store_pre_order_detail_view',function ($join){
                $join->on( 'store_pre_order_detail_view.store_preorder_code','store_pre_orders_view.store_preorder_code')
                    ->whereNull('store_pre_order_detail_view.deleted_at');
            })
            ->addSelect(DB::raw('SUM(store_pre_order_detail_view.quantity * store_pre_order_detail_view.unit_rate) as sum_of_active_products'))
            ->where('store_pre_order_detail_view.delivery_status',1)
            ->where('store_pre_orders_view.store_preorder_code',$filterParameters['preorder_code'])
            ->where('store_pre_orders_view.store_code',$filterParameters['store_code'])
            ->get();
        return $activeProducts;
    }
}
