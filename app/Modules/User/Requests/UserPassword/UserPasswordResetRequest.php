<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 12:36 PM
 */

namespace App\Modules\User\Requests\UserPassword;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserPasswordResetRequest extends FormRequest
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
        $rules  = [];
        $rules['reset_method'] = ['nullable',Rule::in('token','otp')];
        $rules['token'] = [$this->get('reset_method') != 'otp' ? 'required' : 'nullable'];
        $rules['login_email'] = ['required_without:login_phone','email'];
        $rules['login_phone'] = ['required_without:login_email','integer','digits:10'];

        $rules['password'] = ['required','confirmed','min:8'];

        return $rules;

    }

    public function messages()
    {
        return [
            'token.required' => 'Token is required !',
            'login_email.required' => 'Email is required !',
            'login_email.email' => 'Please Provide Valid Email Address',
        ];

    }

}
