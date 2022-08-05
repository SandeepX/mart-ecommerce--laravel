<?php


namespace App\Modules\InvestmentPlan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class InvestmentPlanRequest extends FormRequest
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
            'maturity_period' => ['required', 'integer'],
            'target_capital' => ['required', 'numeric'],
            'price_start_range' => ['required', 'numeric'],
            'price_end_range' => ['required', 'numeric', 'gt:price_start_range'],
            'interest_rate' => ['required', 'numeric'],
            'description' => ['required', 'string', 'min:10'],
            'terms' => ['required', 'string', 'min:10'],
//            'sort_order' => ['required', 'numeric'],
            'is_active' => ['nullable', 'boolean', Rule::in([1, 0])],
            'ip_type_code'=> ['required','exists:investment_plan_types,ip_type_code'],
            'paid_up_capital' =>['nullable','numeric'],
          //  'per_unit_share_price' =>['nullable','numeric'],

      ];
        //IPT1000 is interest type code , per_unit_share_price is nullable only when interest type is selected else is required
        if($this->ip_type_code == 'IPT1000'){
            $rule['per_unit_share_price'] = ['nullable','numeric'];
        }else{
            $rule['per_unit_share_price'] = ['required','numeric'];
        }

        if ($this->isMethod('put')) {
            $rule['image'] = ['sometimes','file', 'mimes:jpeg,png,jpg,gif,svg|max:5048'];
        } else {
            $rule['image'] = ['required','file', 'mimes:jpeg,png,jpg,gif,svg|max:5048'];
         }
        return $rule;
    }


}

