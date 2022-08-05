<?php
namespace App\Modules\Store\Database\seeds;

use Illuminate\Database\Seeder;

class StoreBalanceWithdrawRequestViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement("
 CREATE OR REPLACE VIEW `store_balance_withdraw_request_lists_view` AS
SELECT
        `Table1`.`store_code` AS `store_code`,
        `Table2`.`pending` AS `pending`,
        `Table2`.`processing` AS `processing`,
        `Table2`.`rejected` AS `rejected`,
         `Table2`.`completed` AS `completed`,
        `Table1`.`verification_status` AS `last_verification_status`,
        `Table1`.`last_created_at` AS `last_created_at`
    FROM
        ((SELECT DISTINCT
            `store_balance_withdraw_request`.`store_code` AS `store_code`,
                `t1`.`last_created_at` AS `last_created_at`,
                `store_balance_withdraw_request`.`status` AS `verification_status`
        FROM
            (`store_balance_withdraw_request`
        JOIN (SELECT
            `store_balance_withdraw_request`.`store_code` AS `store_code`,
                SUM(`store_balance_withdraw_request`.`requested_amount`) AS `totalAmount`,
                MAX(`store_balance_withdraw_request`.`created_at`) AS `last_created_at`
        FROM
            `store_balance_withdraw_request`
        GROUP BY `store_balance_withdraw_request`.`store_code` ) `t1` ON (((`t1`.`store_code` = `store_balance_withdraw_request`.`store_code`)
            AND (`store_balance_withdraw_request`.`created_at` = `t1`.`last_created_at`))))) `Table1`
        JOIN (SELECT
            `table1`.`store_code` AS `store_code`,
                IFNULL(SUM(`table1`.`pendingAmount`), 0) AS `Pending`,
                IFNULL(SUM(`table1`.`processingAmount`), 0) AS `Processing`,
                IFNULL(SUM(`table1`.`rejectedAmount`), 0) AS `Rejected`,
                 IFNULL(SUM(`table1`.`completedAmount`), 0) AS `Completed`
        FROM
            (SELECT
            `store_balance_withdraw_request`.`store_code` AS `store_code`,
                `store_balance_withdraw_request`.`status` AS `verification_status`,
                (CASE `store_balance_withdraw_request`.`status`
                    WHEN 'pending' THEN IFNULL(SUM(`store_balance_withdraw_request`.`requested_amount`), 0)
                END) AS `pendingAmount`,
                (CASE `store_balance_withdraw_request`.`status`
                    WHEN 'processing' THEN IFNULL(SUM(`store_balance_withdraw_request`.`requested_amount`), 0)
                END) AS `processingAmount`,
                (CASE `store_balance_withdraw_request`.`status`
                    WHEN 'rejected' THEN IFNULL(SUM(`store_balance_withdraw_request`.`requested_amount`), 0)
                END) AS `rejectedAmount`,
                (CASE `store_balance_withdraw_request`.`status`
                    WHEN 'completed' THEN IFNULL(SUM(`store_balance_withdraw_request`.`requested_amount`), 0)
                END) AS `completedAmount`
        FROM
            `store_balance_withdraw_request`
        GROUP BY `store_balance_withdraw_request`.`store_code` , `store_balance_withdraw_request`.`status`) `table1`
        GROUP BY `table1`.`store_code` ) `Table2` ON (((`Table1`.`store_code` = `Table2`.`store_code`)
           )))");
    }
}
