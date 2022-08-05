<?php

namespace App\Modules\Store\Requests\Kyc;

use App\Modules\Store\Models\Kyc\IndividualKYCMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndividualKycRequest extends FormRequest
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
        $trimmedName = preg_replace('/\s+/', ' ', $this->name_in_english);
        $this->merge([
            'name_in_english' => $trimmedName,
            //'job_opening' =>$this->route('job_opening')
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
            'kyc_for' => ['required', Rule::in(['sanchalak', 'akhtiyari'])],
            'name_in_english' => ['required','max:191'],
            'name_in_devanagari' => ['required','max:191'],
            'marital_status' => ['required', Rule::in(['married', 'unmarried'])],
            'pan_no' => ['nullable','alpha_dash'],
//            'gender' => ['nullable', Rule::in(['m', 'f', 'others'])],
            'educational_qualification' => ['required', Rule::in(IndividualKYCMaster::EDUCATIONAL_QUALIFICATIONS)],
            'permanent_house_no' => ['nullable', 'max:191'],
            'permanent_street' => ['required', 'max:191'],
            'permanent_ward_no' => [
                'required',
                Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                    $query->where('location_type', 'ward');
                })
            ],
            'present_house_no' => ['nullable', 'max:191'],
            'present_street' => ['required', 'max:191'],
            'present_ward_no' => [
                'required',
                Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                    $query->where('location_type', 'ward');
                })
            ],
//            'landmark' => ['nullable', 'max:191'],
//            'latitude' => ['nullable', 'numeric'],
//            'longitude' => ['nullable', 'numeric'],
            'landlord_name' => ['nullable','required_with:landlord_contact_no','max:191'],
            'landlord_contact_no' => ['nullable','required_with:landlord_name', 'digits:10', 'regex:/(9)[0-9]{9}/']
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
