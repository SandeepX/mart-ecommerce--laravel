<?php

namespace App\Modules\Store\Requests\StorePackageTypes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTypePackageUpdateRequest extends FormRequest
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
     * Prepare the data for validation.
     * sanitize any data from the request before you apply your validation rules
     * @return void
     */
    protected function prepareForValidation()
    {
        $trimmed = preg_replace('/\s+/', ' ', $this->package_name);
        $this->merge([
            'package_name' => $trimmed,
            //'slug' => make_slug($trimmed),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'package_name' => ['required','max:191',Rule::unique('store_type_package_master','package_name')
                ->where('store_type_code',$this->store_type_code)
                ->ignore($this->route('storeTPCode'),'store_type_package_master_code')
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|dimensions:max_width=350,max_height=200|max:2048',
            'description' => 'required',
            'refundable_registration_charge' => 'required|numeric|min:0',
            'non_refundable_registration_charge' => 'required|numeric|min:0',
            'base_investment' => 'required|numeric|min:0',
            'annual_purchasing_limit' => 'required|numeric|min:1',
            'referal_registration_incentive_amount' => 'required|numeric|min:0',
            'store_type_code' => 'required|max:191',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'package_name.required' => 'The store Package Name field is required',
            'image.required' => 'The  Image field is required',
            'description.required' => 'The Description field is required',
            'refundable_registration_charge.required' => 'The Refundable Registration Charge field is required',
            'non_refundable_registration_charge.required' => 'The Non Refundable Registration Charge field is required',
            'base_investment.required' => 'The Base Investment field is required',
            'annual_purchasing_limit.required' => 'The Annual Purchasing Limit field is required',
            'referal_registration_incentive_amount.required' => 'Referal Registration Incentive Amount field is required',
        ];
    }
}
