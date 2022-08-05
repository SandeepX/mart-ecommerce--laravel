<?php


namespace App\Modules\Vendor\Helpers;

use App\Modules\Product\Models\ProductMaster;
use DB;
use Illuminate\Http\Request;

class VendorWiseProductFilter
{

    public static function apply(Request $request, $paginate)
    {

        $products = (new ProductMaster)->newQuery();

        $products->when(!is_null($request->project_name), function ($query) use ($request) {
            return $query->where('project_id', $request->project_name);
        })->when(!is_null($request->task_status), function ($query) use ($request) {
            return $query->where('task_status', $request->task_status);
        });


        $paginate = $request->filled('records_per_page') ? $request->records_per_page : $paginate;

        return $products->paginate($paginate);
    }


    public static function filterPaginatedVendorProducts($filterParameters,$paginateBy)
    {

//        $products =  ProductMaster::when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
//            $query->where('vendor_code', $filterParameters['vendor_code']);
//        });
//        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;
//
//        $products = $products->latest()->paginate($paginateBy);
//


        $products = ProductMaster::with(['brand', 'category', 'package.packageType'])
            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
            $query->where('vendor_code', $filterParameters['vendor_code']);
        })
        ->when(isset($filterParameters['product_name']), function ($query) use ($filterParameters) {
            $query->where('product_name', 'like', '%' . $filterParameters['product_name'] . '%');
        })
        ->when(isset($filterParameters['category_name']), function ($query) use ($filterParameters) {
            $query->whereHas('category', function ($query) use ($filterParameters) {
                $query->where('category_name', 'like', '%' . $filterParameters['category_name'] . '%');
            });
        })
        ->when(isset($filterParameters['brand_name']), function ($query) use ($filterParameters) {
            $query->whereHas('brand', function ($query) use ($filterParameters) {
                $query->where('brand_name', 'like', '%' . $filterParameters['brand_name'] . '%');
            });
        })
        ->when(isset($filterParameters['category_codes']) && !empty($filterParameters['category_codes']), function ($query) use ($filterParameters) {
            $query->whereHas('categories', function ($query) use ($filterParameters) {
                $query->whereIn('product_category.category_code',$filterParameters['category_codes']);
            });
        })
        ->when(isset($filterParameters['brand_codes']) && !empty($filterParameters['brand_codes']), function ($query) use ($filterParameters) {
            $query->whereIn('brand_code', $filterParameters['brand_codes']);
        })
        ->when(isset($filterParameters['package_type']), function ($query) use ($filterParameters) {
            $query->whereHas('package', function ($query) use ($filterParameters) {
                $query->whereHas('packageType', function ($query) use ($filterParameters) {
                    $query->where('package_name', 'like', '%' . $filterParameters['package_type'] . '%');
                });

            });
        })->when($filterParameters['has_price'],function ($query) use($filterParameters){
            if($filterParameters['has_price'] == 'yes'){
                return $query->whereHas('priceList'); 
            }
            return $query->whereDoesntHave('priceList');
        })->when($filterParameters['is_taxable'],function ($query) use($filterParameters){
            if($filterParameters['is_taxable'] == 'yes'){
                return $query->where('is_taxable',1); 
            }
            return $query->where('is_taxable',0); 
        })->when($filterParameters['is_active'],function ($query) use($filterParameters){
            if($filterParameters['is_active'] == 'yes'){
                return $query->where('is_active',1);
            }
            return $query->where('is_active',0);
        });


        //for global search
        $products = $products->when(isset($filterParameters['global_search_keyword']), function ($q) use ($filterParameters) {

            $q->where(function ($query) use ($filterParameters) {
                $query->where('product_name', 'like', '%' . $filterParameters['global_search_keyword'] . '%')
                    ->orWhereHas('category', function ($query) use ($filterParameters) {
                        $query->where('category_name', 'like', '%' . $filterParameters['global_search_keyword'] . '%');
                    })->orWhereHas('brand', function ($query) use ($filterParameters) {
                        $query->where('brand_name', 'like', '%' . $filterParameters['global_search_keyword'] . '%');
                    })->orWhereHas('package', function ($query) use ($filterParameters) {
                        $query->whereHas('packageType', function ($query) use ($filterParameters) {
                            $query->where('package_name', 'like', '%' . $filterParameters['global_search_keyword'] . '%');
                        });
                    });
            });

        });
        // {{base_url}}vendor/products?category_name=r&product_name=s&brand_name=a&package_type=bora

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $products = $products->latest()->paginate($paginateBy);
        return $products;
    }


    public static function filterPaginatedVendorQualifiedProducts($filterParameters,$paginateBy)
    {

        $products = ProductMaster::with(['brand', 'category', 'package.packageType'])
            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->where('vendor_code', $filterParameters['vendor_code']);
            })
            ->when(isset($filterParameters['product_name']), function ($query) use ($filterParameters) {
                $query->where('product_name', 'like', '%' . $filterParameters['product_name'] . '%');
            })
            ->when(isset($filterParameters['category_name']), function ($query) use ($filterParameters) {
                $query->whereHas('category', function ($query) use ($filterParameters) {
                    $query->where('category_name', 'like', '%' . $filterParameters['category_name'] . '%');
                });
            })
            ->when(isset($filterParameters['brand_name']), function ($query) use ($filterParameters) {
                $query->whereHas('brand', function ($query) use ($filterParameters) {
                    $query->where('brand_name', 'like', '%' . $filterParameters['brand_name'] . '%');
                });
            })
            ->when(isset($filterParameters['category_codes']) && !empty($filterParameters['category_codes']), function ($query) use ($filterParameters) {
                $query->whereHas('categories', function ($query) use ($filterParameters) {
                    $query->whereIn('product_category.category_code',$filterParameters['category_codes']);
                });
            })
            ->when(isset($filterParameters['brand_codes']) && !empty($filterParameters['brand_codes']), function ($query) use ($filterParameters) {
                $query->whereIn('brand_code', $filterParameters['brand_codes']);
            })
            ->when(isset($filterParameters['package_type']), function ($query) use ($filterParameters) {
                $query->whereHas('package', function ($query) use ($filterParameters) {
                    $query->whereHas('packageType', function ($query) use ($filterParameters) {
                        $query->where('package_name', 'like', '%' . $filterParameters['package_type'] . '%');
                    });

                });
            });

        // {{base_url}}vendor/products?category_name=r&product_name=s&brand_name=a&package_type=bora

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $products = $products->qualifiedToDisplay()->latest()->paginate($paginateBy)->withQueryString();
        return $products;
    }
}