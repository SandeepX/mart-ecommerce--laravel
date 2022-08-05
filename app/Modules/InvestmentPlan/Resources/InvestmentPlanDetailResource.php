<?php


namespace App\Modules\InvestmentPlan\Resources;

use App\Modules\InvestmentPlan\Models\InvestmentPlan;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentPlanDetailResource extends JsonResource
{

    public function toArray($request)
    {

        $data = [
                'investment_plan_code' => $this->investment_plan_code,
                'name' => $this->name,
                'investment_type' => isset($this->investmentType) ? $this->investmentType->name:null,
                'paid_up_capital' =>$this->paid_up_capital,
                'per_unit_share_price' =>$this->per_unit_share_price,
                'image' => asset(InvestmentPlan::IMAGE_PATH.$this->image),
                'maturity_period' => $this->maturity_period,
                'target_capital' => $this->target_capital,
                'price_start_range' => $this->price_start_range,
                'price_end_range' => $this->price_end_range,
                'is_active' => $this->is_active,
                'interest_rate' => $this->interest_rate,
                'description' =>$this->description,
                'terms' =>$this->terms,
                'investment_interest_detail' => new InvestmentInterestReleaseCollection($this->activeInvestmentInterestDetail),
                'investment_commission_detail' => null

            ];
        return $data;
    }
}






