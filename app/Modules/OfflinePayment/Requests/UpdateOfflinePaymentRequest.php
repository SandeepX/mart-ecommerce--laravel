<?php

namespace App\Modules\OfflinePayment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfflinePaymentRequest extends FormRequest
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
        $offlinePaymentCode = $this->route('offline_payment');

        $rules = [
            'transaction_date' => ['required'],
            'payment_meta_code'=>['nullable','array','max:3'],
            'payment_meta_code.*'=>['nullable',Rule::exists(
                'offline_payments_meta','offline_payment_meta_code')
                ->where('offline_payment_code',$offlinePaymentCode)
                ->where('key','transaction_number')
            ],
            'transaction_number' => ['nullable','array','max:3'],
            'transaction_number.*' => ['nullable','string'],
            'payment_meta_remark_code'=>['nullable',Rule::exists(
                'offline_payments_meta','offline_payment_meta_code')
                ->where('offline_payment_code',$offlinePaymentCode)
                ->where('key','remark')
            ],
            'remark' =>['nullable','string'],
            'payment_meta_admin_description_code'=>['nullable',Rule::exists(
                'offline_payments_meta','offline_payment_meta_code')
                ->where('offline_payment_code',$offlinePaymentCode)
                ->where('key','admin_description')
            ],
            'admin_description' => ['nullable','string']
        ];



        return $rules;
    }

}
