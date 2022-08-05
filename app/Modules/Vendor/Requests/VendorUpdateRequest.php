<?php

namespace App\Modules\Vendor\Requests;

use App\Modules\Vendor\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorUpdateRequest extends FormRequest
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
        $vendor = Vendor::find($this->route('vendor'));
        return [
            'vendor_name' => 'required|unique:vendors_detail,vendor_name,' . $vendor->vendor_name . ',vendor_name|max:191',
            'vendor_type_code' => 'required|exists:vendor_types,vendor_type_code',
            'registration_type_code' => 'required|exists:registration_types,registration_type_code',
            'company_type_code' => 'required|exists:company_types,company_type_code',
            'company_size' => 'max:191',
           // 'vendor_location_code' => 'sometimes|required|exists:location_hierarchy,location_code',
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
            'vendor_logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'vendor_location_code.required' => 'Please select the ward where vendor is located',
            'vendor_location_code.exists' => 'Invalid vendor location'
        ];
    }
}
