<?php

/**
 * Created by VScode.
 * User: sandeep
 * Date: 08/30/2021
 * Time: 11:57 PM
 */


namespace App\Modules\Store\Requests\BalanceManagement;

use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WithdrawRequestUpdateRequest extends FormRequest
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
            'withdraw_request_code' => ['required','string','exists:store_balance_withdraw_request,store_balance_withdraw_request_code']
        ];
        return $rules;
    }

}

