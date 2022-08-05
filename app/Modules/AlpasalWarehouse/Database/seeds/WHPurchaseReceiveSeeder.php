<?php
namespace  App\Modules\AlpasalWarehouse\Database\seeds;

use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrderReceivedDetail;
use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;

class WHPurchaseReceiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{

            $warehousePurchaseReceivedDetails = DB::table('warehouse_purchase_order_received_details_old')->select(
                                                                               'warehouse_purchase_order_received_details_old.warehouse_purchase_order_received_detail_code',
                                                                               'warehouse_purchase_order_received_details_old.warehouse_order_detail_code',
                                                                               'warehouse_purchase_order_received_details_old.received_quantity',
                                                                               'warehouse_order_details.warehouse_order_code',
                                                                               'warehouse_order_details.product_code',
                                                                               'warehouse_order_details.product_variant_code',
                                                                                DB::raw('SUM(warehouse_purchase_order_received_details_old.received_quantity) as received_micro_quantity'),
                                                                               'warehouse_order_details.product_packaging_history_code',
                                                                               'warehouse_purchase_order_received_details_old.has_received',
                                                                               'warehouse_purchase_order_received_details_old.manufactured_date',
                                                                               'warehouse_purchase_order_received_details_old.expiry_date',
                                                                               'warehouse_purchase_order_received_details_old.created_at',
                                                                               'warehouse_purchase_order_received_details_old.updated_at'
                                                                              )
                                                                             ->join('warehouse_order_details',
                                                                                 'warehouse_order_details.warehouse_order_detail_code',
                                                                                 '=',
                                                                                 'warehouse_purchase_order_received_details_old.warehouse_order_detail_code')
                                                                             ->groupBy(
                                                                                 'warehouse_order_details.warehouse_order_code',
                                                                                 'warehouse_order_details.product_code',
                                                                                 'warehouse_order_details.product_variant_code'
                                                                             )
                                                                             ->orderBy('warehouse_purchase_order_received_details_old.created_at','ASC')
                                                                             ->get();

            $time_start = microtime(true);
            DB::beginTransaction();
            foreach($warehousePurchaseReceivedDetails->chunk(1000) as $warehousePurchaseReceivedDetails){
                foreach ($warehousePurchaseReceivedDetails as $warehousePurchaseReceivedDetail){

                    $latestProductPackagingHistory = ProductPackagingHistory::where('product_code',$warehousePurchaseReceivedDetail->product_code)
                                                                             ->where('product_variant_code',$warehousePurchaseReceivedDetail->product_variant_code)
                                                                             ->first();

                    $warehousePurchaseOrderDetails = PurchaseOrderDetail::where('warehouse_order_code',$warehousePurchaseReceivedDetail->warehouse_order_code)
                                                                         ->where('product_code',$warehousePurchaseReceivedDetail->product_code)
                                                                         ->where('product_variant_code',$warehousePurchaseReceivedDetail->product_variant_code)
                                                                         ->first();

                    if($warehousePurchaseOrderDetails->product_packaging_history_code){
                        $latestProductPackagingHistory = ProductPackagingHistory::where('product_packaging_history_code',$warehousePurchaseOrderDetails->product_packaging_history_code)
                                                                                ->first();
                    }

                    if($warehousePurchaseReceivedDetail->created_at >= '2021-04-02 20:09:59'){

                        $productPackagingFormatter = new ProductPackagingFormatter();
                        $arr = [];
                        if ($latestProductPackagingHistory->micro_unit_code){
                            $arr[1] =$latestProductPackagingHistory->micro_unit_code;
                        }
                        if ($latestProductPackagingHistory->unit_code){
                            $arrKey = intval($latestProductPackagingHistory->micro_to_unit_value);
                            $arr[$arrKey] =$latestProductPackagingHistory->unit_code;
                        }
                        if ($latestProductPackagingHistory->macro_unit_code){
                            $arrKey = intval($latestProductPackagingHistory->micro_to_unit_value *
                                $latestProductPackagingHistory->unit_to_macro_value);
                            $arr[$arrKey] =$latestProductPackagingHistory->macro_unit_code;
                        }
                        if ($latestProductPackagingHistory->super_unit_code){
                            $arrKey = intval($latestProductPackagingHistory->micro_to_unit_value *
                                $latestProductPackagingHistory->unit_to_macro_value *
                                $latestProductPackagingHistory->macro_to_super_value);
                            $arr[$arrKey] =$latestProductPackagingHistory->super_unit_code;
                        }

                        $arr=array_reverse($arr,true);
                        $productPackagingDetails = $productPackagingFormatter->formatPackagingCombinationWithPackageCode(
                            $warehousePurchaseReceivedDetail->received_quantity,
                            $arr
                        );
                        foreach ($productPackagingDetails as $key => $productPackagingDetail){
                            $warehousePurchaseReceivedData = [];
                            $warehousePurchaseReceivedData['warehouse_order_code'] = $warehousePurchaseReceivedDetail->warehouse_order_code;
                            $warehousePurchaseReceivedData['product_code'] = $warehousePurchaseReceivedDetail->product_code;
                            $warehousePurchaseReceivedData['product_variant_code'] = $warehousePurchaseReceivedDetail->product_variant_code;
                            $warehousePurchaseReceivedData['has_received'] = $warehousePurchaseReceivedDetail->has_received;
                            $warehousePurchaseReceivedData['manufactured_date'] = $warehousePurchaseReceivedDetail->manufactured_date;
                            $warehousePurchaseReceivedData['expiry_date'] = $warehousePurchaseReceivedDetail->expiry_date;
                            $warehousePurchaseReceivedData['created_at'] = $warehousePurchaseReceivedDetail->created_at;
                            $warehousePurchaseReceivedData['updated_at'] = $warehousePurchaseReceivedDetail->updated_at;
                            $warehousePurchaseReceivedData['received_quantity'] = $productPackagingDetail['micro_quantity'];
                            $warehousePurchaseReceivedData['package_quantity'] = $productPackagingDetail['package_quantity'];
                            $warehousePurchaseReceivedData['package_code'] = $key;
                            $warehousePurchaseReceivedData['product_packaging_history_code'] = $latestProductPackagingHistory->product_packaging_history_code;
                            WarehousePurchaseOrderReceivedDetail::create($warehousePurchaseReceivedData);
                        }
                    }else{
                        $warehousePurchaseReceivedData = [];
                        $warehousePurchaseReceivedData['warehouse_order_code'] = $warehousePurchaseReceivedDetail->warehouse_order_code;
                        $warehousePurchaseReceivedData['product_code'] = $warehousePurchaseReceivedDetail->product_code;
                        $warehousePurchaseReceivedData['product_variant_code'] = $warehousePurchaseReceivedDetail->product_variant_code;
                        $warehousePurchaseReceivedData['has_received'] = $warehousePurchaseReceivedDetail->has_received;
                        $warehousePurchaseReceivedData['manufactured_date'] = $warehousePurchaseReceivedDetail->manufactured_date;
                        $warehousePurchaseReceivedData['expiry_date'] = $warehousePurchaseReceivedDetail->expiry_date;
                        $warehousePurchaseReceivedData['created_at'] = $warehousePurchaseReceivedDetail->created_at;
                        $warehousePurchaseReceivedData['updated_at'] = $warehousePurchaseReceivedDetail->updated_at;
                        $warehousePurchaseReceivedData['received_quantity'] = $warehousePurchaseReceivedDetail->received_quantity;
                        WarehousePurchaseOrderReceivedDetail::create($warehousePurchaseReceivedData);
                    }

                    echo "\033[31m" . ' Warehouse Purchase Receive Code: ' . $warehousePurchaseReceivedDetail->warehouse_purchase_order_received_detail_code .
                        "\033[32m" . ' Completed '."\n";

                }
            }

            //dd(1);

            echo " Successfully Seeder Completed "."\n";
            DB::commit();
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo "Total Execution Time: ".($execution_time)/60 ." Mins"."\n";
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
