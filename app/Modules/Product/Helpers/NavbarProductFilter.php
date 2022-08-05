<?php


namespace App\Modules\Product\Helpers;


use App\Modules\Product\Models\ProductMaster;

class NavbarProductFilter
{
    //for navbar search
    //only qualified products
    public static function filterPaginatedWarehouseProducts($filterParameters, $paginateBy, $with = [])
    {

        $issetWarehouseCodes=(isset($filterParameters['warehouse_codes'])&& !empty($filterParameters['warehouse_codes'])) ? true :false;
      //  dd($issetWarehouseCodes);
        $products = ProductMaster::with($with)
            ->whereHas('unitPackagingDetails')
            ->when(isset($filterParameters['search_keyword']), function ($query) use ($filterParameters) {
                $query->where('product_name', 'like', '%' . $filterParameters['search_keyword'] . '%')
                    ->orWhereHas('category', function ($query) use ($filterParameters) {
                        $query->where('category_name', 'like', '%' . $filterParameters['search_keyword'] . '%');
                    })->orWhereHas('brand', function ($query) use ($filterParameters) {
                        $query->where('brand_name', 'like', '%' . $filterParameters['search_keyword'] . '%');
                    });
            })
            ->when($issetWarehouseCodes, function ($query) use ($filterParameters) {
                /*  $query->whereHas('warehouseProducts', function ($query) use ($filterParameters) {
                      $query->where('warehouse_product_master.warehouse_code','AW3');
                  });*/
                $query
                ->join('warehouse_product_master', function ($join) use ($filterParameters){
                    $join->on('warehouse_product_master.product_code', '=', 'products_master.product_code')
                        ->whereIn('warehouse_product_master.warehouse_code', $filterParameters['warehouse_codes'])
                        ->where('warehouse_product_master.is_active',1);

                })->join('warehouse_product_price_master', function ($join) use ($filterParameters){
                    $join->on('warehouse_product_master.warehouse_product_master_code', '=',
                        'warehouse_product_price_master.warehouse_product_master_code');
                })
                    ->join('warehouse_product_stock_view',function ($join){
                        $join->on('warehouse_product_master.warehouse_product_master_code', '=',
                            'warehouse_product_stock_view.code')
                        ->where('warehouse_product_stock_view.current_stock','>',0);
                    });

                });

       //dd($products->get());


        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        if ($issetWarehouseCodes){
            $products= $products->groupBy('products_master.product_code','warehouse_product_master.warehouse_code');
        }
        else{
            $products= $products->qualifiedToDisplay()->groupBy('products_master.product_code');
        }

       // $products = $products->qualifiedToDisplay()->orderBy('products_master.created_at', 'DESC')->paginate($paginateBy);
        $products = $products->orderBy('products_master.created_at', 'DESC')->paginate($paginateBy);
      // dd($products);
        return $products;
    }

    public static function newPaginatedSearchOfWarehouseProducts($filterParameters, $paginateBy, $with = []){

        $issetWarehouseCodes=(isset($filterParameters['warehouse_codes'])&& !empty($filterParameters['warehouse_codes'])) ? true :false;

        $products = ProductMaster::with($with)
            ->select(
                'products_master.product_name',
                'products_master.product_code',
                'products_master.slug',
                'products_master.brand_code',
                'products_master.category_code'
            )
            ->whereHas('unitPackagingDetails')
         //   ->withCount('productVariants')
            ->when(isset($filterParameters['search_keyword']), function ($query) use ($filterParameters) {
                $query->where('product_name', 'like',  '%'.$filterParameters['search_keyword'] . '%');
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
//                    ->join('warehouse_product_stock_view',function ($join){
//                        $join->on('warehouse_product_master.warehouse_product_master_code', '=',
//                            'warehouse_product_stock_view.code')
//                            ->where('warehouse_product_stock_view.current_stock','>',0);
//                    });
            });


        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        if ($issetWarehouseCodes){
            $products= $products->groupBy('products_master.product_code','warehouse_product_master.warehouse_code');
        }
        else{
           $products= $products->qualifiedToDisplay()->groupBy('products_master.product_code');
        }

        if($filterParameters['search_keyword']){
            $products = $products->orderByRaw('LOCATE("'.$filterParameters['search_keyword'].'", `product_name`)', 'ASC')
            ->orderBy('product_name','ASC');
        }else{
            $products = $products->orderBy('products_master.created_at', 'DESC');
        }

        $products = $products->paginate($paginateBy);

        return $products;
    }


}
