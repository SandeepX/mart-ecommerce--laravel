<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 11:03 AM
 */

namespace App\Modules\SystemSetting\Requests;


use App\Rules\WithoutSpaces;
use Illuminate\Foundation\Http\FormRequest;

class UrlSettingRequest extends FormRequest
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
            'ecommerce_site_url' => ['required','url',new WithoutSpaces()],
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
            'ecommerce_site_url.required' => 'E-commerce site url is required',
            'ecommerce_site_url.url' => 'E-commerce site url must be a valid url',

        ];
    }
}