<?php


namespace App\Modules\B2cCustomer\Requests;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class B2CUserRegisterRequest extends FormRequest
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
        // dd($this->all());
        $rules = [];
        $rules['name'] = 'required|max:255';
        $rules['login_email'] = 'required|email|max:255|unique:users,login_email';
        $rules['login_phone'] = 'required|digits:10|unique:users,login_phone|regex:/(9)[0-9]{9}/';
        $rules['password'] = 'required|min:6|confirmed';
        $rules['password_confirmation'] = 'required|min:6';
        $rules['gender'] = ['required',Rule::in(User::GENDER)];

        $rules['avatar'] = 'nullable|image|mimes:jpeg,png,jpg|max:5048';
//        $rules['has_two_wheeler_license'] = 'nullable|boolean';
//        $rules['has_four_wheeler_license'] = 'nullable|boolean';
        $rules['ward_code'] = 'required|exists:location_hierarchy,location_code';
//        $rules['doc_issued_district'] = 'nullable|exists:location_hierarchy,location_code';

        return $rules;
    }

    public function messages()
    {
        return [
            'avatar.image' => 'profile picture should be image file',
            'has_four_wheeler_license.required' => 'Vehicle Information is Required',
            'avatar.mimes' => 'profile picture uploaded is in invalid format : (required :jpeg,png,jpg )'
        ];
    }


}

