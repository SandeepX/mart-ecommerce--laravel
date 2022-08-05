<?php
namespace App\Modules\InvestmentPlan\Database\seeds;


use App\Modules\SalesManager\Models\Manager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use Exception;

class ManagerTypeInInvestmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            DB::beginTransaction();

            $investmentPlanSubscriptions = InvestmentPlanSubscription::where('investment_holder_type','User')
                                                                       ->join('users','users.user_code','=','investment_plan_subscriptions.investment_holder_id')
                                                                       ->join('user_types',function($query) {
                                                                         $query->on('user_types.user_type_code','=','users.user_type_code')
                                                                                ->where('user_types.slug','sales-manager');
                                                                       })
                                                                       ->get();

               foreach($investmentPlanSubscriptions as $investmentPlanSubscription){

                   //dd($investmentPlanSubscription);
                   $manager = Manager::where('user_code',$investmentPlanSubscription->investment_holder_id)
                                            ->first();
                   $investmentPlanSubscriptionsData = [];
                   $investmentPlanSubscriptionsData['investment_plan_holder'] = get_class($manager);
                   $investmentPlanSubscriptionsData['investment_holder_type'] = 'manager';
                   $investmentPlanSubscriptionsData['investment_holder_id'] = $manager->manager_code;

                   $investmentPlanSubscription->update($investmentPlanSubscriptionsData);

               }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
           echo $exception->getMessage().$exception->getLine();
        }
    }
}
