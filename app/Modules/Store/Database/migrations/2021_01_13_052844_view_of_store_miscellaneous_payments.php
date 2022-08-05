<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ViewOfStoreMiscellaneousPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE OR REPLACE VIEW store_miscellaneous_payments_view AS

        select Table1.store_code,Table1.payment_for,Table2.Pending,Table2.Verified,
        Table2.Rejected,Table1.verification_status as lastStatus,Table1.lastPaymentDate
            from
            (
                select DISTINCT(store_miscellaneous_payments.store_code),lastPaymentDate,store_miscellaneous_payments.verification_status ,
                store_miscellaneous_payments.payment_for from
            store_miscellaneous_payments
            inner join
            (
                select store_code,payment_for,sum(amount) as totalAmount,max(created_at) as lastPaymentDate
            from store_miscellaneous_payments group by store_code,payment_for) as t1
            on t1.store_code=store_miscellaneous_payments.store_code and store_miscellaneous_payments.created_at=t1.lastPaymentDate) as Table1
            INNER JOIN
            (
                select table1.store_code,table1.payment_for,
                ifnull(sum(pendingAmount),0) as Pending,
                ifnull(sum(verifiedAmount),0) as Verified,
                ifnull(sum(rejectedAmount),0) as Rejected from
                (
                    select store_code,payment_for,verification_status,
                        (case verification_status when "pending" then ifnull(sum(amount),0) end) as pendingAmount,
                        (case verification_status when "verified" then ifnull(sum(amount),0) end) as verifiedAmount,
                        (case verification_status when "rejected" then ifnull(sum(amount),0) end) as rejectedAmount
                        from store_miscellaneous_payments group by store_code,payment_for,verification_status
                )
                 as table1 GROUP by store_code,payment_for
            )
            as  Table2 on Table1.store_code=Table2.store_code and Table1.payment_for=Table2.payment_for
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_miscellaneous_payments_view');
    }
}
