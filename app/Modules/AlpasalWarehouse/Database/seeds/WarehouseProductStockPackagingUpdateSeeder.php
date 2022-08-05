<?php
namespace  App\Modules\AlpasalWarehouse\Database\seeds;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderStock;
use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseTransferStock;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStock;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseStock;
use App\Modules\AlpasalWarehouse\Models\WarehouseSalesReturnStock;
use App\Modules\AlpasalWarehouse\Models\WarehouseSalesStock;
use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use App\Modules\Store\Models\StoreOrderDetails;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseProductStockPackagingUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $warehouseProductStocks = WarehouseProductStock::where('action','!=','received-stock-transfer')
                                                            ->orderBy('created_at', 'ASC')
                                                            ->get();

           // dd($warehouseProductStocks);
            $time_start = microtime(true);
            DB::beginTransaction();
            foreach ($warehouseProductStocks->chunk(1000) as $warehouseProductStocks){
                foreach ($warehouseProductStocks as $warehouseProductStock) {

                    $warehouseProductMaster = WarehouseProductMaster::where('warehouse_product_master_code', $warehouseProductStock->warehouse_product_master_code)
                                                                     ->firstOrFail();

                    $latestProductPackagingDetails =  ProductPackagingHistory::where('product_code',$warehouseProductMaster->product_code)
                                                                                ->where('product_variant_code',$warehouseProductMaster->product_variant_code)
                                                                                ->orderBy('created_at','DESC')
                                                                                ->first();

                    if($warehouseProductStock->action == 'purchase'){
                        $warehousePurchaseStock = WarehousePurchaseStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                                                                ->firstOrFail();

                        $warehousePurchaseOrderDetails = PurchaseOrderDetail::where('warehouse_order_code',$warehousePurchaseStock->warehouse_order_code)
                                                                             ->where('product_code',$warehouseProductMaster->product_code)
                                                                             ->where('product_variant_code',$warehouseProductMaster->product_variant_code)
                                                                             ->first();

                        if($warehousePurchaseOrderDetails->product_packaging_history_code){
                             $latestProductPackagingDetails = ProductPackagingHistory::where('product_packaging_history_code',$warehousePurchaseOrderDetails->product_packaging_history_code)
                                                                                    ->first();
                        }
                    }elseif($warehouseProductStock->action == 'sales'){
                        $warehouseSalesStock = WarehouseSalesStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                                                            ->first();

                        if($warehouseSalesStock){
                            $warehouseSalesDetails =  StoreOrderDetails::where('store_order_code',$warehouseSalesStock->store_order_code)
                                ->where('product_code',$warehouseProductMaster->product_code)
                                ->where('product_variant_code',$warehouseProductMaster->product_variant_code)
                                ->first();

                            if($warehouseSalesDetails->product_packaging_history_code){
                                $latestProductPackagingDetails = ProductPackagingHistory::where('product_packaging_history_code',$warehouseSalesDetails->product_packaging_history_code)
                                    ->first();
                            }

                        }

                    }elseif($warehouseProductStock->action == 'sales-return'){
                           $warehouseSalesReturnStock = WarehouseSalesReturnStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                                                               ->firstOrFail();
                           $warehouseSalesReturnDetails = StoreOrderDetails::where('store_order_code',$warehouseSalesReturnStock->store_order_code)
                                                                           ->where('product_code',$warehouseProductMaster->product_code)
                                                                           ->where('product_variant_code',$warehouseProductMaster->product_variant_code)
                                                                           ->first();

                           if($warehouseSalesReturnDetails->product_packaging_history_code){
                               $latestProductPackagingDetails = ProductPackagingHistory::where('product_packaging_history_code',$warehouseSalesReturnDetails->product_packaging_history_code)
                                                                                   ->first();

                           }
                    }

                    if ($latestProductPackagingDetails){
                        $productPackagingFormatter = new ProductPackagingFormatter();
                        $arr =[];
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
                        $arr=array_reverse($arr,true);
                        $productPackagingDetails = $productPackagingFormatter->formatPackagingCombinationWithPackageCode(
                            $warehouseProductStock->quantity,
                            //  79,
                            $arr
                        );
                    }


                    if(!$latestProductPackagingDetails || $warehouseProductStock->created_at < '2021-04-02 20:09:59'){
                        switch ($warehouseProductStock->action){
                            case 'purchase':
                                $validatedPurchaseData = [];
                                $validatedPurchaseData['reference_code'] = $warehousePurchaseStock->warehouse_order_code;
                                $validatedPurchaseData['created_at'] = $warehouseProductStock->created_at;
                                $validatedPurchaseData['updated_at'] = $warehouseProductStock->updated_at;
                                $warehouseProductStock->update($validatedPurchaseData);
                                break;
                            case 'sales':

                                if($warehouseSalesStock){
                                    $validatedSalesData = [];
                                    $validatedSalesData['reference_code'] = $warehouseSalesStock->store_order_code;
                                    $validatedSalesData['created_at'] = $warehouseProductStock->created_at;
                                    $validatedSalesData['updated_at'] = $warehouseProductStock->updated_at;
                                    $warehouseProductStock->update($validatedSalesData);
                                }
                                break;
                            case 'stock-transfer':
                                $warehouseTransferStocks = WarehouseTransferStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                    ->first();
                                if ($warehouseTransferStocks) {
                                        $validatedStockTransferData = [];
                                        $validatedStockTransferData['reference_code'] = $warehouseTransferStocks->stock_transfer_master_code;
                                        $validatedStockTransferData['created_at'] = $warehouseProductStock->created_at;
                                        $validatedStockTransferData['updated_at'] = $warehouseProductStock->updated_at;
                                        $warehouseProductStock->update($validatedStockTransferData);
                                }
                                break;
                            case 'sales-return':

                                $validatedSalesReturnData = [];
                                $validatedSalesReturnData['reference_code'] = $warehouseSalesReturnStock->store_order_code;
                                $validatedSalesReturnData['created_at'] = $warehouseProductStock->created_at;
                                $validatedSalesReturnData['updated_at'] = $warehouseProductStock->updated_at;
                                $warehouseProductStock->update($validatedSalesReturnData);
                                break;
                            case 'preorder_sales':
                                $warehousePreOrderSalesStock = WarehousePreOrderStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                    ->firstOrFail();
                                $storePreOrderDetails = StorePreOrderDetail::where('store_preorder_detail_code', $warehousePreOrderSalesStock->store_preorder_detail_code)
                                    ->firstOrFail();
                                $validatedPreOrderData = [];
                                $validatedPreOrderData['reference_code'] = $storePreOrderDetails->store_preorder_code;
                                $validatedPreOrderData['created_at'] = $warehouseProductStock->created_at;
                                $validatedPreOrderData['updated_at'] = $warehouseProductStock->updated_at;
                                if ($storePreOrderDetails->package_code) {
//                                    $productPackagingPreOrderDetails = ProductPackagingHistory::where('product_packaging_history_code',$storePreOrderDetails->product_packaging_history_code)
//                                        ->firstOrFail();
//                                    $validatedPreOrderData['quantity'] = ProductUnitPackagingHelper::convertToMicroUnitQuantity(
//                                        $storePreOrderDetails->package_code,
//                                        $productPackagingPreOrderDetails,
//                                        $storePreOrderDetails->quantity
//                                    );
                                    $validatedPreOrderData['package_qty'] = $storePreOrderDetails->quantity;
                                    $validatedPreOrderData['package_code'] = $storePreOrderDetails->package_code;
                                    $validatedPreOrderData['product_packaging_history_code'] = $storePreOrderDetails->product_packaging_history_code;
                                }
                                $warehouseProductStock->update($validatedPreOrderData);

                                break;
                            case 'received-stock-transfer':
                                $warehouseTransferStocks = WarehouseTransferStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                    ->first();
                                if ($warehouseTransferStocks) {
                                    $validatedStockReceiveData = [];
                                    $validatedStockReceiveData['reference_code'] = $warehouseTransferStocks->stock_transfer_master_code;
                                    $validatedStockReceiveData['created_at'] = $warehouseProductStock->created_at;
                                    $validatedStockReceiveData['updated_at'] = $warehouseProductStock->updated_at;
                                    $warehouseProductStock->update($validatedStockReceiveData);
                                }
                                break;
                        }
                        echo "\033[31m" . ' Warehouse Product Master Code: ' . $warehouseProductMaster->warehouse_product_master_code .
                            "\033[32m" . ' due to Warehouse Product Stock  Code: ' . $warehouseProductStock->warehouse_product_stock_code . "\n";
                        continue;
                    }



                     switch ($warehouseProductStock->action) {
                        case 'purchase':
                            $warehousePurchaseStock = WarehousePurchaseStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                                                              ->firstOrFail();
                            foreach($productPackagingDetails as $key => $productPackagingDetail){
                                $validatedPurchaseData = [];
                                $validatedPurchaseData['reference_code'] = $warehousePurchaseStock->warehouse_order_code;
                                $validatedPurchaseData['quantity'] = $productPackagingDetail['micro_quantity'];
                                $validatedPurchaseData['package_qty'] = $productPackagingDetail['package_quantity'];
                                $validatedPurchaseData['package_code'] = $key;
                                $validatedPurchaseData['product_packaging_history_code'] = $latestProductPackagingDetails->product_packaging_history_code;
                                $validatedPurchaseData['created_at'] = $warehouseProductStock->created_at;
                                $validatedPurchaseData['updated_at'] = $warehouseProductStock->updated_at;

                                if($key === array_key_first($productPackagingDetails)){
                                     $warehouseProductStock->update($validatedPurchaseData);
                                }else{
                                    $validatedPurchaseData['warehouse_product_master_code'] = $warehouseProductStock->warehouse_product_master_code;
                                    $validatedPurchaseData['action'] = $warehouseProductStock->action;
                                    WarehouseProductStock::create($validatedPurchaseData);
                                }
                            }

                            break;
                        case 'sales':

                            $warehouseSalesStock = WarehouseSalesStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                ->first();

                            foreach($productPackagingDetails as $key => $productPackagingDetail){
                                $validatedSalesData = [];
                                $validatedSalesData['reference_code'] = $warehouseSalesStock->store_order_code;
                                $validatedSalesData['quantity'] = $productPackagingDetail['micro_quantity'];
                                $validatedSalesData['package_qty'] = $productPackagingDetail['package_quantity'];
                                $validatedSalesData['package_code'] = $key;
                                $validatedSalesData['product_packaging_history_code'] =  $latestProductPackagingDetails->product_packaging_history_code;
                                $validatedSalesData['created_at'] =  $warehouseProductStock->created_at;
                                $validatedSalesData['updated_at'] =  $warehouseProductStock->updated_at;

                                if($key === array_key_first($productPackagingDetails)) {
                                    $warehouseProductStock->update($validatedSalesData);
                                }else{
                                    $validatedSalesData['warehouse_product_master_code'] = $warehouseProductStock->warehouse_product_master_code;
                                    $validatedSalesData['action'] = $warehouseProductStock->action;
                                    WarehouseProductStock::create($validatedSalesData);

                                }
                            }
                            break;
                        case 'stock-transfer':

                            $warehouseTransferStocks = WarehouseTransferStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                ->first();
                            if ($warehouseTransferStocks) {

                                foreach($productPackagingDetails as $key => $productPackagingDetail) {
                                    $validatedStockTransferData = [];
                                    $validatedStockTransferData['reference_code'] = $warehouseTransferStocks->stock_transfer_master_code;
                                    $validatedStockTransferData['quantity'] = $productPackagingDetail['micro_quantity'];
                                    $validatedStockTransferData['package_qty'] = $productPackagingDetail['package_quantity'];
                                    $validatedStockTransferData['package_code'] = $key;
                                    $validatedStockTransferData['product_packaging_history_code'] =  $latestProductPackagingDetails->product_packaging_history_code;
                                    $validatedStockTransferData['created_at'] =  $warehouseProductStock->created_at;
                                    $validatedStockTransferData['updated_at'] =  $warehouseProductStock->updated_at;
                                    if($key === array_key_first($productPackagingDetails)) {
                                        $warehouseProductStock->update($validatedStockTransferData);
                                    }else{
                                        $validatedStockTransferData['warehouse_product_master_code'] = $warehouseProductStock->warehouse_product_master_code;
                                        $validatedStockTransferData['action'] = $warehouseProductStock->action;
                                        WarehouseProductStock::create($validatedStockTransferData);

                                    }
                                }
                            }

                            break;
                        case 'sales-return':

                            $warehouseSalesReturnStock = WarehouseSalesReturnStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                ->firstOrFail();
                            foreach($productPackagingDetails as $key => $productPackagingDetail) {
                                $validatedSalesReturnData = [];
                                $validatedSalesReturnData['reference_code'] = $warehouseSalesReturnStock->store_order_code;
                                $validatedSalesReturnData['quantity'] = $productPackagingDetail['micro_quantity'];
                                $validatedSalesReturnData['package_qty'] = $productPackagingDetail['package_quantity'];
                                $validatedSalesReturnData['package_code'] = $key;
                                $validatedSalesReturnData['product_packaging_history_code'] =  $latestProductPackagingDetails->product_packaging_history_code;
                                $validatedSalesReturnData['created_at'] =  $warehouseProductStock->created_at;
                                $validatedSalesReturnData['updated_at'] =  $warehouseProductStock->updated_at;
                                if($key === array_key_first($productPackagingDetails)) {
                                    $warehouseProductStock->update($validatedSalesReturnData);
                                }else{
                                    $validatedSalesReturnData['warehouse_product_master_code'] = $warehouseProductStock->warehouse_product_master_code;
                                    $validatedSalesReturnData['action'] = $warehouseProductStock->action;
                                    WarehouseProductStock::create($validatedSalesReturnData);
                                }
                            }

                            break;
                        case 'preorder_sales':

                            $warehousePreOrderSalesStock = WarehousePreOrderStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                ->firstOrFail();

                            $storePreOrderDetails = StorePreOrderDetail::where('store_preorder_detail_code', $warehousePreOrderSalesStock->store_preorder_detail_code)
                                ->firstOrFail();

                            $validatedPreOrderData = [];
                            $validatedPreOrderData['reference_code'] = $storePreOrderDetails->store_preorder_code;
                            $validatedPreOrderData['created_at'] = $storePreOrderDetails->created_at;
                            $validatedPreOrderData['updated_at'] = $storePreOrderDetails->updated_at;
                            if ($storePreOrderDetails->package_code) {
//                                $productPackagingPreOrderDetails = ProductPackagingHistory::where('product_packaging_history_code',$storePreOrderDetails->product_packaging_history_code)
//                                                                                            ->firstOrFail();
//                                $validatedPreOrderData['quantity'] = ProductUnitPackagingHelper::convertToMicroUnitQuantity(
//                                    $storePreOrderDetails->package_code,
//                                    $productPackagingPreOrderDetails,
//                                    $storePreOrderDetails->quantity
//                                );
                                $validatedPreOrderData['package_qty'] = $storePreOrderDetails->quantity;
                                $validatedPreOrderData['package_code'] = $storePreOrderDetails->package_code;
                                $validatedPreOrderData['product_packaging_history_code'] = $storePreOrderDetails->product_packaging_history_code;
                            }
                            $warehouseProductStock->update($validatedPreOrderData);

                            break;
                        case 'received-stock-transfer':

                            $warehouseTransferStocks = WarehouseTransferStock::where('warehouse_product_stock_code', $warehouseProductStock->warehouse_product_stock_code)
                                ->first();


                            if ($warehouseTransferStocks) {
                                foreach($productPackagingDetails as $key => $productPackagingDetail) {
                                    //   dd($warehouseTransferStocks);
                                    $validatedStockReceiveData = [];
                                    $validatedStockReceiveData['reference_code'] = $warehouseTransferStocks->stock_transfer_master_code;
                                    $validatedStockReceiveData['quantity'] = $productPackagingDetail['micro_quantity'];
                                    $validatedStockReceiveData['package_qty'] = $productPackagingDetail['package_quantity'];
                                    $validatedStockReceiveData['package_code'] = $key;
                                    $validatedStockReceiveData['product_packaging_history_code'] = $latestProductPackagingDetails->product_packaging_history_code;
                                    $validatedStockReceiveData['created_at'] = $warehouseProductStock->created_at;
                                    $validatedStockReceiveData['updated_at'] = $warehouseProductStock->updated_at;

                                    if ($key === array_key_first($productPackagingDetails)) {
                                        $warehouseProductStock->update($validatedStockReceiveData);
                                    } else {
                                        $validatedStockReceiveData['warehouse_product_master_code'] = $warehouseProductStock->warehouse_product_master_code;
                                        $validatedStockReceiveData['action'] = $warehouseProductStock->action;
                                        WarehouseProductStock::create($validatedStockReceiveData);
                                    }
                                }
                            }
                            break;
                    }

                    echo "\033[31m" . 'Warehouse Product Master Code: ' . $warehouseProductMaster->warehouse_product_master_code .
                        "\033[32m" . ' due to Warehouse Product Stock  Code: ' . $warehouseProductStock->warehouse_product_stock_code . "\n";
                }
        }

            echo " Successfully Completed "."\n";
            DB::commit();
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo '<b>Total Execution Time:</b> '.($execution_time)/60 .' Mins'.'\n';
        }catch (Exception $exception){
            DB::rollback();
            echo $exception->getMessage();
        }
    }
}
