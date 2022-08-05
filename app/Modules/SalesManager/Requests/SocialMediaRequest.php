<?php


namespace App\Modules\SalesManager\Requests;


use App\Modules\SalesManager\Models\SocialMedia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialMediaRequest extends FormRequest
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
            'base_url'=>'required|string',
            'enabled_for_smi' => 'nullable|boolean:0,1'
        ];

        if($this->isMethod('post')) {
          $rules['social_media_name'] = ['required','string','max:100',
            Rule::unique('social_medias','sm_code')
                ->whereNull('deleted_at')
              ];
        }else{
            $rules['social_media_name'] = ['required','string','max:100',
                Rule::unique('social_medias','sm_code')->ignore($this->route('social_medium'))->whereNull('deleted_at')
//                'unique:social_medias,sm_code,'. $this->route('social_medium').',deleted_at,null'
                ];
        }

        return $rules;
    }

}

