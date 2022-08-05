<?php


namespace App\Modules\InvestmentPlan\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentPlanCommissionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'ipc_code' => $this->ipc_code,
            'investment_plan_code' =>$this->investment_plan_code,
            'investment_plan_name' => ucfirst($this->investmentPlan->name),
            'commission_type' => $this->commission_type,
            'commission_mount_type' => $this->commission_mount_type,
            'commission_amount_value' => $this->commission_amount_value,

        ];
    }
}








