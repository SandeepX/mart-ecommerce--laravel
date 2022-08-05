<?php


namespace App\Modules\InvestmentPlan\Requests;

use App\Modules\InvestmentPlan\Models\InvestmentPlanSubscription;
use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvestmentPlanSubscribePayingOfflineRequest extends FormRequest
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

            'ipir_option_code' => 'required|string|exists:investment_plan_interest_release_options,ipir_option_code',
            'amount' => 'required|numeric',
            'payment_mode' => ['required','string',Rule::in(InvestmentPlanSubscription::PAYMENT_MODE)],
            'transaction_type' => ['required', 'string', Rule::in(['investment'])],
            'referred_by' => ['nullable', 'string', 'exists:managers_detail,referral_code'],

            //'payment_for' => ['required',Rule::in(InvestmentPlanSubscription::PAYMENT_FOR)],
            'payment_type' => ['required',Rule::in(InvestmentPlanSubscription::PAYMENT_TYPE)],
            'deposited_by' => ['required','max:191'],
            'purpose' => ['nullable','max:191'],
            'transaction_date' => 'required|date|before_or_equal:today|date_format:Y-m-d',
            'contact_phone_no' =>'required|string',
            //'amount' => ['bail','required','integer','digits_between:1,15'],
            'voucher_number' => ['nullable','string','min:4'],
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
            $rules['deposited_branch_name'] = ['required','max:191'];
            $rules['bank_code'] = ['required',Rule::exists('banks', 'bank_code')];
            $rules['cheque_bank_code'] = ['required',Rule::exists('banks', 'bank_code')];
            $rules['cheque_bank'] = ['required',Rule::exists('banks', 'bank_name')];
            $rules['cheque_holder_name'] = ['required','max:191'];
            $rules['cheque_account_number'] = ['required','max:191','alpha_dash'];
            $rules['cheque_number'] = ['required','min:5','string'];
        }
        elseif($paymentType == 'remit'){
            $rules['remit_name'] = ['required',Rule::exists('remits', 'remit_name')];
            $rules['remit_branch_name'] = ['required','max:191'];
            $rules['remit_code'] = ['required',Rule::exists('remits', 'remit_code')];
            $rules['transaction_number'] = ['required','array'];
            $rules['transaction_number.*'] = ['required','string'];
            $rules['bank_name'] =['required','max:191'];
            $rules['bank_code'] =['required','max:191'];
            $rules['receiver_name'] =['required','max:191'];
            $rules['receiver_bank_account_name'] =['required','max:191'];

        }
        elseif($paymentType == 'wallet'){

            $rules['payment_partner'] = ['required',Rule::exists('digital_wallets', 'wallet_name')];
            $rules['wallet_code'] = ['required',Rule::exists('digital_wallets', 'wallet_code')];

            $paymentPartner =   $this->get('payment_partner');

            if($paymentPartner == 'Connect Ips'){
                $rules['bank_code'] = ['required',Rule::exists('banks', 'bank_code')];
                $rules['bank_name'] = ['required',Rule::exists('banks', 'bank_name')];
                $rules['branch_name'] = ['required','string'];
                $rules['account_number'] = ['required','max:50'];
                $rules['account_holder_name'] = ['required','max:50'];
                $rules['transaction_number'] = ['required','array'];
                $rules['transaction_number.*'] = ['required','string'];
                $rules['remark'] = ['required','string'];
                $rules['sender_bank_code'] = ['required',Rule::exists('banks', 'bank_code')];
                $rules['sender_bank_name'] = ['required',Rule::exists('banks', 'bank_name')];
                $rules['sender_branch_name'] = ['required','string'];
                $rules['sender_account_number'] = ['required','string'];
            }else{
                $rules['receiver_name'] = ['required','max:191','string'];
                $rules['receiver_id'] = ['required','min:10'];
            }
        }
        elseif($paymentType == 'mobile_banking'){
            $rules['bank_code'] = ['required',Rule::exists('banks', 'bank_code')];
            $rules['bank_name'] = ['required',Rule::exists('banks', 'bank_name')];
            $rules['account_number'] = ['required','max:50'];
            $rules['account_holder_name'] = ['required','max:50'];
            $rules['transaction_number'] = ['required','array'];
            $rules['transaction_number.*'] = ['required','string'];
            $rules['remark'] = ['required','string'];
            $rules['sender_bank_code'] = ['required',Rule::exists('banks', 'bank_code')];
            $rules['sender_bank_name'] = ['required',Rule::exists('banks', 'bank_name')];
            $rules['sender_account_number'] = ['required','string'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'transaction_date.date_format' => "The date format is not valid : use Y-m-d Eg. 2021-05-12",
            'referred_by.exists' => 'Invalid Referral Code !'
        ];
    }


}



