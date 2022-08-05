<?php

namespace App\Modules\Store\Requests\Kyc;

use App\Modules\Store\Models\Kyc\KycAgreementVideo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgreementVideoRequest extends FormRequest
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
           'agreement_video_for' => ['required', Rule::in(KycAgreementVideo::AGREEMENT_VIDEO_FOR_TYPES)],
            'video_file' =>['required' ,'file', 'mimetypes:video/avi,video/mp4,video/mpeg', 'max:100000'// 100 MB
                 ]
            //'video_file' =>['required' ,'image', 'max:100000']
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