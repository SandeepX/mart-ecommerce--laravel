<?php


namespace App\Modules\InvestmentPlan\Requests;


use App\Modules\InvestmentPlan\Models\InvestmentPlanCommission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class InvestmentPlanCommissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'investment_plan_code' => ['required', 'string', 'exists:investment_plans,investment_plan_code'],
            'commission_type' => ['required', Rule::in(InvestmentPlanCommission::COMMISSION_TYPE)],
            'commission_mount_type' => ['required', Rule::in(InvestmentPlanCommission::COMMISSION_MOUNT_TYPE)],
            'commission_amount_value' => ['required', 'numeric'],
            'is_active' => ['nullable', 'boolean', Rule::in([1, 0])],
        ];
    }

}


