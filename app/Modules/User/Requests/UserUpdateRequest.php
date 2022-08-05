<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
        return [
            'name' => 'required|max:255',
            'login_email' => 'required|max:255|unique:users,login_email,'.$this->route('user').',user_code',
            'login_phone' => 'required|digits:10|regex:/(9)[0-9]{9}/|unique:users,login_phone,'.$this->route('user').',user_code',
            'role_id' =>['required','array'],
            'role_id.*' => [Rule::exists('roles','id')->where(function ($query) {
                $query->where('for_user_type', 'admin');
            })]
        ];
    }
}
