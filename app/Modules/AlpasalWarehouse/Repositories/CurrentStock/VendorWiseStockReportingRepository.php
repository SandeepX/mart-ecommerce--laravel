<?php


namespace App\Modules\AlpasalWarehouse\Repositories\CurrentStock;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use Illuminate\Support\Facades\DB;

class VendorWiseStockReportingRepository
{

    public function getVendorWiseCurrentStock($warehouseCode,$filterParameters,$paginatedBy = 10)
    {
        $vendorWiseCurrentStocks = WarehouseProductMaster::select(
            'warehouse_product_master.warehouse_product_master_code',
            'warehouses.warehouse_name',
            'vendors_detail.vendor_name',
            'vendors_detail.vendor_code'
        )
        ->join('vendors_detail',function($join) use($filterParameters){
            $join->on('warehouse_product_master.vendor_code','vendors_detail.vendor_code')
                ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                    $query->where('warehouse_product_master.vendor_code',$filterParameters['vendor_code']);
                });
        })
//        ->join('warehouse_product_stock_view',function($join){
//            $join->on('warehouse_product_master.warehouse_product_master_code','warehouse_product_stock_view.code')
//                ->where('warehouse_product_stock_view.current_stock','>',0);
//        })
        ->join('warehouses','warehouse_product_master.warehouse_code','=','warehouses.warehouse_code')
        ->where('warehouse_product_master.warehouse_code',$warehouseCode)
        ->where('warehouse_product_master.current_stock','>',0)
        ->addSelect(DB::raw('COUNT(warehouse_product_master.warehouse_product_master_code) as total_products'))
        ->groupBy('vendors_detail.vendor_code')
        //->get();
        ->paginate($paginatedBy);
        //dd($vendorWiseCurrentStocks);
        return $vendorWiseCurrentStocks;
    }

    public function getVendorWiseProduct($warehouseCode,$vendorCode,$filterParameters)
    {
        $vendorWiseProducts = WarehouseProductMaster::select(
            'warehouse_product_master.warehouse_product_master_code',
            'warehouse_product_master.is_active',
            'products_master.product_code',
            'products_master.product_name',
            'warehouse_product_master.current_stock',
            'product_variants.product_variant_name',
            'product_variants.product_variant_code',
            'product_packaging_details.product_packaging_detail_code',

            'product_packaging_details.micro_unit_code',
            'micro_package_name.id as micro_unit_id',
            'micro_package_name.package_code as micro_unit_code',
            'micro_package_name.package_name as micro_unit_name',
            'micro_package_name.remarks as micro_unit_remarks',

            'product_packaging_details.unit_code',
            'unit_package_name.id as unit_id',
            'unit_package_name.package_code as unit_code',
            'unit_package_name.package_name as unit_name',
            'unit_package_name.remarks as unit_remarks',


            'product_packaging_details.macro_unit_code',
            'macro_package_name.id as macro_unit_id',
            'macro_package_name.package_code as macro_unit_code',
            'macro_package_name.package_name as macro_unit_name',
            'macro_package_name.remarks as macro_unit_remarks',

            'product_packaging_details.super_unit_code',
            'super_package_name.id as super_unit_id',
            'super_package_name.package_code as super_unit_code',
            'super_package_name.package_name as super_unit_name',
            'super_package_name.remarks as super_unit_remarks',

            'product_packaging_details.micro_to_unit_value',
            'product_packaging_details.unit_to_macro_value',
            'product_packaging_details.macro_to_super_value'
        )
            ->join('products_master',function($join) use($filterParameters){
                $join->on('warehouse_product_master.product_code','products_master.product_code')
                    ->when(isset($filterParameters['product_name']), function ($query) use ($filterParameters) {
                        $query->where('products_master.product_name', 'like', '%' . $filterParameters['product_name'] . '%');
                    });
            })
            ->leftJoin('product_variants',function($join){
                $join->on('warehouse_product_master.product_variant_code','product_variants.product_variant_code');
            })
//            ->join('warehouse_product_stock_view',function($join){
//                $join->on('warehouse_product_master.warehouse_product_master_code','warehouse_product_stock_view.code')
//                    ->where('warehouse_product_stock_view.current_stock','>',0);
//            })
            ->leftJoin('product_packaging_details', function ($join) {
                $join->on(function($join){
                    $join->on('product_packaging_details.product_code','=', 'products_master.product_code');
                    $join->on(function ($q){
                        $q->on('product_packaging_details.product_variant_code','=', 'product_variants.product_variant_code')
                            ->orWhere(function($q){
                                $q->where('product_packaging_details.product_variant_code',null)->where('product_variants.product_variant_code',null);
                            });
                    });
                });
            })
            ->leftJoin('package_types as unit_package_name', function ($join) {
                $join->on('unit_package_name.package_code',
                    '=',
                    'product_packaging_details.unit_code')->whereNull('product_packaging_details.deleted_at');
            })->leftJoin('package_types as micro_package_name', function ($join) {
                $join->on('micro_package_name.package_code',
                    '=',
                    'product_packaging_details.micro_unit_code')
                    ->whereNull('product_packaging_details.deleted_at');
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
            ->where('warehouse_product_master.warehouse_code',$warehouseCode)
            ->where('warehouse_product_master.vendor_code',$vendorCode)
            ->get();

        return $vendorWiseProducts;
    }


}
