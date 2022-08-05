<?php

namespace App\Modules\Store\Requests\Kyc;

use Illuminate\Foundation\Http\FormRequest;

class IndividualKycFamilyDetailRequest extends FormRequest
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
                'spouse_name' => ['nullable','max:191'],
                'father_name' =>['required','max:191'],
                'mother_name' =>['required','max:191'],
                'grand_father_name' =>['required','max:191'],
                'grand_mother_name' =>['required','max:191'],
                'sons' => ['nullable','array','between:0,2'],
                'sons.*' => ['nullable','max:191'],
                'daughters' =>['nullable','array','between:0,2'],
                'daughters.*' =>['nullable','max:191'],
                'daughter_in_laws' =>['nullable','array','between:0,2'],
                'daughter_in_laws.*' =>['nullable','max:191'],
                'father_in_law' =>['nullable','max:191'],
                'mother_in_law' =>['nullable','max:191']
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

        return [];
        return [
           'sons.0.max' => 'The name mentioned for son 1 may not be greater than 191 characters',
           'sons.1.max' => 'The name mentioned for son 2 may not be greater than 191 characters',
           'daughters.0.max' => 'The name mentioned for daughter 1 may not be greater than 191 characters',
           'daughters.1.max' => 'The name mentioned for daughter 2 may not be greater than 191 characters',
           'daughter_in_laws.0.max' => 'The name mentioned for daughter in law 1 may not be greater than 191 characters',
           'daughter_in_laws.1.max' => 'The name mentioned for daughter in law 2 may not be greater than 191 characters'

        ];
    }

}