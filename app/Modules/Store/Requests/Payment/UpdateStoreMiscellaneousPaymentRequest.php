<?php

namespace App\Modules\Store\Requests\Payment;

use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoreMiscellaneousPaymentRequest extends FormRequest
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
        $storePaymentCode = $this->route('payment_code');

        $rules = [
            'transaction_date' => ['required'],
            'payment_meta_code'=>['nullable','array','max:3'],
            'payment_meta_code.*'=>['nullable',Rule::exists(
                'store_miscellaneous_payments_meta','payment_meta_code')
                ->where('store_misc_payment_code',$storePaymentCode)
                ->where('key','transaction_number')
            ],
            'transaction_number' => ['nullable','array','max:3'],
            'transaction_number.*' => ['nullable','string'],
            'payment_meta_remark_code'=>['nullable',Rule::exists(
                'store_miscellaneous_payments_meta','payment_meta_code')
                ->where('store_misc_payment_code',$storePaymentCode)
                ->where('key','remark')
            ],
            'remark' =>['nullable','string'],
            'payment_meta_admin_description_code'=>['nullable',Rule::exists(
                'store_miscellaneous_payments_meta','payment_meta_code')
                ->where('store_misc_payment_code',$storePaymentCode)
                ->where('key','admin_description')
            ],
            'admin_description' => ['nullable','string']
        ];



        return $rules;
    }

}
