<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/7/2020
 * Time: 3:48 PM
 */

namespace App\Modules\SystemSetting\Requests;


use App\Rules\WithoutSpaces;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PassportSettingRequest extends FormRequest
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
            'passport_login_endpoint' => ['required','url'],
            'passport_client_id' => ['required',new WithoutSpaces()],
            'passport_client_secret' => ['required',new WithoutSpaces()],
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
            'passport_login_endpoint.required' => 'Login endpoint is required',
            'passport_login_endpoint.url' => 'Login endpoint must be a valid url',
            'passport_client_id.required' => 'Client id is required',
            'passport_client_secret.required' => 'Client secret is required',

        ];
    }
}