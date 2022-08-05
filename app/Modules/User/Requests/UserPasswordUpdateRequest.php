<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/19/2020
 * Time: 11:20 AM
 */

namespace App\Modules\User\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UserPasswordUpdateRequest extends FormRequest
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
            'password' => 'required|min:6|confirmed',

        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'New password is required',
        ];
    }
}
