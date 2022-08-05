<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
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
//        if(request()->routeIs('admin.vendors.store')){
//            $userTypes = ['vendor'];
//        }else{
//            $userTypes = ['vendor', 'admin'];
//
//        }
        return [
            'name' => 'required|max:255',
            'login_email' => 'required|max:255|unique:users,login_email',
            'login_phone' => 'required|digits:10|unique:users,login_phone|regex:/(9)[0-9]{9}/',
          //  'user_type' => ['required',Rule::in($userTypes)],
            'password' => 'required|min:6|confirmed',
            'role_id' =>['required','array'],
            'role_id.*' => [Rule::exists('roles','id')->where(function ($query) {
                $query->where('for_user_type', 'admin');
            })]
        ];
    }
}
