<?php

namespace App\Modules\User\Requests\OTP;

use App\Modules\OTP\Models\OTP;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegistrationOTPCreateRequest extends FormRequest
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
            'email' => ['required', 'email','max:255'],
          //  'password' => ['required', 'string','min:6'],
            'otp_request_via' => ['required',Rule::in(OTP::OTP_REQUEST_VIA)]
        ];
    }

}
