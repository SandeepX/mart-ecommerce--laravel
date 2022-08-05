<?php

namespace App\Modules\Store\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOTPVerificationRequest extends FormRequest
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
        $rules = [
            'otp_code'=>'required|numeric|digits:4',
        ];

        return $rules;
    }

}
