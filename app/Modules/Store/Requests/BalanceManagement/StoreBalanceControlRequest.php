<?php
/**
 * Created by VScode.
 * User: sandeep
 * Date: 12/17/2020
 * Time: 11:57 PM
 */

namespace App\Modules\Store\Requests\BalanceManagement;

use App\Modules\Store\Models\Balance\StoreBalanceWithdrawRequest;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBalanceControlRequest extends FormRequest
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
            'action_type' =>'required|string|max:500',
            'transaction_type'=>['required',Rule::in(StoreBalanceMaster::TRANSACTION_TYPE)],
            'transaction_amount' => ['required','numeric','between:0,999999999.99','regex:/^\d+(\.\d{1,2})?$/'],
            'proof_of_document'=> 'nullable|mimes:jpeg,png,jpg,pdf,doc,docx|max:8192',
            'remarks'=>'required|string|max:1000',
        ];

        if ($this->transaction_type == 'sales_reconciliation_deduction'
            ||
            $this->transaction_type == 'sales_reconciliation_increment')
        {

            $rules['order_code']=['bail','nullable','required_without:ref_bill_no',
              Rule::exists('store_orders','store_order_code')
                ->where('store_code',$this->route('storeCode'))
                ->whereNull('deleted_at')
            ];

            $rules['ref_bill_no']=['required_without:order_code'];
        }

        if ($this->transaction_type == 'pre_orders_sales_reconciliation_deduction'
            ||
            $this->transaction_type == 'pre_orders_sales_reconciliation_increment')
        {
            $rules['order_code']=['bail','nullable','required_without:ref_bill_no',
              Rule::exists('store_preorder','store_preorder_code')
                ->where('store_code',$this->route('storeCode'))
                ->whereNull('deleted_at')
            ];

            $rules['ref_bill_no']=['required_without:order_code'];
        }

        if($this->transaction_type == 'transaction_correction_increment'
            ||
            $this->transaction_type =='transaction_correction_deduction')
        {
            $rules['transaction_code'] = ['required',
                Rule::exists('store_balance_master', 'store_balance_master_code')
                    ->where('store_code',$this->route('storeCode'))
            ];
        }

        if ($this->transaction_type == 'cash_received')
        {
            $rules['ref_bill_no']=['required','max:255'];
        }

        return $rules;
    }

    public function messages(){
        return[
            'transaction_amount.regex' => 'The Transaction Amount Should be two digit or less after decimal',
        ];
    }






}
