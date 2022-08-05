<?php


namespace App\Modules\Product\Helpers;

use App\Modules\Product\Models\ProductCategoryFilterView;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\WHProductCategoryFilterView;
use Illuminate\Support\Facades\DB;


class ProductCategoryHelper
{

    public static function getProductsByCategoryCodes(array $categories)
    {
        return ProductMaster::whereHas('categories',function($query) use ($categories){
            $query->whereIn('product_category.category_code',$categories);
        })->get();
    }

    public static function getProductsOfCategoriesWithPagination(array $categories,$paginateBy = 8)
    {
      return ProductMaster::whereHas('categories',function($query) use ($categories){
              $query->whereIn('product_category.category_code',$categories);
       })->paginate($paginateBy);
    }

    public static function filterProductsByParameters(array $filterParameters){

        $productsCode =ProductCategoryFilterView::whereIn('category_code',$filterParameters['category_codes'])
            ->when($filterParameters['min_price'] && $filterParameters['max_price'],function ($query) use($filterParameters){
            $query->whereBetween('storePrice',[$filterParameters['min_price'], $filterParameters['max_price']]);
            })->groupBy('product_code')->pluck('product_code')->toArray();
        //$products = ProductMaster::verified()->active()->whereIn('product_code',$productsCode)->paginate(ProductMaster::PRODUCT_PER_PAGE);
        $products = ProductMaster::qualifiedToDisplay()->whereIn('product_code',$productsCode)->paginate(ProductMaster::PRODUCT_PER_PAGE);

        return $products;
    }

    public static function filterPaginatedWarehouseRelatedProducts($filterParameters,$paginateBy,$exceptProductCode,$with = [])
    {

        $products = ProductMaster::with($with)
            ->when(isset($filterParameters['category_codes']) && !empty($filterParameters['category_codes']), function ($query) use ($filterParameters) {
                $query-> whereIn('category_code',$filterParameters['category_codes']);
            })
            ->when(isset($filterParameters['warehouse_codes'])&& !empty($filterParameters['warehouse_codes']), function ($query) use ($filterParameters) {
                $query->join('warehouse_product_master', function ($join) use ($filterParameters){
                    $join->on('warehouse_product_master.product_code', '=', 'products_master.product_code')
                        ->whereIn('warehouse_product_master.warehouse_code', $filterParameters['warehouse_codes']);
                });
            });


        //dd($products);

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $products = $products->qualifiedToDisplay()->where('products_master.product_code','!=',$exceptProductCode)
            ->inRandomOrder()->paginate($paginateBy);
        return $products;
    }

    public static function filterPaginatedRelatedProducts($filterParameters,$paginateBy,$exceptProductCode,$with = [])
    {
        $issetWarehouseCodes=(isset($filterParameters['warehouse_codes'])&& !empty($filterParameters['warehouse_codes'])) ? true :false;
       // dd($issetWarehouseCodes);
        $products = ProductMaster::with($with)
            ->whereHas('unitPackagingDetails')
            ->select(
                'products_master.category_code',
                'products_master.brand_code',
                'products_master.highlights',
                'products_master.product_code',
                'products_master.product_name',
                'products_master.is_taxable',
                'products_master.slug',
                'products_master.created_at'
            )
            ->when(isset($filterParameters['category_codes']) && !empty($filterParameters['category_codes']), function ($query) use ($filterParameters) {
                $query-> whereIn('category_code',$filterParameters['category_codes']);
            })
            ->when($issetWarehouseCodes, function ($query) use ($filterParameters) {
                $query->join('warehouse_product_master', function ($join) use ($filterParameters){
                    $join->on('warehouse_product_master.product_code', '=', 'products_master.product_code')
                        ->whereIn('warehouse_product_master.warehouse_code', $filterParameters['warehouse_codes'])
                        ->where('warehouse_product_master.is_active',1)
                        ->where('warehouse_product_master.current_stock','>',0);
                })->join('warehouse_product_price_master', function ($join) use ($filterParameters){
                    $join->on('warehouse_product_master.warehouse_product_master_code', '=',
                        'warehouse_product_price_master.warehouse_product_master_code');
                });
//                ->join('warehouse_product_stock_view',function ($join){
//                    $join->on('warehouse_product_master.warehouse_product_master_code', '=',
//                        'warehouse_product_stock_view.code')
//                        ->where('warehouse_product_stock_view.current_stock','>',0);
//                });
            });


        //dd($products);
        if ($issetWarehouseCodes){
            $products= $products->groupBy('products_master.product_code','warehouse_product_master.warehouse_code');
        }
        else{
            $products= $products->qualifiedToDisplay()->groupBy('products_master.product_code');
        }

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $products = $products->orderBy('products_master.created_at', 'DESC')->where('products_master.product_code','!=',$exceptProductCode)
            ->inRandomOrder()->paginate($paginateBy);
        //dd($products);
        return $products;
    }

    public static function filterWarehouseProductsByParameters(array $filterParameters){

        $productCodes =WHProductCategoryFilterView::whereIn('category_code',$filterParameters['category_codes'])
        ->when($filterParameters['min_price'] && $filterParameters['max_price'],function ($query) use($filterParameters){
            $query->whereBetween('storePrice',[$filterParameters['min_price'], $filterParameters['max_price']]);
        })->whereIn('warehouse_code',$filterParameters['warehouse_codes'])
            ->groupBy('product_code')
            ->pluck('product_code')
            ->toArray();

        $products = ProductMaster::whereIn('product_code',$productCodes)
            ->whereHas('warehouseProducts',function($query) use ($filterParameters){
                $query->whereIn('warehouse_code',$filterParameters['warehouse_codes'])
                    ->qualifiedToDisplay()
                    ->havingRaw('SUM(warehouse_product_master.current_stock) > 0');
//                    ->whereHas('warehouseProductStockView',function ($query){
//                        $query->havingRaw('SUM(current_stock) > 0');
//                    });
            })->whereHas('unitPackagingDetails')
            ->paginate(ProductMaster::PRODUCT_PER_PAGE);


        return $products;
    }
}
