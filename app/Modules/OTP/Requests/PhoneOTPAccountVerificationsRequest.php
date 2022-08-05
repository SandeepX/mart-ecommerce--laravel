<?php


namespace App\Modules\OTP\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class PhoneOTPAccountVerificationsRequest extends FormRequest
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
            'phone' =>'required|digits:10|starts_with:9',
//            'entity'=>['required',Rule::in('store','warehouse')]
        ];
    }

}

