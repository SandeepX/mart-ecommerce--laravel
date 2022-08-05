<?php
namespace App\Modules\InvestmentPlan\Database\seeds;

use App\Modules\PaymentGateway\Models\OnlinePaymentMaster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use Exception;

class InvestmentOnlinePaymentCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $investmentPlanSubscriptions = InvestmentPlanSubscription::orderBy('ip_subscription_code','ASC')
                                                                      ->get();
            DB::beginTransaction();
            foreach ($investmentPlanSubscriptions as $investmentPlanSubscription){
                $onlinePayment = OnlinePaymentMaster::where('reference_code',$investmentPlanSubscription->ip_subscription_code)
                                                      ->first();
                if($onlinePayment){
                    $investmentPlanSubscription->update(['payment_code'=>$onlinePayment->online_payment_master_code]);
                }
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
