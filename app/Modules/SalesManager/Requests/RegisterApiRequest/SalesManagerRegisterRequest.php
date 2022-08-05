<?php


namespace App\Modules\SalesManager\Requests\RegisterApiRequest;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalesManagerRegisterRequest extends FormRequest
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
        $rules=[];
        $rules['name' ]='required|max:255';
        $rules['login_email' ]='required|email|max:255|unique:users,login_email';
        $rules['login_phone' ]='required|digits:10|unique:users,login_phone|regex:/(9)[0-9]{9}/';
        $rules['avatar'] = 'nullable|image|mimes:jpeg,png,jpg|max:10240';
        //$rules['citizenship_number_eng'] = 'required';
        $rules['has_two_wheeler_license'] = 'required|boolean';
        $rules['has_four_wheeler_license'] = 'required|boolean';
        $rules['password'] = 'required|min:6|confirmed';
        $rules['password_confirmation'] = 'required|min:6';
        $rules['ward_code'] = 'required|exists:location_hierarchy,location_code';
        $rules['temporary_ward'] = 'required|exists:location_hierarchy,location_code';
       // $rules['referral_code'] = 'nullable|exists:managers_detail,referral_code';
        $rules['doc_issued_district'] = 'nullable|exists:location_hierarchy,location_code';
        $rules['referral_code'] ='nullable|max:191|exists:managers_detail,referral_code';
        $rules['phone_otp_code']= 'required|digits:4';
//        $rules['email_otp_code']='required|digits:4';
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
