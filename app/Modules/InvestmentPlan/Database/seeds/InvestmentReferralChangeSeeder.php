<?php
namespace App\Modules\InvestmentPlan\Database\seeds;

use App\Modules\SalesManager\Models\Manager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use Exception;

class InvestmentReferralChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{

            $investmentPlanSubscriptionReferrals = InvestmentPlanSubscription::select('investment_plan_subscriptions.*')->join('users','users.user_code','=','investment_plan_subscriptions.referred_by')
                                                                                ->join('user_types',function ($query){
                                                                                    $query->on('user_types.user_type_code','=','users.user_type_code')
                                                                                    ->where('user_types.slug','sales-manager');
                                                                                })
                                                                                ->whereNotNull('investment_plan_subscriptions.referred_by')
                                                                                ->get();

            DB::beginTransaction();
            foreach ($investmentPlanSubscriptionReferrals as $investmentPlanSubscriptionReferral){

                $manager = Manager::where('user_code',$investmentPlanSubscriptionReferral->referred_by)
                                    ->firstOrFail();
                $investmentPlanSubscriptionReferral->update(['referred_by'=>$manager->manager_code]);
            }

            DB::commit();
        }catch (Exception $exception){
           DB::rollBack();
           echo $exception->getMessage();
        }
    }
}
