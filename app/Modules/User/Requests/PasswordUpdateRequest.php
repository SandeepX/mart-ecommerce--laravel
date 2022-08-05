<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/23/2020
 * Time: 4:57 PM
 */

namespace App\Modules\User\Requests;


use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
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
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Current password is required',
            'password.required' => 'New password is required',
        ];
    }

}