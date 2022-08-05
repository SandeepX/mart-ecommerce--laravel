<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/1/2020
 * Time: 12:36 PM
 */

namespace App\Modules\Newsletter\Requests;


use Illuminate\Foundation\Http\FormRequest;

class SubscriberStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,dns|unique:subscribers,email',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.unique' => 'Given email is already a subscriber',
        ];
    }
}