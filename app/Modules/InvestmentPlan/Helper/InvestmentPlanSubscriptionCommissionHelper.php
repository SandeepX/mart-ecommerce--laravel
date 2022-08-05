<?php


namespace App\Modules\InvestmentPlan\Helper;


use App\Modules\InvestmentPlan\Models\InvestmentPlanCommission;
use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use Exception;

class InvestmentPlanSubscriptionCommissionHelper
{

    public static function calculateInvestmentPlanSubscriptionCommission(
        InvestmentPlanSubscription $investmentPlanSubscription
    ){
        try{
            $investedAmount = $investmentPlanSubscription->invested_amount;


            $investmentCommission = $investmentPlanSubscription->investmentPlan->activeInstantInvestmentCommissionDetail();
            if($investmentCommission){
              $commissionValue =   roundPrice(self::commissionFromCommissionMountType(
                  $investmentCommission->commission_mount_type,
                  $investmentCommission->commission_amount_value,
                  $investedAmount
              ));
              return  $commissionValue;
            }
            return 0.0;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    private static function commissionFromCommissionMountType($commissionMountType,$commissionValue,$investmentAmount){


        $commissionValue = ($commissionMountType == 'p') ? (($commissionValue / 100) * $investmentAmount) : $commissionValue;

        return $commissionValue;
    }


}
