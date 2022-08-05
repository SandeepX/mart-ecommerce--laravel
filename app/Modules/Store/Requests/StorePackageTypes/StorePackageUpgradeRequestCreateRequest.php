<?php

namespace App\Modules\Store\Requests\StorePackageTypes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePackageUpgradeRequestCreateRequest extends FormRequest
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
//    protected function prepareForValidation()
//    {
//        $trimmed = preg_replace('/\s+/', ' ', $this->package_name);
//        $this->merge([
//            'package_name' => $trimmed,
//            //'slug' => make_slug($trimmed),
//        ]);
//    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store_code' => 'required|max:191',
            //'slug' => 'required_with:store_name|unique:stores_detail,slug|max:191',
            'requested_store_type' => 'required|max:191',
            'requested_package_type' => 'required|max:191',
            'remark' => 'nullable',
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
            'store_code.required' => 'The store Code field is required',
            'requested_store_type.required' => 'The Requested Store Type field is required',
            'requested_package_type.required' => 'The Requested Package Type field is required',
        ];
    }
}
