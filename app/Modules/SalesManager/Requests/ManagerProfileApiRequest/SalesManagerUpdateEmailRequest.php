<?php

namespace App\Modules\SalesManager\Requests\ManagerProfileApiRequest;

use Illuminate\Foundation\Http\FormRequest;

class SalesManagerUpdateEmailRequest extends FormRequest
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
        $rules['email'] = 'required|email|max:255|unique:users,login_email,'.getAuthUserCode().',user_code';
        return $rules;
    }

    public function messages()
    {
        return [
            'avatar.image' => 'profile picture should be image file'
        ];
    }
}
