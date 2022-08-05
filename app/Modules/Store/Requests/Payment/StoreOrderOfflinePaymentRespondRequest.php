<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/2/2020
 * Time: 10:57 AM
 */

namespace App\Modules\Store\Requests\Payment;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderOfflinePaymentRespondRequest extends FormRequest
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
            'payment_status' => ['required',Rule::in(['verified','rejected'])],
            'remarks'=>['required_if:payment_status,rejected','max:2000'],
        ];

        return $rules;
    }
}
