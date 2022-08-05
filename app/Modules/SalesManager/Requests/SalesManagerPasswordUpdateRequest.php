<?php

/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 03/06/2021
 * Time: 11:19 AM
 */

namespace App\Modules\SalesManager\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesManagerPasswordUpdateRequest extends FormRequest
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
