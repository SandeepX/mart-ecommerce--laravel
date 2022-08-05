<?php

namespace App\Modules\Store\Requests;

use App\Modules\Store\Models\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Validation\Rule;

class StoreUpdateApiRequest extends FormRequest
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
        //$store = Store::find($this->route('storeCode'));
        $store = Store::find(getAuthStoreCode());

        return [
            //'store_name' => 'required|unique:stores_detail,store_name,' . $store->store_name . ',store_name|max:191',
            'store_name' => 'required|max:191',
            'store_location_code' => [
                'nullable',
                Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                    $query->where('location_type', 'ward');
                })
            ],
            'store_owner' => 'nullable|max:191',
            'store_size_code' => 'nullable|max:191',
            'store_contact_phone' => 'nullable|max:191',
            'store_contact_mobile' => 'nullable|max:191',
            'store_email' => 'nullable|email|max:191',
            'store_registration_type_code' => 'nullable|exists:registration_types,registration_type_code',
            'store_company_type_code' => 'nullable|exists:company_types,company_type_code',
            'store_established_date' => 'nullable|date',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store_landmark_name' => 'nullable|max:191',
            'latitude' => 'nullable|numeric|max:191',
            'longitude' => 'nullable|numeric|max:191',
            'pan_vat_type' => 'required',
            'pan_vat_no' => 'string|required|unique:stores_detail',
        ];
    }


    public function messages()
    {
        return [
            'store_name.required' => 'The store name field is required',
           'store_name.exists' => 'Invalid store name',
            //'slug.unique' => 'Store name already exists, try maintaining spaces',
          //  'store_location_code.required' => 'Please select the ward where store is located',
           // 'store_location_code.exists' => 'Invalid store location'
        ];
    }
}
