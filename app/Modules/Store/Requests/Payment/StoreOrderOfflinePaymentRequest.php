<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 1:06 PM
 */

namespace App\Modules\Store\Requests\Payment;


use App\Modules\Store\Models\Payments\StoreOrderOfflinePayment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderOfflinePaymentRequest extends FormRequest
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
        $rules= [
            'payment_type' => ['required',Rule::in(StoreOrderOfflinePayment::PAYMENT_TYPE)],
            'deposited_by' => ['required','max:191'],
            'purpose' => ['required','max:191'],
            'amount' => ['bail','required','integer','digits_between:1,15'],
            'voucher_number' => ['required','max:191','alpha_dash'],
            'document_types' => ['required','array','max:3'],
            'document_types.*' => ['required','max:191'],
            'document_images' => ['required','array','max:3'],
            'document_images.*' => ['required','mimes:jpg,jpeg,png,svg','max:8192'],
        ];

        $paymentType = $this->get('payment_type');

        if ($paymentType == 'cash'){

            $rules['bank_code'] = ['required',Rule::exists('banks', 'bank_code')];
            $rules['bank_name'] = ['required',Rule::exists('banks', 'bank_name')];
            $rules['branch_name'] = ['required','max:191'];
        }
        elseif($paymentType == 'cheque'){

            $rules['deposit_bank_name'] = ['required',Rule::exists('banks', 'bank_name')];
            $rules['cheque_bank'] = ['required',Rule::exists('banks', 'bank_name')];
            $rules['cheque_holder_name'] = ['required','max:191'];
            $rules['cheque_account_number'] = ['required','max:191','alpha_dash'];
            $rules['cheque_number'] = ['required','max:191','alpha_dash'];
        }
        elseif($paymentType == 'remit'){

            $rules['remit_name'] = ['required',Rule::exists('remits', 'remit_name')];
            $rules['branch_name'] = ['required','max:191'];
        }
        elseif($paymentType == 'wallet'){

            $rules['payment_partner'] = ['required',Rule::exists('digital_wallets', 'wallet_name')];
        }


        return $rules;
    }
}