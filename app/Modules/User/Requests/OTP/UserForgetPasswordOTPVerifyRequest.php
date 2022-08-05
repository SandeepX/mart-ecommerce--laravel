<?php

namespace App\Modules\User\Requests\OTP;

use App\Modules\OTP\Models\OTP;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserForgetPasswordOTPVerifyRequest extends FormRequest
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
        $rules = [];
        $rules['otp_request_via'] = ['required',Rule::in(OTP::OTP_REQUEST_VIA)];

        if($this->get('otp_request_via') == 'phone'){
           $rules['phone'] = ['required','integer','digits:10'];
        }else{
           $rules['email'] = ['required', 'email','max:255'];
        }

        $rules['otp_code'] = ['required','integer','digits:4'];

        return $rules;
    }

    public function messages()
    {
        return [
            'phone.digits' => 'phone number must be of 10 digits',
            'otp_code.digits' => 'OTP code must be of 4 digits'
        ];
    }

}
