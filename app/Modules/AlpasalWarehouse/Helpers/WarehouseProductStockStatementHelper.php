<?php

namespace App\Modules\AlpasalWarehouse\Helpers;

use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransfer;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseTransferStock;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStock;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\StoreOrder;
use Illuminate\Support\Facades\DB;

class WarehouseProductStockStatementHelper
{

    public static function getWarehouseProductStockStatement($filterParameters,$with =[]){

        $productCode = NULL;
        $productVariantCode = NULL;
        if($filterParameters['product_code']){
            $productVariant = explode('-',$filterParameters['product_code']);
            $productCode = $productVariant[0];
            if(isset($productVariant[1]) && $productVariant[1]){
                $productVariantCode = $productVariant[1];
            }
        }
           $warehouseProductsStocks =  WarehouseProductStock::with($with)->select(
                                          'warehouse_product_stock.id',
                                          'warehouse_product_stock.warehouse_product_master_code',
                                          'warehouse_product_master.product_code',
                                          'warehouse_product_master.product_variant_code',
                                          'warehouse_product_stock.quantity',
                                          'warehouse_product_stock.package_qty',
                                          'warehouse_product_stock.package_code',
                                          'warehouse_product_stock.product_packaging_history_code',
                                          'warehouse_product_stock.reference_code',
                                          'warehouse_product_stock.action',
                                          'warehouse_product_stock.created_at',
                                          'warehouse_product_master.current_stock',
                                          DB::raw('GROUP_CONCAT(CONCAT(warehouse_product_stock.package_qty," ",package_types.package_name)," " ORDER BY warehouse_product_stock.id DESC) as package')
                                       )
                                       ->whereHas('warehouseProductMaster',function ($query) use ($filterParameters,$productCode,$productVariantCode){
                                               $query->where('warehouse_code',$filterParameters['warehouse_code'])
                                                ->when($filterParameters['vendor_code'],function ($query) use ($filterParameters){
                                                           $query->whereIn('vendor_code',$filterParameters['vendor_code']);
                                                })
                                               ->when($productCode,function ($query) use ($productCode){
                                                   $query->where('product_code',$productCode);
                                               })
                                               ->when($productVariantCode,function ($query) use ($productVariantCode){
                                                   $query->where('product_variant_code',$productVariantCode);
                                               });
                                       })
                                       ->when($filterParameters['stock_action'],function ($query) use ($filterParameters){
                                           $query->where('action',$filterParameters['stock_action']);
                                       })
                                       ->when($filterParameters['start_date'],function ($query) use ($filterParameters){
                                           $query->where('warehouse_product_stock.created_at','>=',$filterParameters['start_date']);
                                       })
                                       ->when($filterParameters['end_date'],function ($query) use ($filterParameters){
                                           $query->where('warehouse_product_stock.created_at','<=',$filterParameters['end_date']);
                                       })
                                        ->join('warehouse_product_master',
                                               'warehouse_product_master.warehouse_product_master_code','=','warehouse_product_stock.warehouse_product_master_code')
                                        ->leftJoin('package_types','package_types.package_code','=','warehouse_product_stock.package_code')                ->addSelect(DB::raw('
                                                    CASE WHEN FIND_IN_SET(warehouse_product_stock.action,"' . implode(',', WarehouseProductStock::INCREMENTS_TYPES) . '") = 0 then "out" else "in" end as stock_changing_type
                                        '))
                                         ->where('warehouse_product_stock.created_at','>=',$filterParameters['report_filter_date'])
                                        ->groupBy('warehouse_product_stock.warehouse_product_master_code', 'warehouse_product_stock.action',DB::raw('IFNULL(warehouse_product_stock.reference_code,warehouse_product_stock.id)'))
                                        ->orderBy('warehouse_product_stock.id','desc');

                                        if($filterParameters['download_excel']){
                                            $warehouseProductsStocks =  $warehouseProductsStocks->get();
                                        }else{
                                            $warehouseProductsStocks =  $warehouseProductsStocks->paginate(20);
                                        }
                             return $warehouseProductsStocks;
    }

    public static function getStockReportOfWarehouseProductByWarehouseProductMasterCode(
       $warehouseCode,
       $warehouseProductMasterCode,
       $filterParameters
    ){

            $incrementingTypes = implode(',', WarehouseProductStock::INCREMENTS_TYPES);
            $warehouseProductsStocks =  WarehouseProductStock::select(
                                'warehouse_product_stock.id',
                                'warehouse_product_stock.warehouse_product_master_code',
                                'warehouse_product_master.product_code',
                                'warehouse_product_master.product_variant_code',
                                'warehouse_product_stock.quantity',
                                'warehouse_product_stock.package_qty',
                                'warehouse_product_stock.package_code',
                                'warehouse_product_stock.product_packaging_history_code',
                                'warehouse_product_stock.reference_code',
                                'warehouse_product_stock.action',
                                'warehouse_product_stock.created_at',
                                DB::raw('GROUP_CONCAT(CONCAT(warehouse_product_stock.package_qty," ",package_types.package_name)," " ORDER BY warehouse_product_stock.id DESC) as package'),
                                DB::raw("SUM(case when ( FIND_IN_SET(warehouse_product_stock.action,'". $incrementingTypes."'))
                                                then warehouse_product_stock.quantity
                                                else -1 * warehouse_product_stock.quantity
                                                end) over (order by warehouse_product_stock.id) as current_stock_cum
                                              ")
                            )
                                ->whereHas('warehouseProductMaster',function ($query) use ($warehouseCode){
                                    $query->where('warehouse_code',$warehouseCode);
                                })
                                ->join('warehouse_product_master',
                                    'warehouse_product_master.warehouse_product_master_code','=','warehouse_product_stock.warehouse_product_master_code')
                                ->leftJoin('package_types','package_types.package_code','=','warehouse_product_stock.package_code')
                                ->join('warehouse_product_stock_view','warehouse_product_stock_view.code','=','warehouse_product_master.warehouse_product_master_code')
                                ->when($warehouseProductMasterCode,function ($query) use ($warehouseProductMasterCode){
                                    $query->where('warehouse_product_master.warehouse_product_master_code',$warehouseProductMasterCode);
                                })
                                ->when($filterParameters['stock_action'],function ($query) use ($filterParameters){
                                    $query->where('action',$filterParameters['stock_action']);
                                })
                                ->when($filterParameters['start_date'],function ($query) use ($filterParameters){
                                    $query->where('warehouse_product_stock.created_at','>=',$filterParameters['start_date']);
                                })
                                ->when($filterParameters['end_date'],function ($query) use ($filterParameters){
                                    $query->where('warehouse_product_stock.created_at','<=',$filterParameters['end_date']);
                                })
                                ->where('warehouse_product_stock.created_at','>=',$filterParameters['report_filter_date'])
                                ->addSelect(DB::raw('
                                                                        CASE WHEN FIND_IN_SET(warehouse_product_stock.action,"' . $incrementingTypes . '") = 0 then "out" else "in" end as stock_changing_type
                                                            '))
                                ->groupBy('warehouse_product_stock.warehouse_product_master_code','warehouse_product_stock.action',DB::raw('IFNULL(warehouse_product_stock.reference_code,warehouse_product_stock.id)'))
                                ->orderBy('warehouse_product_stock.id','desc');

                    if($filterParameters['download_excel']){
                        $warehouseProductsStocks = $warehouseProductsStocks->get();
                    }else{
                        $warehouseProductsStocks = $warehouseProductsStocks->paginate(20);
                    }

            return $warehouseProductsStocks;

    }

    public static function generateStockStatementReferenceLink($action,$referenceCode){

        $data = [
            'link' => NULL,
            'value' => NULL
        ];
        $referenceCodeLinks = [
            'sales' => [
                    'link' =>  (isset($referenceCode)) ? route('admin.store.orders.show',$referenceCode) : NULL
            ],
            'sales-return'=>[
                    'link' => (isset($referenceCode)) ? route('admin.store.orders.show',$referenceCode ) : NULL
            ],
            'preorder_sales' => [
                    'link' => (isset($referenceCode)) ? route('admin.store.pre-orders.show',$referenceCode) : NULL
            ]
        ];
        if(isset($referenceCodeLinks[$action]['link'])){
            $data['link'] = $referenceCodeLinks[$action]['link'];
        }

        if(($action == 'stock-transfer' || $action ==  'received-stock-transfer') && $referenceCode){
            $warehouseStockTransfer = WarehouseStockTransfer::with('sourceWarehouses','destinationWarehouses')
                                                              ->where('stock_transfer_master_code',$referenceCode)
                                                              ->first();

            $data['value'] = $warehouseStockTransfer->sourceWarehouses->warehouse_name. ' to '.$warehouseStockTransfer->destinationWarehouses->warehouse_name;
        }elseif(($action == 'sales' || $action== 'sales-return') && $referenceCode){
            $storeOrder = StoreOrder::with('store')->where('store_order_code',$referenceCode)->first();
            $data['value'] = $storeOrder->store->store_name;
        }elseif($action == 'preorder_sales' && $referenceCode){
            $storePreOrder = StorePreOrder::with('store')->where('store_preorder_code',$referenceCode)->first();
            $data['value'] = $storePreOrder->store->store_name;
        }

        return $data;

    }



}
