<?php


namespace App\Modules\Product\Helpers;

use App\Modules\Product\Models\ProductMaster;
use Illuminate\Http\Request;


class ProductFilter
{
    public function productFilterQuery(Request $request)
    {
        $productQuery = (new ProductMaster())->newQuery();

        $productQuery->when($request->filled('brand_code'), function($query) use ($request) {
            return $query->where('brand_code', $request->brand_code);
        });

        return $productQuery;
    }

    public function filterProducts(Request $request)
    {
        return  $this->productFilterQuery($request)->get();
    }

    public static function filterProductsWithPagination(Request $request,$paginateBy)
    {
        return productFilterQuery($request)->paginate($paginateBy);
    }

    public static function filterPaginatedProducts($filterParameters,$paginateBy,$with=[]){

        return ProductMaster::with($with)->when(isset($filterParameters['vendor_code']),function ($query) use($filterParameters){
            $query->where('vendor_code',$filterParameters['vendor_code']);
        })->when(isset($filterParameters['product_name']),function ($query) use($filterParameters){
            $query->where('product_name','like','%'.$filterParameters['product_name'] . '%');
        })->latest()->paginate($paginateBy);
    }



}
