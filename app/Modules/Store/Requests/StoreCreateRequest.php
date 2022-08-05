<?php

namespace App\Modules\Store\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCreateRequest extends FormRequest
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
        $trimmed = preg_replace('/\s+/', ' ', $this->store_name);
        $this->merge([
            'store_name' => $trimmed,
            //'slug' => make_slug($trimmed),
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'store_contact_phone' => 'contact landline',
            'store_contact_mobile' => 'contact mobile',
            'store_email' => 'contact email',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store_name' => 'required|max:191',
            //'slug' => 'required_with:store_name|unique:stores_detail,slug|max:191',
            'store_location_code' => [
                'required',
                Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                    $query->where('location_type', 'ward');
                })
            ],
            'store_owner' => 'required|max:191',
            'store_size_code' => [
                'required',
                Rule::exists('store_sizes', 'store_size_code')->where(function ($query) {
                    $query->where('is_active', 1);
                })
            ],
            'store_contact_phone' => 'required|max:191',
            'store_contact_mobile' => 'required|max:191',
           // 'store_email' => 'required|email|max:191|unique:stores_detail,store_email',
            'store_email' => 'required|email|max:191',
            'store_registration_type_code' => [
                'required',
                Rule::exists('registration_types', 'registration_type_code')->where(function ($query) {
                    $query->where('is_active', 1);
                })
            ],
            'store_company_type_code' => [
                'required',
                Rule::exists('company_types', 'company_type_code')->where(function ($query) {
                    $query->where('is_active', 1);
                })
            ],
            'store_established_date' => 'required|date',
            'store_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'store_landmark_name' => 'required|max:191',
//            'latitude' => 'required|numeric|max:191',
//            'longitude' => 'required|numeric|max:191',
            'pan_vat_type' => 'required',
            'pan_vat_no' => 'string|nullable',
            // 'referred_by' => 'required|exists:users,user_code',


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
            'store_size_code.required' => 'The store size field is required',
            'store_size_code.exists' => 'Invalid store size',
            //'slug.unique' => 'Store name already exists, try maintaining spaces',
            'store_location_code.required' => 'The store location field is required',
            'store_location_code.exists' => 'Invalid store location'
        ];
    }
}
