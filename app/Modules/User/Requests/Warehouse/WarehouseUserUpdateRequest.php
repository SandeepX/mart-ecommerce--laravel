<?php


namespace App\Modules\User\Requests\Warehouse;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseUserUpdateRequest extends FormRequest
{

    public function rules()
    {
        // $route=Route::getCurrentRoute();
        $userCode =$this->route('warehouse_user');
        return [
            'name' => 'required|max:255',
            // 'login_email' => ['required','max:255',Rule::unique('users','login_email')->ignore($userCode,'user_code')],
            //'password' => 'required|min:6|confirmed',
            // 'login_phone' => 'required|digits:10|unique:users,login_phone|regex:/(9)[0-9]{9}/',
            'login_phone' => ['required','digits:10','regex:/(9)[0-9]{9}/',Rule::unique('users','login_phone')->ignore($userCode,'user_code')],
            'role_id' =>['required','array'],
            'role_id.*' => [Rule::exists('roles','id')->where(function ($query) {
                $query->where('for_user_type', 'warehouse');
            })]
        ];
    }
}
