<?php
/**
 * Created by PhpStorm.
 * User: Sandeep Pant
 * Date: 10/29/2020
 * Time: 2:04 PM
 */

namespace App\Modules\Store\Requests\Payment;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMiscPaymentRespondRequest extends FormRequest
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
            'verification_status' => ['required',Rule::in(['verified','rejected'])],
            'remarks'=>['required_if:verification_status,rejected','max:2000'],
            'balance_reconciliation_code' => ['required_if:verification_status,verified','exists:balance_reconciliation,balance_reconciliation_code'],
        ];


        return $rules;
    }
}
