<?php

namespace App\Modules\Store\Requests\RegisterApiRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserCreateApiRequest extends FormRequest
{ public function authorize()
{
    return true;
}

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'login_email' => 'required|max:255|unique:users,login_email',
            'login_phone' => 'required|digits:10|unique:users,login_phone|regex:/(9)[0-9]{9}/',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',

        ];
    }
}
