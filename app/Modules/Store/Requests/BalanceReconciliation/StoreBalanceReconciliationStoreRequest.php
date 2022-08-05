<?php

/**
 * Created by VScode.
 * User: sandeep
 * Date: 12/17/2020
 * Time: 11:57 PM
 */

namespace App\Modules\Store\Requests\BalanceReconciliation;


use App\Modules\Store\Models\BalanceReconciliation\StoreBalanceReconciliation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBalanceReconciliationStoreRequest extends FormRequest
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
            'transaction_type' => ['required', Rule::in(StoreBalanceReconciliation::transaction_type)],
            'payment_method' => ['required', Rule::in(StoreBalanceReconciliation::payment_method)],
            'status' => ['nullable', Rule::in(StoreBalanceReconciliation::status)],
            'payment_body_code' => 'required|string',
            'transaction_no' => ['nullable','string','max:100',Rule::unique('balance_reconciliation','transaction_no')->where(function ($query) {
                return $query->where('payment_body_code', $this->payment_body_code);
             })],
            'transaction_amount' => ['bail', 'required', 'integer', 'digits_between:1,9'],
            'transacted_by'=>['nullable','string'],
            'transaction_date' => 'required|date|before_or_equal:today',
            'description' =>['required','max:2000'],

        ];

        return $rules;
    }

}

