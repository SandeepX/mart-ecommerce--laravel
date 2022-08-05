<?php


namespace App\Modules\InvestmentPlan\Repositories;


use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;

class InvestmentSubscriptionRepository
{

    public function getAllSubscribedIP()
    {
        return InvestmentPlanSubscription::paginate(20);
    }

    public function findActiveInvestmentPlanSubscription($ipsCode,$with= [])
    {
        return InvestmentPlanSubscription::with($with)->where('ip_subscription_code',$ipsCode)->first();
    }
    public function findOrFailInvestmentPlanSubscription($ipsCode,$with = []){
       $investmentPlanSubscription = $this->findActiveInvestmentPlanSubscription($ipsCode,$with);
       if($investmentPlanSubscription){
          return $investmentPlanSubscription;
       }
       throw new \Exception('Investment Plan Subscription Plan not found!');
    }

    public function getSubscribedInvestmentPlanByHolderIdAndType($validatedData)
    {

        return InvestmentPlanSubscription::where('investment_holder_type',$validatedData['investment_holder_type'])
            ->where('investment_holder_id',$validatedData['investment_holder_id'])
            ->where('has_paid',1)
            ->get();
    }

    public function getALlSubscribedInvestmentReferredByManager($referredCode)
    {
        return InvestmentPlanSubscription::where('referred_by',$referredCode)
            ->get();
    }

    public function store($validatedData)
    {
        return InvestmentPlanSubscription::create($validatedData)->fresh();
    }

    public function update($validatedData,$subscriptionData)
    {
        $subscriptionData->update($validatedData);
        return $subscriptionData->refresh();
    }

    public function changeInvestmentSubscriptionStatus($subscriptionData)
    {
        return $subscriptionData->update([
            'is_active' => !$subscriptionData['is_active']
        ]);
    }
}
