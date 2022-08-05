<?php

namespace App\Modules\Store\Requests\Kyc;

use App\Modules\Store\Models\Kyc\IndividualKYCDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndividualKycDocumentRequest extends FormRequest
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
        /*$documentTypeSize=count(IndividualKYCDocument::DOCUMENT_TYPES);
        $rules = [
            'document_type' =>['required','array','size:'.$documentTypeSize],
            'document_type.*' =>['required',Rule::in(IndividualKYCDocument::DOCUMENT_TYPES)],
            'document_file' =>['required','array','size:'.$documentTypeSize],
            'document_file.*' =>['required','file','mimes:jpeg,jpg,png,pdf','max:8192'],
        ];*/

        $rules=[
            'update_citizenship_front' => ['required',Rule::in([0,1])],
            'update_citizenship_back' => ['required',Rule::in([0,1])],
        ];

        if ($this->get('update_citizenship_front') == 1){
            $rules['citizenship_front'] = ['sometimes','nullable','file','mimes:jpeg,jpg,png,pdf','max:8192'];
        }
        else{
            $rules['citizenship_front'] = ['required','file','mimes:jpeg,jpg,png,pdf','max:8192'];
        }


        if ($this->get('update_citizenship_back') == 1){
            $rules['citizenship_back'] = ['sometimes','nullable','file','mimes:jpeg,jpg,png,pdf','max:8192'];
        }
        else{
            $rules['citizenship_back'] = ['required','file','mimes:jpeg,jpg,png,pdf','max:8192'];
        }

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