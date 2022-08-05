<?php
namespace  App\Modules\AlpasalWarehouse\Database\seeds;

use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseReceivedStockTransferDetail;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransferDetail;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStock;
use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;

class WHReceivedStockTransferDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{

                $warehouseStockTransferDetails = WarehouseStockTransferDetail::whereNotNull('received_quantity')
                                                                              ->orderBy('created_at','ASC')
                                                                              ->get();
                $time_start = microtime(true);
                DB::beginTransaction();
                foreach($warehouseStockTransferDetails->chunk(1000) as $warehouseStockTransferDetails){
                    foreach ($warehouseStockTransferDetails as $warehouseStockTransferDetail) {

                        $warehouseProductMaster = WarehouseProductMaster::where('warehouse_product_master_code', $warehouseStockTransferDetail->warehouse_product_master_code)
                            ->firstOrFail();

                        $latestProductPackagingDetails =  ProductPackagingHistory::where('product_code',$warehouseProductMaster->product_code)
                            ->where('product_variant_code',$warehouseProductMaster->product_variant_code)
                            ->orderBy('created_at','DESC')
                            ->first();

                        if($latestProductPackagingDetails && $warehouseStockTransferDetail->created_at >= '2021-04-02 20:09:59'){

                            $productPackagingFormatter = new ProductPackagingFormatter();
                            $arr =[];
                            if ($latestProductPackagingDetails){
                                if ($latestProductPackagingDetails->micro_unit_code){
                                    $arr[1] =$latestProductPackagingDetails->micro_unit_code;
                                }
                                if ($latestProductPackagingDetails->unit_code){
                                    $arrKey = intval($latestProductPackagingDetails->micro_to_unit_value);
                                    $arr[$arrKey] =$latestProductPackagingDetails->unit_code;
                                }
                                if ($latestProductPackagingDetails->macro_unit_code){
                                    $arrKey = intval($latestProductPackagingDetails->micro_to_unit_value *
                                        $latestProductPackagingDetails->unit_to_macro_value);
                                    $arr[$arrKey] =$latestProductPackagingDetails->macro_unit_code;
                                }
                                if ($latestProductPackagingDetails->super_unit_code){
                                    $arrKey = intval($latestProductPackagingDetails->micro_to_unit_value *
                                        $latestProductPackagingDetails->unit_to_macro_value *
                                        $latestProductPackagingDetails->macro_to_super_value);

                                    $arr[$arrKey] =$latestProductPackagingDetails->super_unit_code;
                                }
                            }
                            $arr=array_reverse($arr,true);
                            $productPackagingDetails = $productPackagingFormatter->formatPackagingCombinationWithPackageCode(
                                $warehouseStockTransferDetail->received_quantity,
                                $arr
                            );

                            foreach($productPackagingDetails as $key => $productPackagingDetail){
                                $validatedReceivedStockData = [];
                                $validatedReceivedStockData['received_quantity'] = $productPackagingDetail['micro_quantity'];
                                $validatedReceivedStockData['package_quantity'] = $productPackagingDetail['package_quantity'];
                                $validatedReceivedStockData['package_code'] = $key;
                                $validatedReceivedStockData['product_packaging_history_code'] = $latestProductPackagingDetails->product_packaging_history_code;
                                $validatedReceivedStockData['warehouse_product_master_code'] = $warehouseStockTransferDetail->warehouse_product_master_code;
                                $validatedReceivedStockData['stock_transfer_master_code'] = $warehouseStockTransferDetail->stock_transfer_master_code;
                                $validatedReceivedStockData['created_by'] = $warehouseStockTransferDetail->created_by;
                                $validatedReceivedStockData['created_at'] = $warehouseStockTransferDetail->warehouseStockTransfer->created_at;
                                $validatedReceivedStockData['updated_at'] = $warehouseStockTransferDetail->warehouseStockTransfer->updated_at;

                                WarehouseReceivedStockTransferDetail::create($validatedReceivedStockData);
                            }
                        }else{
                            $validatedReceivedStockData = [];
                            $validatedReceivedStockData['stock_transfer_master_code'] =  $warehouseStockTransferDetail->stock_transfer_master_code;
                            $validatedReceivedStockData['warehouse_product_master_code'] = $warehouseStockTransferDetail->warehouse_product_master_code;
                            $validatedReceivedStockData['received_quantity'] = $warehouseStockTransferDetail->received_quantity;
                            $validatedReceivedStockData['created_by'] = $warehouseStockTransferDetail->created_by;
                            $validatedReceivedStockData['created_at'] = $warehouseStockTransferDetail->warehouseStockTransfer->created_at;
                            $validatedReceivedStockData['updated_at'] = $warehouseStockTransferDetail->warehouseStockTransfer->updated_at;
                            WarehouseReceivedStockTransferDetail::create($validatedReceivedStockData);
                        }

                        echo "\033[31m" . ' Warehouse Product Master Code: ' . $warehouseProductMaster->warehouse_product_master_code .
                            "\033[32m" . ' Received for  Warehouse Stock Transfer Details  Code: ' . $warehouseStockTransferDetail->stock_transfer_details_code . "\n";

                    }
                }

            echo " Successfully Completed "."\n";
            DB::commit();
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo '<b>Total Execution Time:</b> '.($execution_time)/60 .' Mins'.'\n';
        }catch (Exception $exception){
          DB::rollBack();
          echo  $exception->getMessage();
        }
    }
}
