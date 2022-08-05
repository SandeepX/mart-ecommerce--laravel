<?php

namespace App\Modules\Store\Requests\Kyc;

use App\Modules\Store\Models\Kyc\FirmKycDocument;
use App\Modules\Store\Models\Kyc\FirmKycMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FirmKycRequest extends FormRequest
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
        $trimmedName = preg_replace('/\s+/', ' ', $this->business_name);
        $this->merge([
            'business_name' => $trimmedName
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        
        $rules = [
        'business_name' => ['required','max:191'],
        'business_capital'=> ['required','integer','min:1'],
        'business_registered_from' => ['required',Rule::in(FirmKycMaster::BUSINESS_REGISTERED_FROM)],
        'business_registered_address'=>['required','max:191'],
        'business_address_latitude'=>['required','numeric'],
        'business_address_longitude'=>['required','numeric'],

        'business_pan_vat_type'=> ['required',Rule::in('pan','vat')],
        'business_pan_vat_number' => ['required','max:191'],
        'business_registration_no'=>['required','max:191'],
        'business_registered_date' => ['required','max:191'],
        'purpose_of_business' => ['required','max:191'],
        'share_holders_no'=>['required','integer','min:1'],
        'store_location_ward_no' =>  [
            'required',
            Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                $query->where('location_type', 'ward');
            })
        ],
        ];



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


        ];
    }

}