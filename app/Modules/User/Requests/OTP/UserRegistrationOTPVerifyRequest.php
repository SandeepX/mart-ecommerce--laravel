<?php

namespace App\Modules\User\Requests\OTP;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationOTPVerifyRequest extends FormRequest
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
            'email' => ['required', 'email:rfc,dns','max:255'],
        //    'password' => ['required', 'string','min:6'],
            'otp_code' => ['required','numeric']
        ];
    }

}
