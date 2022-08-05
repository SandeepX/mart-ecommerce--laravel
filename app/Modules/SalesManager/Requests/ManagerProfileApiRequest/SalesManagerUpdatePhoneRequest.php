<?php

namespace App\Modules\SalesManager\Requests\ManagerProfileApiRequest;

use Illuminate\Foundation\Http\FormRequest;

class SalesManagerUpdatePhoneRequest extends FormRequest
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
        $rules = [];
        $rules['phone'] = 'required|digits:10|unique:users,login_phone,'.getAuthUserCode().',user_code|regex:/(9)[0-9]{9}/';
        return $rules;
    }

    public function messages()
    {
        return [
            'avatar.image' => 'profile picture should be image file'
        ];
    }

}
