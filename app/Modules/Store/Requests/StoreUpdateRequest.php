<?php

namespace App\Modules\Store\Requests;

use App\Modules\Store\Models\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateRequest extends FormRequest
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
        $store = Store::find($this->route('store'));
        return [
            'store_name' => 'required|unique:stores_detail,store_name,' . $store->store_name . ',store_name|max:191',
            'store_location_code' => [
                'required',
                Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                    $query->where('location_type', 'ward');
                })
            ],
            'store_owner' => 'required|max:191',
            'store_size_code' => 'required|max:191',
            'store_contact_phone' => 'required|max:191',
            'store_contact_mobile' => 'required|max:191',
            'store_email' => 'required|email|max:191',
            'store_registration_type_code' => 'required|exists:registration_types,registration_type_code',
            'store_company_type_code' => 'required|exists:company_types,company_type_code',
            'store_established_date' => 'required|date',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store_landmark_name' => 'required|max:191',
            'latitude' => 'required|numeric|max:191',
            'longitude' => 'required|numeric|max:191',
            'pan_vat_type' => 'required',
            'pan_vat_no' => 'string|nullable',
        ];
    }


    public function messages()
    {
        return [
            'store_size_code.required' => 'The store size field is required',
            'store_size_code.exists' => 'Invalid store size',
            //'slug.unique' => 'Store name already exists, try maintaining spaces',
            'store_location_code.required' => 'Please select the ward where store is located',
            'store_location_code.exists' => 'Invalid store location'
        ];
    }
}
