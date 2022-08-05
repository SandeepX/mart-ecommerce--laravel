<?php

namespace App\Modules\Store\Requests\Kyc;

use App\Modules\Store\Helpers\FirmKycQueryHelper;
use App\Modules\Store\Models\Kyc\FirmKycDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FirmKycDocumentRequest extends FormRequest
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
        $existingDocumentTypes =FirmKycQueryHelper::getAuthStoreExistingDocumentTypes();

        $businessRegisteredFrom=$this->get('business_registered_from');

        $rules=[];

        if (in_array('firm_darta_pramaan_patra',$existingDocumentTypes)){
            $rules['firm_darta_pramaan_patra'] = ['nullable','file','mimes:jpeg,jpg,png,pdf','max:8192'];
        }
        else{
            $rules['firm_darta_pramaan_patra'] = ['required','file','mimes:jpeg,jpg,png,pdf','max:8192'];
        }

         if (in_array('pan_vat_darta',$existingDocumentTypes)){
             $rules['pan_vat_darta'] = ['nullable','file','mimes:jpeg,jpg,png,pdf','max:8192'];
         }
         else{
             $rules['pan_vat_darta'] = ['required','file','mimes:jpeg,jpg,png,pdf','max:8192'];
         }

        if (in_array('prabhanda_patra',$existingDocumentTypes)){
            $rules['prabhanda_patra'] = ['nullable','file','mimes:pdf','max:8192'];
        }
        elseif($businessRegisteredFrom == 'private-public-ltd'){
            $rules['prabhanda_patra'] = ['required','file','mimes:pdf','max:8192'];
        }

        if (in_array('niyamaawali',$existingDocumentTypes)){
            $rules['niyamaawali'] = ['sometimes','nullable','file','mimes:pdf','max:8192'];
        }
        elseif($businessRegisteredFrom == 'private-public-ltd' || $businessRegisteredFrom == 'partnership'){
            $rules['niyamaawali'] = ['required','file','mimes:pdf','max:8192'];
        }

        if (in_array('minute',$existingDocumentTypes)){
            $rules['minute'] = ['nullable','file','mimes:pdf','max:8192'];
        }
        elseif($businessRegisteredFrom == 'partnership'){
            $rules['minute'] = ['required','file','mimes:pdf','max:8192'];
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