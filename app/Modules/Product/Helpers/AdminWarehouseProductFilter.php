<?php


namespace App\Modules\Product\Helpers;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStockView;
use Illuminate\Support\Facades\DB;

class AdminWarehouseProductFilter
{

    public static function filterPaginatedWarehouseProduct($filterParameters,$paginateBy,$with){
        $products = WarehouseProductMaster::with($with)
            ->when(isset($filterParameters['product_name']), function ($query) use ($filterParameters) {
                $query->whereHas('product', function ($query) use ($filterParameters) {
                    $query->where('products_master.product_name', 'like', '%' . $filterParameters['product_name'] . '%');
                });
            })
            ->when(isset($filterParameters['product_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_product_master.product_code',$filterParameters['product_code']);
            })
            ->when(isset($filterParameters['vendor_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_product_master.vendor_code',$filterParameters['vendor_code']);
            })
            ->when(isset($filterParameters['warehouse_code']), function ($query) use ($filterParameters) {
                $query->where('warehouse_product_master.warehouse_code', $filterParameters['warehouse_code']);
            });


        //dd($products);

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $products = $products->orderBy('warehouse_product_master.created_at', 'DESC')
            ->paginate($paginateBy)->withQueryString();
        return $products;
    }

    public static function test($filterParameters,$paginateBy,$with){
       /* $products = WarehouseProductMaster::with($with)
            ->when(isset($filterParameters['product_code']), function ($query) use ($filterParameters) {
                //$query->where('warehouse_product_master.product_code',$filterParameters['product_code'])
                $query->where('warehouse_product_master.product_code',$filterParameters['product_code']);
            })  ->join('product_variants', function ($join) use ($filterParameters){
                $join->on('product_variants.product_variant_code', '=', 'warehouse_product_master.product_variant_code')
                    ->where('warehouse_product_master.product_code',$filterParameters['product_code']);
            })->join('warehouse_product_stock_view', function ($join) {
                $join->on('warehouse_product_stock_view.code', '=', 'warehouse_product_master.warehouse_product_master_code')
                    ->whereIn('warehouse_product_stock_view.code',DB::raw('count(*) as user_count, status'));
            });
        return $products->get();*/

       /* $currentStocks = WarehouseProductStockView::whereHas('warehouseProductMaster',function ($query){
            $query->where('product_code','P1119');
        })->groupBy('warehouse');*/

        $warehouses = Warehouse::join('warehouse_product_master', function ($join) use ($filterParameters){
            $join->on('warehouses.warehouse_code', '=', 'warehouse_product_master.warehouse_code');
        })->join('product_variants', function ($join) use ($filterParameters){
            $join->on('product_variants.product_variant_code', '=', 'warehouse_product_master.product_variant_code');
        })->join('warehouse_product_stock_view', function ($join) use ($filterParameters){
            $join->on('warehouse_product_stock_view.code', '=', 'warehouse_product_master.warehouse_product_master_code');
        })->where('warehouse_product_master.product_code','P1714')->get();
        dd($warehouses);

        //return $warehouses->groupBy('warehouse_code');
        /*$warehouses= $warehouses->mapToGroups(function ($item) {
            return [
               // 'warehouse_name' => $item->warehouse_name
                $item['warehouse_name'] =>[
                    'warehouse_code'=>$item['warehouse_code'],
                    'product_code' => $item['product_code'],
                    'product_variant_code' => $item['product_variant_code'],
                    'product_variant_name' => $item['product_variant_name'],
                    'current_stock' => $item['current_stock']

                ]
            ];
        });*/
        //dd($warehouses->groupBy('warehouse_code'));
        $warehouses= $warehouses->map(function ($item,$key) {

                // 'warehouse_name' => $item->warehouse_name
                $item['product_variants']=[
                    'product_variant_code' => $item['product_variant_code'],
                    'product_variant_name' => $item['product_variant_name'],
                    'current_stock' => $item['current_stock']
                ];

                return $item;

        });


       // dd($warehouses);
        return $warehouses;
    }

    public static function test2($filterParameters,$paginateBy,$with){

        $warehouses = Warehouse::rightJoin('warehouse_product_master', function ($join) use ($filterParameters){
            $join->on('warehouses.warehouse_code', '=', 'warehouse_product_master.warehouse_code');
        })->select('warehouses.warehouse_name','warehouse_product_master.*')
            ->where('warehouse_product_master.product_code','P1714')->get();

        dd($warehouses);

        $warehouses = $warehouses->mapToGroups(function ($item,$key){
            //dd($item);
            return [
                $item['warehouse_code'] => [
                    'warehouse_name'=>$item['warehouse_name'],
                    'warehouse_code' => $item['warehouse_code'],
                    'product_variant_code' => $item['product_variant_code']
                ],

            ];

        });
        $productVariants=array_merge_recursive((array)$warehouses['AW1001']);
        dd($productVariants);
        dd($warehouses['AW1001']);
        return $warehouses;
    }
}
