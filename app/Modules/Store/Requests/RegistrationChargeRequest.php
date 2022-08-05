<?php

namespace App\Modules\Store\Requests;

use App\Modules\Store\Models\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationChargeRequest extends FormRequest
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
            'registration_charge' => 'nullable|numeric|min:1',
            'refundable_registration_charge' => 'nullable|numeric|min:1',
            'base_investment' => 'nullable|numeric|min:1',
        ];
    }


    public function messages()
    {
        return [
//            'registration_charge.required' => 'The Registration Charge field is required',
//            'refundable_registration_charge.required' => 'The Refundable Registration Charge field is required',
//            'base_investment.required' => 'The base Investment field is required',

        ];
    }
}
