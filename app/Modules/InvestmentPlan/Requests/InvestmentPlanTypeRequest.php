<?php


namespace App\Modules\InvestmentPlan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class InvestmentPlanTypeRequest extends FormRequest
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
        $rule = [
            'name' => ['required', 'string'],
            'description' => ['required', 'string', 'min:10'],
            'is_active' => ['nullable', 'boolean', Rule::in([1, 0])]
        ];

        return $rule;
    }


}

