<?php

namespace App\Modules\Store\Requests\Withdraw;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddVerificationDetailRequest extends FormRequest
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
            'addmore.*.payment_verification_source' => 'required_with:addmore.*.payment_body_code,addmore.*.amount,addmore.*.proof,addmore.*.remarks|nullable|string',
            'addmore.*.amount' => 'required_with:addmore.*.payment_verification_source,addmore.*.payment_body_code,addmore.*.proof,addmore.*.remarks|nullable|string',
            'addmore.*.proof' => 'required_with:addmore.*.payment_verification_source,addmore.*.amount,addmore.*.payment_body_code,addmore.*.remarks|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'addmore.*.remarks' => 'required_with:addmore.*.payment_verification_source,addmore.*.amount,addmore.*.proof,addmore.*.payment_body_code|nullable|string|max:500',
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'addmore.*.payment_verification_source.required_with' => 'payment verification source is required',
            'addmore.*.amount.required_with' => 'amount is required',
            'addmore.*.proof.required_with' => 'proof is required',
            'addmore.*.status.required_with' => 'status is required',
            'addmore.*.remarks.required_with' => 'remarks is required',
        ];
    }
}
