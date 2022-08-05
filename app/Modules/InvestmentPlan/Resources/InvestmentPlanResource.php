<?php


namespace App\Modules\InvestmentPlan\Resources;


use App\Modules\InvestmentPlan\Models\InvestmentPlan;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentPlanResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'investment_plan_code' => $this->investment_plan_code,
            'name' => $this->name,
            'investment_type' => isset($this->investmentType) ? $this->investmentType->name : null,
            'image' => asset(InvestmentPlan::IMAGE_PATH.$this->image),
            'maturity_period' => $this->maturity_period,
            'target_capital' => $this->target_capital,
            'price_start_range' => $this->price_start_range,
            'price_end_range' => $this->price_end_range,
            'interest_rate' => $this->interest_rate,
        ];
    }
}







