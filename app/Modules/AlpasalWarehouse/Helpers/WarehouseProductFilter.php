<?php


namespace App\Modules\AlpasalWarehouse\Helpers;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStockView;
use App\Modules\Product\Models\ProductMaster;

use Illuminate\Support\Facades\DB;
class WarehouseProductFilter
{

    public static function oldFilterPaginatedWarehouseProducts($filterParameters, $paginateBy, $with = [])
    {

        $products = ProductMaster::with($with)
            ->when(isset($filterParameters['product_name']), function ($query) use ($filterParameters) {
                $query->where('products_master.product_name', 'like', '%' . $filterParameters['product_name'] . '%');
            })


            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->where('products_master.vendor_code',$filterParameters['vendor_code']);
            })
            ->when(isset($filterParameters['warehouse_codes'])&& !empty($filterParameters['warehouse_codes']), function ($query) use ($filterParameters) {
                $query->join('warehouse_product_master', function ($join) use ($filterParameters){
                    $join->on('warehouse_product_master.product_code', '=', 'products_master.product_code')
                        ->whereIn('warehouse_product_master.warehouse_code', $filterParameters['warehouse_codes']);
                });
            });


        //dd($products);

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $products = $products->orderBy('products_master.created_at', 'DESC')->paginate($paginateBy);
        return $products;
    }

    public static function filterPaginatedWarehouseProducts($filterParameters, $paginateBy, $with = [])
    {

        $products = WarehouseProductMaster::with($with)
            ->select('*')
            ->when(isset($filterParameters['product_name']), function ($query) use ($filterParameters) {
                $query->whereHas('product', function ($query) use ($filterParameters) {
                    $query->where('products_master.product_name', 'like', '%' . $filterParameters['product_name'] . '%');
                });
            })
            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_product_master.vendor_code',$filterParameters['vendor_code']);
            })

            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('is_active',$filterParameters['status']);
            })

            ->when(isset($filterParameters['warehouse_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_product_master.warehouse_code', $filterParameters['warehouse_code']);
            })

            ->addSelect(DB::raw('count(product_variant_code) as totalVariants'))
            ->addSelect(DB::raw('count(Case is_active when "1" then is_active  End) as active_product '))
            ->addSelect(DB::raw('count(Case is_active when "0" then is_active  End) as inactive_product '))

            ->groupBy('product_code');

       //dd($products);

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        if(isset($filterParameters['status']) && ($filterParameters['status'])==1) {
            $products = $products->groupBy('warehouse_product_master.product_code')
                ->orderBy('active_product', 'DESC')->paginate($paginateBy)->withQueryString();
        }
        elseif(isset($filterParameters['status']) && ($filterParameters['status'])==0) {
            $products = $products->groupBy('warehouse_product_master.product_code')
                ->orderBy('active_product', 'DESC')->paginate($paginateBy)->withQueryString();
        }else{
            $products = $products->orderBy('warehouse_product_master.created_at', 'DESC')
                ->groupBy('warehouse_product_master.product_code')->paginate($paginateBy)->withQueryString();
        }



        //dd($products);
        return $products;
    }

}
