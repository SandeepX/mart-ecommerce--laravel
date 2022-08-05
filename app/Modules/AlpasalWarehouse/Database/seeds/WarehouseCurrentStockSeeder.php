<?php
namespace  App\Modules\AlpasalWarehouse\Database\seeds;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductStock;
use Illuminate\Database\Seeder;
use Exception;
use Illuminate\Support\Facades\DB;

class WarehouseCurrentStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $warehouseProductStocks = WarehouseProductStock::orderBy('created_at', 'ASC')
                ->get();
            $time_start = microtime(true);
            DB::beginTransaction();
            foreach ($warehouseProductStocks->chunk(1000) as $warehouseProductStocks){
                foreach ($warehouseProductStocks as $warehouseProductStock) {

                    $warehouseProductMaster = WarehouseProductMaster::where('warehouse_product_master_code', $warehouseProductStock->warehouse_product_master_code)
                        ->firstOrFail();

                    switch ($warehouseProductStock->action) {
                        case 'received-stock-transfer':
                        case 'sales-return':
                        case 'purchase':
                            $last_current_stock = $warehouseProductMaster->current_stock;
                            $current_stock = $last_current_stock + $warehouseProductStock->quantity;
                            $warehouseProductMaster->update(['current_stock' => $current_stock]);
                            break;
                        case 'preorder_sales':
                        case 'stock-transfer':
                        case 'purchase-return':
                        case 'sales':
                            $last_current_stock = $warehouseProductMaster->current_stock;
                            $current_stock = $last_current_stock - $warehouseProductStock->quantity;
                            $warehouseProductMaster->update(['current_stock' => $current_stock]);
                            break;
                    }

                    echo "\033[34m" . ' Current Stock: ' . $current_stock .
                        "\033[31m" . ' of Warehouse Product Master Code: ' . $warehouseProductMaster->warehouse_product_master_code .
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
