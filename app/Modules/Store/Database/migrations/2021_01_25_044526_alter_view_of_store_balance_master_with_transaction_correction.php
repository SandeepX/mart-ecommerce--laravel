<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewOfStoreBalanceMasterWithTransactionCorrection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \DB::statement('CREATE OR REPLACE VIEW store_current_balance AS
           select DISTINCT mainBalance.store_code,loadedBalance,rewardBalance,interestBalance,salesReconciliationIncrementBalance,refundReleaseBalance,transactionCorrectionIncrementBalance,withdrawnBalance,salesBalance, refundableCharges,royaltyCharges,
           initialRegistrationsBalance,salesReconciliationDeductionBalance,transactionCorrectionDeductionBalance,annualCharges,
           (IFNULL(loadedBalance,0)+IFNULL(rewardBalance,0)+ IFNULL(interestBalance,0)+IFNULL(salesReconciliationIncrementBalance,0)+IFNULL(refundReleaseBalance,0)+IFNULL(transactionCorrectionIncrementBalance,0)-
           IFNULL(withdrawnBalance,0)-IFNULL(salesBalance,0)-IFNULL(royaltyCharges,0)-IFNULL(refundableCharges,0)-IFNULL(initialRegistrationsBalance,0)-IFNULL(salesReconciliationDeductionBalance,0)-IFNULL(transactionCorrectionDeductionBalance,0)-IFNULL(annualCharges,0)) as balance from store_balance_master as mainBalance
    left join
      (select store_code,sum(transaction_amount) as loadedBalance from store_balance_master where transaction_type="load_balance" GROUP by store_code) as loadBalance on loadBalance.store_code= mainBalance.store_code
     left join
      (select store_code,sum(transaction_amount)  as withdrawnBalance from store_balance_master where transaction_type="withdraw" GROUP by store_code) as withDraw on withDraw.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as salesBalance from store_balance_master where transaction_type="sales" GROUP by store_code) as sales on sales.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as refundableCharges from store_balance_master where transaction_type="refundable" GROUP by store_code) as refundable on refundable.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as royaltyCharges from store_balance_master where transaction_type="royalty" GROUP by store_code) as royalty on royalty.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as interestBalance from store_balance_master where transaction_type="interest" GROUP by store_code) as interest on interest.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as annualCharges from store_balance_master where transaction_type="annual_charge" GROUP by store_code) as annual_charge on annual_charge.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as rewardBalance from store_balance_master where transaction_type="rewards" GROUP by store_code) as rewards on rewards.store_code=mainBalance.store_code
    left join
       (select store_code,sum(transaction_amount)  as salesReconciliationIncrementBalance from store_balance_master where transaction_type="sales_reconciliation_increment" GROUP by store_code) as salesReconciliationIncrement on salesReconciliationIncrement.store_code=mainBalance.store_code
    left join
       (select store_code,sum(transaction_amount)  as salesReconciliationDeductionBalance from store_balance_master where transaction_type="sales_reconciliation_deduction" GROUP by store_code) as salesReconciliationDeduction on salesReconciliationDeduction.store_code=mainBalance.store_code
    left join
    (select store_code,sum(transaction_amount)  as initialRegistrationsBalance from store_balance_master where transaction_type="initial_registrations" GROUP by store_code) as initialRegistrations on initialRegistrations.store_code=mainBalance.store_code
    left join
     (select store_code,sum(transaction_amount)  as refundReleaseBalance from store_balance_master where transaction_type="refund_release" GROUP by store_code) as refundRelease on refundRelease.store_code=mainBalance.store_code
     left join
      (select store_code,sum(transaction_amount)  as transactionCorrectionIncrementBalance from store_balance_master where transaction_type="transaction_correction_increment" GROUP by store_code) as transactionCorrectionIncrement on transactionCorrectionIncrement.store_code=mainBalance.store_code
     left join
     (select store_code,sum(transaction_amount)  as transactionCorrectionDeductionBalance from store_balance_master where transaction_type="transaction_correction_deduction" GROUP by store_code) as transactionCorrectionDeduction on transactionCorrectionDeduction.store_code=mainBalance.store_code
       ');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('CREATE OR REPLACE VIEW store_current_balance AS
           select DISTINCT mainBalance.store_code,loadedBalance,rewardBalance,interestBalance,salesReconciliationIncrementBalance,withdrawnBalance,salesBalance, refundableCharges,royaltyCharges,
           initialRegistrationsBalance,salesReconciliationDeductionBalance,annualCharges,
           (IFNULL(loadedBalance,0)+IFNULL(rewardBalance,0)+ IFNULL(interestBalance,0)+IFNULL(salesReconciliationIncrementBalance,0)-
           IFNULL(withdrawnBalance,0)-IFNULL(salesBalance,0)-IFNULL(royaltyCharges,0)-IFNULL(refundableCharges,0)-IFNULL(initialRegistrationsBalance,0)-IFNULL(salesReconciliationDeductionBalance,0)-IFNULL(annualCharges,0)) as balance from store_balance_master as mainBalance
    left join
      (select store_code,sum(transaction_amount) as loadedBalance from store_balance_master where transaction_type="load_balance" GROUP by store_code) as loadBalance on loadBalance.store_code= mainBalance.store_code
     left join
      (select store_code,sum(transaction_amount)  as withdrawnBalance from store_balance_master where transaction_type="withdraw" GROUP by store_code) as withDraw on withDraw.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as salesBalance from store_balance_master where transaction_type="sales" GROUP by store_code) as sales on sales.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as refundableCharges from store_balance_master where transaction_type="refundable" GROUP by store_code) as refundable on refundable.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as royaltyCharges from store_balance_master where transaction_type="royalty" GROUP by store_code) as royalty on royalty.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as interestBalance from store_balance_master where transaction_type="interest" GROUP by store_code) as interest on interest.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as annualCharges from store_balance_master where transaction_type="annual_charge" GROUP by store_code) as annual_charge on annual_charge.store_code=mainBalance.store_code
    left join
      (select store_code,sum(transaction_amount)  as rewardBalance from store_balance_master where transaction_type="rewards" GROUP by store_code) as rewards on rewards.store_code=mainBalance.store_code
    left join
       (select store_code,sum(transaction_amount)  as salesReconciliationIncrementBalance from store_balance_master where transaction_type="sales_reconciliation_increment" GROUP by store_code) as salesReconciliationIncrement on salesReconciliationIncrement.store_code=mainBalance.store_code
    left join
       (select store_code,sum(transaction_amount)  as salesReconciliationDeductionBalance from store_balance_master where transaction_type="sales_reconciliation_deduction" GROUP by store_code) as salesReconciliationDeduction on salesReconciliationDeduction.store_code=mainBalance.store_code
    left join
    (select store_code,sum(transaction_amount)  as initialRegistrationsBalance from store_balance_master where transaction_type="initial_registrations" GROUP by store_code) as initialRegistrations on initialRegistrations.store_code=mainBalance.store_code
       ');

    }
}
