<?php

namespace  App\Modules\AlpasalWarehouse\Database\seeds;

use App\Modules\AlpasalWarehouse\Models\AlpasalWarehouseType;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlpasalWarehouseStockTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement("
            CREATE OR REPLACE VIEW warehouse_product_stock_view AS
             WITH warehouseProductMaster As (
                SELECT
                    warehouse_product_master_code
                FROM
                    warehouse_product_stock
                GROUP by warehouse_product_master_code
            ),
            stockPurchase AS (
                SELECT
                    warehouse_product_master_code,
                    SUM(quantity) as purchase
                FROM
                    warehouse_product_stock
                WHERE
                    action = 'purchase'
                    GROUP by warehouse_product_master_code
            ),
            stockSales AS (
                SELECT
                    warehouse_product_master_code,
                    SUM(quantity) as sales
                FROM
                    warehouse_product_stock
                WHERE
                    action = 'sales'
                    GROUP by warehouse_product_master_code
            ),
            stockPreOrderSales AS (
                SELECT
                    warehouse_product_master_code,
                    SUM(quantity) as preorder_sales
                FROM
                    warehouse_product_stock
                WHERE
                    action = 'preorder_sales'
                    GROUP by warehouse_product_master_code
            ),
            stockTransferred AS (
                SELECT
                    warehouse_product_master_code,
                    SUM(quantity) as stock_transfer
                FROM
                    warehouse_product_stock
                WHERE
                    action = 'stock-transfer'
                    GROUP by warehouse_product_master_code
            ),
            stockTransferReceived AS (
                SELECT
                    warehouse_product_master_code,
                    SUM(quantity) as stock_transfer_received
                FROM
                    warehouse_product_stock
                WHERE
                    action = 'received-stock-transfer'
                    GROUP by warehouse_product_master_code
            ),
             stockSalesReturn AS (
                SELECT
                    warehouse_product_master_code,
                    SUM(quantity) as stock_sales_return
                FROM
                    warehouse_product_stock
                WHERE
                    action = 'sales-return'
                    GROUP by warehouse_product_master_code
            ),
            resultStock AS (
                     select
                      warehouseProductMaster.warehouse_product_master_code,
                      stockPurchase.purchase,
                      stockSales.sales,
                      stockPreOrderSales.preorder_sales,
                      stockTransferred.stock_transfer,
                      stockTransferReceived.stock_transfer_received,
                      stockSalesReturn.stock_sales_return
                      from warehouseProductMaster
                      left join stockPurchase
                      USING (warehouse_product_master_code)
                      left join stockSales
                      USING (warehouse_product_master_code)
                      left join stockPreOrderSales
                      USING (warehouse_product_master_code)
                      left join stockTransferred
                      USING (warehouse_product_master_code)
                       left join stockTransferReceived
                      USING (warehouse_product_master_code)
                        left join stockSalesReturn
                      USING (warehouse_product_master_code)

                  )
            SELECT
                warehouse_product_master_code as code,
                purchase,
                sales,
                preorder_sales,
                stock_transfer,
                 stock_transfer_received,
                 stock_sales_return,
                (
                  (COALESCE(purchase,0)
                    + COALESCE(stock_transfer_received,0)
                    + COALESCE(stock_sales_return,0)
                  ) -
                    (
                      COALESCE(sales,0)
                       + COALESCE(preorder_sales,0)
                       +  COALESCE(stock_transfer,0)
                    )
                ) as current_stock
            FROM
                resultStock;
        ");
    }
}
