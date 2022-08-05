<?php

namespace App\Modules\Vendor\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorCreateRequest extends FormRequest
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
            'vendor_name' => 'required|unique:vendors_detail,vendor_name|max:191',
            'vendor_type_code' => 'required|exists:vendor_types,vendor_type_code',
            'registration_type_code' => 'required|exists:registration_types,registration_type_code',
            'company_type_code' => 'required|exists:company_types,company_type_code',
            'company_size' => 'max:191',
            'vendor_location_code' => [
                'required',
                Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                    $query->where('location_type', 'ward');
                })
            ],
            'vendor_landmark' => 'max:191',
            'landmark_latitude' => 'numeric',
            'landmark_longitude' => 'numeric',
            'vendor_owner' => 'required|max:191',
            'pan' => [
                'string',
                'nullable',
                function ($attribute, $value, $fail) {
                    if (request()->has($attribute) === request()->filled('vat')) {
                        return $fail('Only 1 of the two pan or vat is allowed');
                    }
                }
            ],
            'vat' => 'required_without:pan|string|nullable',
            'contact_person' => 'max:255',
            'contact_landline' => 'numeric',
            'contact_mobile' => 'numeric',
            'contact_email' => 'email|max:255',
            'contact_fax' => 'numeric',
            'vendor_logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
