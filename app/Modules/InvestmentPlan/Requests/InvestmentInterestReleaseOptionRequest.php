<?php

namespace App\Modules\InvestmentPlan\Requests;

use App\Modules\InvestmentPlan\Models\InvestmentInterestRelease;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class InvestmentInterestReleaseOptionRequest extends FormRequest
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
            'investment_plan_code' =>['required','string','exists:investment_plans,investment_plan_code'],
            'interest_release_time' => ['required',Rule::in(InvestmentInterestRelease::INTEREST_RELEASE_TIME)],
            'is_active' => ['nullable', 'boolean', Rule::in([1, 0])],

        ];
    }

}


