<?php

namespace App\Modules\Store\Requests\RegisterApiRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCreateApiRequest extends FormRequest
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
//        $trimmed = preg_replace('/\s+/', ' ', $this->store_name);
//        $this->merge([
//            'store_name' => $trimmed,
//            //'slug' => make_slug($trimmed),
//        ]);
//    }

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

        $rules=[];
//        $rules['store_landmark_name' ]='required|max:191';
        $rules['has_store' ]='required';
//        $rules['latitude' ]='required|numeric|max:191';
//        $rules['longitude' ]='required|numeric|max:191';
        $rules['store_logo' ]='nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240';
       // $rules['store_contact_phone' ]='required|max:191';
        $rules['store_contact_phone' ]='nullable|max:191';
        //$rules['store_contact_mobile' ]='required|max:191';
        //$rules['store_email' ]='required|email|max:191';
        $rules['store_type_code'] = ['required',Rule::exists('store_types','store_type_code')
            ->where(function($query){
                $query->where('is_active',1);
            })
        ];
        $rules['store_type_package_history_code'] =['required','max:191',
            Rule::exists('store_type_package_history','store_type_package_history_code')
            ->where(function($query){
                $query->where('store_type_code',$this->store_type_code)
                    ->where('is_active',1);
            })
            ];
        $rules['referred_by'] ='nullable|max:191|exists:managers_detail,referral_code';

        $rules['store_location_code' ]=[
            'required',
            Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                $query->where('location_type', 'ward');
            })
        ];
//        $rules['store_type' ]=[
//            'required',
//            Rule::exists('store_types', 'store_type_slug')->where(function ($query) {
//                $query->where('is_active', '1');
//            })
//        ];

        if ($this->get('has_store')=='1') {
            $rules['store_name' ]='required|max:191';
            $rules['store_owner' ]='required|max:191';
//            $rules['store_size_code' ]=[
//                'required',
//                Rule::exists('store_sizes', 'store_size_code')->where(function ($query) {
//                    $query->where('is_active', 1);
//                })
//            ];
//            $rules['store_registration_type_code' ]=[
//                'required',
//                Rule::exists('registration_types', 'registration_type_code')->where(function ($query) {
//                    $query->where('is_active', 1);
//                })
//            ];
//            $rules['store_company_type_code' ]=[
//                'required',
//                Rule::exists('company_types', 'company_type_code')->where(function ($query) {
//                    $query->where('is_active', 1);
//                })
//            ];

            $rules['pan_vat_type' ]='nullable';

           // $rules['store_established_date' ]='required|date';

            $rules['pan_vat_no' ]=['nullable','string'];


        }
        $rules['phone_otp_code']='required|digits:4';
//        $rules['email_otp_code']='required|digits:4';
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
           // 'pan_vat_type.required' => 'Pan/Vat type is required',
//            'store_size_code.required' => 'The store size field is required',
//            'store_size_code.exists' => 'Invalid store size',
            'store_type.exists' => 'Invalid store type',
            //'slug.unique' => 'Store name already exists, try maintaining spaces',
            'store_location_code.required' => 'The store location field is required',
            'store_location_code.exists' => 'Invalid store location',
           // 'store_established_date.required' => 'Store established date is required',
           // 'store_registration_type_code.required' => 'Store registration type is required.',
            'has_store.required' => 'Information on pre-existing store is required',
            'referred_by.exists' => 'The selected referral code does not exists',
            'store_type_code.exists' => 'The selected store type does not exists',
            'store_type_package_history_code.exists' => 'The selected package does not exists',

        ];
    }
}
