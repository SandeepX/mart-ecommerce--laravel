<?php


namespace App\Modules\InvestmentPlan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class InvestmentSubscriptionRequest extends FormRequest
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
            'ipir_option_code' => 'required|string|exists:investment_plan_interest_release_options,ipir_option_code',
            'amount' => 'required|numeric',
            'transaction_type' => ['required','string', Rule::in(['investment'])],
            'referred_by' => ['nullable','string','exists:managers_detail,referral_code'],
        ];
    }

    public function messages()
    {
        return [
            'referred_by.exists' => 'Invalid Referral Code !'
        ];
    }


}


