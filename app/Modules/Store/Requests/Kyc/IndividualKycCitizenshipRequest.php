<?php

namespace App\Modules\Store\Requests\Kyc;

use App\Rules\NepaliDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndividualKycCitizenshipRequest extends FormRequest
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
        $rules = [

            'citizenship_no' => ['required'],
            //'citizenship_full_name' => ['required','max:191'],
            'citizenship_nationality' => ['required',Rule::in(['nepali'])],
            'citizenship_issued_date' => ['required'],
            'citizenship_gender' => ['required',Rule::in(['m','f','others'])],
            'citizenship_birth_place' => ['required','max:191'],
            'citizenship_district' => ['required','max:191'],
            //'citizenship_municipality' => ['required','max:191'],
            //'citizenship_ward_no' => ['required','max:191'],
            'citizenship_dob' => ['required','max:191'],
            'citizenship_father_name' => ['required','max:191'],
            //'citizenship_father_address' => ['required','max:191'],
            //'citizenship_father_nationality' => ['required','max:191'],
            'citizenship_mother_name' => ['required','max:191'],

            'citizenship_grandfather_name' => ['nullable','max:191'],
            //'citizenship_grandfather_nationality' => ['nullable','max:191'],
            //'citizenship_mother_address' => ['required','max:191'],
            //'citizenship_mother_nationality' => ['required','max:191'],
            'citizenship_spouse_name' => ['nullable','max:191'],
            //'citizenship_spouse_address' => ['nullable','max:191'],
            //'citizenship_spouse_nationality' => ['nullable','max:191'],

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
