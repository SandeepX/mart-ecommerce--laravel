<?php


namespace App\Modules\PricingLink\Helper;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\InvestmentPlan\Models\InvestmentPlan;
use App\Modules\Product\Helpers\ProductPackagingPriceHelper;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;
use Carbon\Carbon;
use Exception;

class ProductPricingHelper
{
    public static function getWarehouseWiseProducts($warehouseCode,$filterParameters,$paginatedBy=25)
    {

        $warehouseProducts = WarehouseProductMaster::select(
            'warehouse_product_master.warehouse_product_master_code',
            'warehouse_product_master.warehouse_code',
            'warehouse_product_stock.quantity',
            'products_master.product_name',
            'products_master.product_code',
            'product_variants.product_variant_name',
            'product_variants.product_variant_code',
            'product_packaging_details.product_packaging_detail_code',
            'product_packaging_details.micro_unit_code',
            'micro_package_name.package_name as micro_unit_name',
            'product_packaging_details.unit_code',
            'unit_package_name.package_name as unit_name',
            'product_packaging_details.macro_unit_code',
            'macro_package_name.package_name as macro_unit_name',
            'product_packaging_details.super_unit_code',
            'super_package_name.package_name as super_unit_name',
            'product_packaging_details.micro_to_unit_value',
            'product_packaging_details.unit_to_macro_value',
            'product_packaging_details.macro_to_super_value'
        )
            ->join('products_master',function($join){
                $join->on('warehouse_product_master.product_code','products_master.product_code');
            })
            ->leftJoin('product_variants',function($join){
                $join->on('warehouse_product_master.product_variant_code','product_variants.product_variant_code');
            })

            ->leftJoin('warehouse_product_stock',function($join){
                $join->on('warehouse_product_master.warehouse_product_master_code',
                    'warehouse_product_stock.warehouse_product_master_code')
                ->whereIn('warehouse_product_stock.action',['sales','preorder_sales']);
            })

            ->join('product_packaging_details', function ($join) {
                $join->on(function ($join) {
                    $join->on('product_packaging_details.product_code', '=', 'products_master.product_code')
                        ->on(function ($q) {
                            $q->on('product_packaging_details.product_variant_code', '=', 'product_variants.product_variant_code')
                                ->orWhere(function ($q) {
                                    $q->where('product_packaging_details.product_variant_code', null);//difference in cases
                                });
                        });
                })->whereNull('product_packaging_details.deleted_at');
            })
            ->leftJoin('package_types as micro_package_name', function ($join) {
                $join->on('micro_package_name.package_code',
                    '=',
                    'product_packaging_details.micro_unit_code')
                    ->whereNull('product_packaging_details.deleted_at');
            })->leftJoin('package_types as unit_package_name', function ($join) {
                $join->on('unit_package_name.package_code',
                    '=',
                    'product_packaging_details.unit_code')->whereNull('product_packaging_details.deleted_at');
            })->leftJoin('package_types as macro_package_name', function ($join) {
                $join->on('macro_package_name.package_code',
                    '=',
                    'product_packaging_details.macro_unit_code')
                    ->whereNull('product_packaging_details.deleted_at');
            })->leftJoin('package_types as super_package_name', function ($join) {
                $join->on('super_package_name.package_code',
                    '=',
                    'product_packaging_details.super_unit_code')
                    ->whereNull('product_packaging_details.deleted_at');
            })
            ->when(isset($filterParameters['product']),function ($query) use ($filterParameters){
                $query->whereIn('products_master.product_code', $filterParameters['product'] );
            })

            ->where('warehouse_product_master.warehouse_code',$warehouseCode)
            ->where('warehouse_product_master.is_active',1)
            ->where('warehouse_product_master.current_stock','>',0)
            ->groupBy(['products_master.product_code','product_variants.product_variant_code'])
            ->orderByRaw('SUM(warehouse_product_stock.quantity) DESC')
            ->paginate($paginatedBy);
        //->get();
       // dd($warehouseProducts);
        return $warehouseProducts;

    }

    public static function getProductPackageTypes($productCode,$productVariantCode)
    {
        $productUnitPackagingDetail = ProductUnitPackagingHelper::findProductPackagingDetail(
            $productCode,$productVariantCode);

        if (!$productUnitPackagingDetail){
            throw new Exception('Packaging not available for the product.');
        }

        return $productUnitPackagingDetail;

    }

    public static function isPastExpiresTime($expiresAt)
    {
        $currentTime=Carbon::now('Asia/Kathmandu')->toDateTimeString();
        if ($currentTime > $expiresAt){
            return true;
        }
        return false;
    }

    public static function getExireTime($expiresAt,$dateFormat='Y-m-d H:i:s'){
        return date($dateFormat,strtotime($expiresAt));
    }

    public static function getWarehouseAllProductsForFilter($warehouseCode)
    {
        $warehouseProductsForFilter = WarehouseProductMaster::select(
            'warehouse_product_master.warehouse_product_master_code',
            'warehouse_product_master.warehouse_code',
            'products_master.product_name',
            'products_master.product_code',
            'warehouse_product_master.is_active',
            'warehouse_product_master.current_stock'
//            'product_variants.product_variant_name',
//            'product_variants.product_variant_code'
        )
            ->join('products_master',function($join){
                $join->on('warehouse_product_master.product_code','products_master.product_code');
            })
//            ->leftJoin('product_variants',function($join){
//                $join->on('warehouse_product_master.product_variant_code','product_variants.product_variant_code');
//            })
            ->where('warehouse_product_master.warehouse_code',$warehouseCode)
            ->where('warehouse_product_master.is_active',1)
            ->where('warehouse_product_master.current_stock','>',0)
            ->groupBy(['products_master.product_code'])
            ->get();

        return $warehouseProductsForFilter;
    }
}

