<?php

namespace App\Modules\InvestmentPlan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvestmentSubscriptionPlanRespondRequest extends FormRequest
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

        $rules = [
            'admin_status' => ['required',Rule::in(['accepted','rejected'])],
            'admin_remark'=>['required_if:status,rejected','max:2000'],
            'balance_reconciliation_code' => ['nullable', 'exists:balance_reconciliation,balance_reconciliation_code'],
        ];
        return $rules;
    }
}
