<?php
/**
 * Created by VScode.
 * User: sandeep
 * Date: 12/17/2020
 * Time: 11:57 PM
 */

namespace App\Modules\Store\Requests\BalanceManagement;


use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BalanceWithdrawRequest extends FormRequest
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
            'status' => ['nullable',Rule::in(StoreBalanceWithdrawRequest::status)],
            'store_code' =>'required|string|exists:stores_detail,store_code',
            'reason'=>'nullable|string|max:500',
            'requested_amount' => ['bail','required','integer','min:1','digits_between:1,6'],
            'withdraw_date'=>'nullable|date',
            'verified_by' => 'nullable|string|exists:users,user_code',
            //'document' => 'nullable|mimes:jpeg,jpg,svg,png',
            'remarks' =>  ['required_if:status,rejected','max:2000'],
            //'verified_at' => 'nullable|date',
            'kyc_type' => ['required',Rule::in('sanchalak','akhtiyari','firm')],
            'bank_code' => 'required|string',
            'kyc_code' => 'required|string',
        ];

        return $rules;
    }

}
