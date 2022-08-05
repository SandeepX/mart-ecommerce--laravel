<?php


namespace App\Modules\AlpasalWarehouse\Helpers\PreOrder;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreorderProductCategoryView;
use Illuminate\Support\Facades\DB;
class WarehousePreOrderProductFilter
{
    public static function filterPaginatedWarehousePreOrderProducts($filterParameters,$paginateBy,$with = [])
    {

        $preOrderProducts=WarehousePreOrderProduct::with($with)
            ->when(isset($filterParameters['warehouse_preorder_listing_code']), function ($query) use ($filterParameters) {
                $query ->where('warehouse_preorder_listing_code',$filterParameters['warehouse_preorder_listing_code']);
            }) ->when(isset($filterParameters['warehouse_preorder_listing_code']), function ($query) use ($filterParameters) {
                $query->whereHas('warehousePreOrderListing',function ($query) use($filterParameters){
                    $query->where('warehouse_code',$filterParameters['warehouse_code']);
                });
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $preOrderProducts = $preOrderProducts->latest()->paginate($paginateBy)->withQueryString();
        return $preOrderProducts;

    }

    //used in wh admin
    public static function filterPaginatedWarehouseGroupedPreOrderProducts($filterParameters,$paginateBy,$with = [])
    {

        //dd($filterParameters);

        $preOrderProducts=WarehousePreOrderProduct::with($with)
            ->when(isset($filterParameters['warehouse_preorder_listing_code']), function ($query) use ($filterParameters) {
                $query ->where('warehouse_preorder_listing_code',$filterParameters['warehouse_preorder_listing_code']);
            }) ->when(isset($filterParameters['warehouse_preorder_listing_code']), function ($query) use ($filterParameters) {
                $query->whereHas('warehousePreOrderListing',function ($query) use($filterParameters){
                    $query->where('warehouse_code',$filterParameters['warehouse_code']);
                });
            }) ->when(isset($filterParameters['product_name']), function ($query) use ($filterParameters) {
                $query->whereHas('product',function ($query) use($filterParameters){
                    $query->where('products_master.product_name', 'like', '%' . $filterParameters['product_name'] . '%');
                });

            })->when(isset($filterParameters['vendor_name']), function ($query) use ($filterParameters) {
                $query->whereHas('product.vendor', function ($query) use ($filterParameters) {
                    $query->where('vendors_detail.vendor_name', 'like', '%' . $filterParameters['vendor_name'] . '%');
                });
            })

            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->whereHas('product.vendor', function ($query) use ($filterParameters) {
                    $query->where('vendors_detail.vendor_code', $filterParameters['vendor_code']);
                });
            });

        $preOrderProducts= $preOrderProducts->select('*',DB::raw('SUM(is_active) as total_active_product'));

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $preOrderProducts = $preOrderProducts->groupBy('warehouse_preorder_products.product_code')
            ->latest()->paginate($paginateBy)->withQueryString();

        return $preOrderProducts;

    }


    //used in api store
    public static function filterPaginatedWarehousePreOrderProductsForStore($storeCode,$filterParameters,$paginateBy,$with=[]){

        $productsCode =WarehousePreorderProductCategoryView::when($filterParameters['warehouse_codes'],function ($query) use($filterParameters){
                $query-> whereIn('warehouse_code',$filterParameters['warehouse_codes']);
            })
            ->when($filterParameters['category_codes'],function ($query) use($filterParameters){
                $query-> whereIn('category_code',$filterParameters['category_codes']);
            })->when(isset($filterParameters['warehouse_preorder_listing_code']), function ($query) use ($filterParameters) {
                $query ->where('warehouse_preorder_listing_code',$filterParameters['warehouse_preorder_listing_code']);
            })->when($filterParameters['min_price'] && $filterParameters['max_price'],function ($query) use($filterParameters){
                $query->whereBetween('store_pre_order_price',[$filterParameters['min_price'], $filterParameters['max_price']]);
            })->groupBy('product_code')->pluck('product_code')->toArray();



        $preOrderProducts= WarehousePreOrderProduct::with($with)
            ->whereIn('product_code',$productsCode)
            ->when(isset($filterParameters['warehouse_preorder_listing_code']), function ($query) use ($filterParameters) {
                $query ->where('warehouse_preorder_products.warehouse_preorder_listing_code',$filterParameters['warehouse_preorder_listing_code']);
            })
            ->when(isset($filterParameters['warehouse_codes']),function ($query) use($filterParameters){
                $query->whereHas('warehousePreOrderListing',function ($q) use($filterParameters){
                    $q->whereIn('warehouse_preorder_listings.warehouse_code',$filterParameters['warehouse_codes']);
                });
            })
            ->when(isset($filterParameters['product_name']),function ($query) use($filterParameters){
                $query->whereHas('product',function ($q) use($filterParameters){
                    $q->where('product_name', 'like', '%' . $filterParameters['product_name'] . '%');
                });
            })
            ->when(isset($filterParameters['is_active']),function ($query) use($filterParameters){
                $query->where('is_active',1);
            });
           /* ->leftJoin('store_preorder_details', function ($join) {
                $join->on('warehouse_preorder_products.warehouse_preorder_product_code', '=', 'store_preorder_details.warehouse_preorder_product_code')
                    ->whereNull('store_preorder_details.deleted_at');
            })->leftJoin('store_preorder', function ($join) use ($storeCode) {
                $join->on('store_preorder.store_preorder_code', '=', 'store_preorder_details.store_preorder_code')
                    ->where('store_preorder.store_code',$storeCode)->whereNull('store_preorder.deleted_at');
            });*/


      $preOrderProducts= $preOrderProducts->select(
          'warehouse_preorder_products.warehouse_preorder_product_code',
          'warehouse_preorder_products.warehouse_preorder_listing_code',
          'warehouse_preorder_products.product_code', 'warehouse_preorder_products.product_variant_code',
         // 'store_preorder.store_preorder_code',
         # DB::raw('COUNT(store_preorder_details.id) as total_store_orders')
      );

        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $preOrderProducts= $preOrderProducts->groupBy('warehouse_preorder_products.product_code')
            ->orderBy('warehouse_preorder_products.created_at','DESC')->paginate($paginateBy);
       // dd($preOrderProducts);
        return $preOrderProducts;
    }

    //used in api store
    public static function filterPaginatedWarehouseRelatedPreOrderProductsForStore($filterParameters,$exceptProductCode,$paginateBy,$with=[]){

        $preOrderProducts= WarehousePreOrderProduct::with($with)
            ->when(isset($filterParameters['warehouse_preorder_listing_code']), function ($query) use ($filterParameters) {
                $query ->where('warehouse_preorder_products.warehouse_preorder_listing_code',$filterParameters['warehouse_preorder_listing_code']);
            })
            ->when(isset($filterParameters['warehouse_code']),function ($query) use($filterParameters){
                $query->whereHas('warehousePreOrderListing',function ($q) use($filterParameters){
                    $q->whereIn('warehouse_preorder_listings.warehouse_code',$filterParameters['warehouse_code']);
                });
            })->when(isset($filterParameters['is_active']),function ($query) use($filterParameters){
                $query->where('is_active',1);
            });


        $preOrderProducts= $preOrderProducts->select('warehouse_preorder_products.warehouse_preorder_product_code',
            'warehouse_preorder_products.warehouse_preorder_listing_code',
            'warehouse_preorder_products.product_code', 'warehouse_preorder_products.product_variant_code');

        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $preOrderProducts= $preOrderProducts->where('warehouse_preorder_products.product_code','!=',$exceptProductCode)
            ->groupBy('warehouse_preorder_products.product_code')
            ->orderBy('warehouse_preorder_products.created_at','DESC')
            ->inRandomOrder()->paginate($paginateBy);
        return $preOrderProducts;
    }
}
