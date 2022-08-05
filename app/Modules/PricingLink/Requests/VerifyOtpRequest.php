<?php


namespace App\Modules\PricingLink\Requests;

use Illuminate\Foundation\Http\FormRequest;


class VerifyOtpRequest extends FormRequest
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
            'otp_code' =>'required|numeric|exists:user_pricing_view,otp_code',
            'link_code' =>'required|string|exists:pricing_master,link_code',
            'mobile_number' =>'required|exists:user_pricing_view,mobile_number'
        ];
    }

}

