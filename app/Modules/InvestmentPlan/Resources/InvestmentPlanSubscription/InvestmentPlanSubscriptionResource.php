<?php


namespace App\Modules\InvestmentPlan\Resources\InvestmentPlanSubscription;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentPlanSubscriptionResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'ip_subscription_code' => $this->ip_subscription_code,
            'investment_plan_code' => $this->investment_plan_code,
            'investment_plan_name' => ucfirst($this->investment_plan_name),
            'investment_type' => $this->investmentPlan->investmentType->name,
            'maturity_period' => $this->maturity_period,
            'ipir_option_code' => $this->ipir_option_code,
            'interest_release_time' => ucfirst($this->investmentPlanInterestRelease->interest_release_time),
            'interest_rate' => $this->interest_rate,
            'invested_amount' => $this->invested_amount,
            'price_start_range' => $this->price_start_range,
            'price_end_range' => $this->price_end_range,
            'is_mature' => $this->is_mature,
            'maturity_date' => $this->maturity_date,
            'invested_date' => date_format($this->created_at, "d M Y"),
            'is_active' => $this->is_active,
            'has_paid' => $this->has_paid,
            'referred_by' => (isset($this->referred_by)) ? $this->referredBy->name : null,
        ];

        if ($this->investment_holder_type == 'store') {
            $data['investor_name'] = $this->storeHolderId->store_name;
        } elseif ($this->investment_holder_type == 'vendor') {
            $data['investor_name'] = $this->vendorHolderId->vendor_name;
        } elseif ($this->investment_holder_type == 'manager'){
            $data['investor_name'] = $this->managerHolderId->manager_name;
        }
        else {
            $data['investor_name'] = $this->userHolderId->name;
        }

        return $data;
    }


}








