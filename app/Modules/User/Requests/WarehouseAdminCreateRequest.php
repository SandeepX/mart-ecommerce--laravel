<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/11/2020
 * Time: 12:09 PM
 */

namespace App\Modules\User\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseAdminCreateRequest extends FormRequest
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
            'login_email' => 'required|max:255|unique:users,login_email',
            //'password' => 'required|min:6|confirmed',
            'login_phone' => 'required|digits:10|unique:users,login_phone|regex:/(9)[0-9]{9}/',
//            'role_id' =>['required','array'],
//            'role_id.*' => [Rule::exists('roles','id')->where(function ($query) {
//                $query->where('for_user_type', 'warehouse');
//            })]
        ];
    }
}
