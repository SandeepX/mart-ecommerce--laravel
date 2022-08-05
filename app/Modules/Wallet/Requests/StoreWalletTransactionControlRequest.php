<?php

namespace App\Modules\Wallet\Requests;

use App\Modules\Wallet\Models\Wallet;
use App\Modules\Wallet\Models\WalletTransactionPurpose;
use App\Modules\Wallet\Services\WalletService;
use App\Modules\Wallet\Services\WalletTransactionPurposeService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWalletTransactionControlRequest extends FormRequest
{
    private $walletTransactionPurposeService;
    private $walletService;

    public function __construct(array $query = [], array $request = [],
                                array $attributes = [], array $cookies = [],
                                array $files = [], array $server = [],
                                $content = null,
                                WalletTransactionPurposeService $walletTransactionPurposeService,
                                WalletService $walletService
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->walletTransactionPurposeService = $walletTransactionPurposeService;
        $this->walletService = $walletService;
    }

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
     * Prepare the data for validation.
     * sanitize any data from the request before you apply your validation rules
     * @return void
     */
    protected function prepareForValidation()
    {
        $walletCode= $this->route('walletCode');
        $wallet = $this->walletService->findorFailWalletByWalletCodeAndHolderType(
            $walletCode,'App\Modules\Store\Models\Store'
        );
        $this->merge([
            'store_code' => $wallet->wallet_holder_code,
            //'job_opening' =>$this->route('job_opening')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

       // dd($this->all());

        $transactionType =  $this->walletTransactionPurposeService->findOrFailTransactionPurposeByFilterParams(
              $this->transaction_type, ['is_active'=>1,'admin_control'=>1]
        );

        $rules= [
            'action_type' =>'required|string|max:500',
            'transaction_type'=>['required'],
            'transaction_amount' => ['required','numeric','between:1,999999999.99','regex:/^\d+(\.\d{1,2})?$/'],
            'proof_of_document'=> 'nullable|mimes:jpeg,png,jpg,pdf,doc,docx|max:8192',
            'remarks'=>'required|string|max:2000',
        ];

        if (in_array($transactionType->slug,
            ['sales-reconciliation-deduction','sales-reconciliation-increment']
        ))
        {

            $rules['order_code']=['bail','nullable','required_without:ref_bill_no',
                Rule::exists('store_orders','store_order_code')
                    ->where('store_code',$this->store_code)
                    ->whereNull('deleted_at')
            ];

            $rules['ref_bill_no']=['required_without:order_code'];
        }

        if (in_array($transactionType->slug,
            ['pre-orders-sales-reconciliation-deduction','pre-orders-sales-reconciliation-increment']
        ))
        {
            $rules['order_code']=['bail','nullable','required_without:ref_bill_no',
                Rule::exists('store_preorder','store_preorder_code')
                    ->where('store_code',$this->store_code)
                    ->whereNull('deleted_at')
            ];

            $rules['ref_bill_no']=['required_without:order_code'];
        }

        if(in_array($transactionType->slug,
            ['transaction-correction-increment','transaction-correction-deduction']
        ))
        {
            $rules['transaction_code'] = ['required',
                Rule::exists('wallet_transaction', 'wallet_transaction_code')
                    ->where('wallet_code',$this->route('walletCode'))
            ];
        }

        if ($transactionType->slug === 'cash-received')
        {
            $rules['ref_bill_no']=['required','max:255'];
        }

        $rules['store_code'] = ['required'];

        return $rules;
    }

    public function messages(){
        return[
            'transaction_amount.regex' => 'The Transaction Amount Should be two digit or less after decimal',
        ];
    }

}
