<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 12:36 PM
 */

namespace App\Modules\User\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CheckUserPhoneExistsRequest extends FormRequest
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
            'phone' => 'required|digits:10|starts_with:9',
        ];
    }

}
