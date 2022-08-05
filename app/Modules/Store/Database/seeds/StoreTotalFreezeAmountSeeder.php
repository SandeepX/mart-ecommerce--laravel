<?php


namespace App\Modules\Store\Database\seeds;


use Illuminate\Database\Seeder;

class StoreTotalFreezeAmountSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement("
          CREATE OR REPLACE VIEW store_frozen_balance_view AS
           WITH storeDetail AS (
                       SELECT store_code
                        FROM
                        stores_detail
                       GROUP BY store_code
                     ),
                   withdrawFreeze AS (
                        SELECT
                            store_code,
                           ROUND( SUM(requested_amount),2) as total_withdraw_freeze
                        FROM
                            store_balance_withdraw_request
                        WHERE status IN  ('pending','processing')
                            GROUP by store_code
                    ),
                     preOrderFreeze AS (
                        SELECT
                            store_code,
                            ROUND(SUM(total_price),2) as total_preorder_freeze
                        FROM
                            store_pre_orders_view
                        WHERE
                            status = 'pending'
                            GROUP by store_code
                    ),
                    resultFreezeAmount AS (
                             select
                              storeDetail.store_code,
                              withdrawFreeze.total_withdraw_freeze,
                              preOrderFreeze.total_preorder_freeze
                              from storeDetail
                             left join withdrawFreeze
                              USING (store_code)
                              left join preOrderFreeze
                              USING (store_code)
                          )
                    SELECT
                        store_code,
                        total_withdraw_freeze,
                        total_preorder_freeze,
                          (COALESCE(total_withdraw_freeze,0)
                            + COALESCE(total_preorder_freeze,0)
                          )
                        as total_freeze_amount
                    FROM
                        resultFreezeAmount
        ");
    }

}
