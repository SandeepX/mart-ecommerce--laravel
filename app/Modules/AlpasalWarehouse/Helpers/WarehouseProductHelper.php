<?php


namespace App\Modules\AlpasalWarehouse\Helpers;


use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Product\Models\ProductMaster;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseProductHelper
{

    public static function doesActiveProductBelongsToAnyWarehouses($warehouseCodes,$productCode,$productVariantCode=null){
        $warehouseProducts= WarehouseProductMaster::whereIn('warehouse_code',$warehouseCodes)
            ->where('product_code',$productCode)->where('product_variant_code',$productVariantCode)
            ->where('is_active',1)
            ->whereHas('warehouseProducts.warehouseProductStockView',function ($query){
                $query->havingRaw('SUM(current_stock) > 0');
            })
            ->count();

        if ($warehouseProducts > 0){
            return true;
        }

        return  false;

    }

    public static function doesActiveProductBelongsToWarehouse($warehouseCode,$productCode,$productVariantCode=null){
        $warehouseProducts= WarehouseProductMaster::where('warehouse_code',$warehouseCode)
            ->where('product_code',$productCode)->where('product_variant_code',$productVariantCode)
            ->where('is_active',1)->count();

        if ($warehouseProducts > 0){
            return true;
        }

        return  false;
    }

    public static function findWarehouseProductByWarehouseCode($warehouseCode,$productCode,$productVariantCode=null,$with=[],$select='*'){
        //dd($warehouseCode,$productCode,$productVariantCode=null,$with,$select);
        return WarehouseProductMaster::with($with)->select($select)->where('warehouse_code',$warehouseCode)
            ->where('product_code',$productCode)->where('product_variant_code',$productVariantCode)->first();
    }

    public static function findActiveWarehouseProductByWarehouseCode($warehouseCode,$productCode,$productVariantCode=null){
        return WarehouseProductMaster::where('warehouse_code',$warehouseCode)->where('is_active',1)
            ->where('product_code',$productCode)->where('product_variant_code',$productVariantCode)->first();

    }

    public static function findOrFailQualifiedWarehouseProductBySlug($warehouseCode,$productSlug,$with=[],$select='*'){


        $warehouseProduct= ProductMaster::with($with)->select($select)
            ->whereHas('warehouseProducts',
            function ($query) use ($warehouseCode){
                $query->where('warehouse_code',$warehouseCode)
                    ->qualifiedToDisplay()
                    ->havingRaw('SUM(current_stock) > 0');
//                    ->whereHas('warehouseProductStockView',function ($query){
//                    $query->havingRaw('SUM(current_stock) > 0');
//                });
            })->whereHas('unitPackagingDetails')
            ->where('slug',$productSlug)
            ->first();

        //dd($warehouseProduct);

        if(!$warehouseProduct){
            throw new Exception('No Product Found in the warehouse',404);
        }

        return $warehouseProduct;


    }

    public static function getMinMaxQtyLimitOfWarehouseProduct($warehouseCode,$productCode,$productVariantCode = null){
        $wpm = WarehouseProductMaster::where('warehouse_code',$warehouseCode)
                                     ->where('product_code',$productCode)
                                     ->where('product_variant_code',$productVariantCode)
                                     ->select('min_order_quantity','max_order_quantity')
                                     ->first();
        if(!$wpm){
            throw new Exception('could not get such product from the warehouse');
        }
        return [
            'min_order_quantity'=>$wpm->min_order_quantity ?? "N/A",
            'max_order_quantity'=>$wpm->max_order_quantity ?? "N/A"
        ];
    }

    public static function getWarehouseVendorListByWarehouseCode($warehouseCode,$filterParameters){

                $warehousePreOrderVendors = WarehousePreOrderProduct::select(
                                    'vendors_detail.vendor_code',
                                    'vendors_detail.vendor_name'
                                    )
                                    ->join('warehouse_preorder_listings',function ($join) use ($warehouseCode){
                                            $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code','=','warehouse_preorder_products.warehouse_preorder_listing_code')
                                                ->where('warehouse_code',$warehouseCode);
                                    })
                                    ->join('products_master','products_master.product_code','=','warehouse_preorder_products.product_code')
                                    ->join('vendors_detail','products_master.vendor_code','=','vendors_detail.vendor_code')
                                    ->when($filterParameters['vendor_name'],function ($query) use ($filterParameters){
                                        $query->where('vendors_detail.vendor_name','LIKE','%'.$filterParameters['vendor_name'].'%');
                                    });

                $warehouseVendors = WarehouseProductMaster::select(
                                                   'vendors_detail.vendor_code',
                                                   'vendors_detail.vendor_name'
                                                  )
                                                  ->where('warehouse_code',$warehouseCode)
                                                  ->when($filterParameters['vendor_name'],function ($query) use ($filterParameters){
                                                     $query->where('vendors_detail.vendor_name','LIKE','%'.$filterParameters['vendor_name'].'%');
                                                  })
                                                  ->join('vendors_detail','warehouse_product_master.vendor_code','=','vendors_detail.vendor_code')
                                                  ->groupBy('warehouse_product_master.vendor_code')
                                                  ->orderBy('vendors_detail.created_at')
                                                  ->union($warehousePreOrderVendors)
                                                  ->paginate($filterParameters['paginate_by']);
       return  $warehouseVendors;
    }

    public static function getProductsofWarehouseWithVariantByWarehouseCode($warehouseCode,$filterParameters){

        $preOrderProducts = WarehousePreOrderProduct::select(
                                                             'products_master.product_code',
                                                             DB::raw('CONCAT(products_master.product_name,
                                                             CASE when product_variants.product_variant_name IS NULL THEN "" ELSE "("  END,
                                                             COALESCE(product_variants.product_variant_name,""),
                                                             CASE when product_variants.product_variant_name IS NULL THEN "" ELSE ")"  END
                                                             ) as name'),
                                                        'product_variants.product_variant_code'
                                                )
                                                ->join('warehouse_preorder_listings',function ($join) use ($warehouseCode){
                                                     $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code','=','warehouse_preorder_products.warehouse_preorder_listing_code')
                                                          ->where('warehouse_code',$warehouseCode);
                                                   })
                                                ->join('products_master','warehouse_preorder_products.product_code','=','products_master.product_code')
                                                ->leftJoin('product_variants','warehouse_preorder_products.product_variant_code','=','product_variants.product_variant_code')
                                                ->when($filterParameters['product_name'],function ($query) use ($filterParameters){
                                                    $query->having('name','LIKE','%'.$filterParameters['product_name'].'%');
                                                });


        $products = WarehouseProductMaster::select(
                                            'products_master.product_code',
                                            DB::raw('CONCAT(products_master.product_name,
                                            CASE when product_variants.product_variant_name IS NULL THEN "" ELSE "("  END,
                                            COALESCE(product_variants.product_variant_name,""),
                                             CASE when product_variants.product_variant_name IS NULL THEN "" ELSE ")"  END
                                            ) as name'),
                                            'product_variants.product_variant_code'
                                            )
                                           ->where('warehouse_code',$warehouseCode)
                                           ->when($filterParameters['vendor_code'],function ($query) use ($filterParameters){
                                               $vendorsCodes = explode(',',$filterParameters['vendor_code']);
                                               $query->whereIn('warehouse_product_master.vendor_code',$vendorsCodes);
                                           })
                                           ->join('products_master','warehouse_product_master.product_code','=','products_master.product_code')
                                           ->leftJoin('product_variants','warehouse_product_master.product_variant_code','=','product_variants.product_variant_code')
                                            ->when($filterParameters['product_name'],function ($query) use ($filterParameters){
                                                $query->having('name','LIKE','%'.$filterParameters['product_name'].'%');
                                            })
                                           ->union($preOrderProducts)
                                           ->paginate($filterParameters['paginate_by']);
        return $products;
    }





}
