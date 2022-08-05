<?php


namespace App\Modules\InvestmentPlan\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentInterestReleaseResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'ipir_option_code' => $this->ipir_option_code,
            'investment_plan_code' =>$this->investment_plan_code,
            'investment_plan_name' => ucfirst($this->investmentPlan->name),
            'interest_release_time' => ucfirst($this->interest_release_time)
        ];
    }
}







