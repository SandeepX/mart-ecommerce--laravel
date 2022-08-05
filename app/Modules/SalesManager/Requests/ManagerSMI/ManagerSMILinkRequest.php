<?php


namespace App\Modules\SalesManager\Requests\ManagerSMI;

use Illuminate\Foundation\Http\FormRequest;

class ManagerSMILinkRequest extends FormRequest
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
            'social_media' =>['required','array','min:1'],
            'social_media.*.sm_code'=> ['required','exists:social_medias,sm_code'],
            'social_media.*.links' => ['required', 'array', 'min:1'],
            'social_media.*.links.*' => ['required_with:social_media.*.sm_code','nullable','url']
        ];
        return $rules;
    }

}



