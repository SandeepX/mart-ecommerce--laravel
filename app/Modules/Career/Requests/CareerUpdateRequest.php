<?php

namespace App\Modules\Career\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
class CareerUpdateRequest extends FormRequest
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
//        dd($this->request);
        return [
            'title' => ['required','max:255','unique:careers,career_code,'.$this->route('career').''],
//            'slug'=>'required|unique',
            'descriptions'=>'nullable',

            'is_active'=>['nullable',Rule::in(0,1)],
//            'created_by'=>'bail|required',
        ];
    }
}
