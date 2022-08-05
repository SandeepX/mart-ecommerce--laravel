<?php

namespace App\Modules\Store\Requests\Kyc;

use Illuminate\Foundation\Http\FormRequest;

class IndividualKycBankDetailRequest extends FormRequest
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
        $bankCodeLength = 0;

        $bankCodes=$this->get('bank_code');

        if (isset($bankCodes)){
            $bankCodeLength= count($bankCodes);
        }

        $rules = [
            'bank_code' =>['nullable','array'],
            'bank_code.*' =>['nullable','distinct','exists:banks,bank_code','required_with:bank_branch_name.*,bank_account_no.*,bank_account_holder_name.*'],
            'bank_branch_name' =>['nullable','array','size:'.$bankCodeLength],
            'bank_branch_name.*' =>['nullable','required_with:bank_code.*','max:191'],
            'bank_account_no' =>['nullable','array','size:'.$bankCodeLength],
            'bank_account_no.*' =>['nullable','required_with:bank_code.*','max:191'],
            'bank_account_holder_name' => ['nullable','array','size:'.$bankCodeLength],
            'bank_account_holder_name.*' => ['nullable','required_with:bank_code.*','max:191'],
            'deleted_bank_code'=>['nullable','array'],
            'deleted_bank_code.*' =>['nullable','distinct','exists:banks,bank_code'],

        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [

            'bank_code.*.required_with' => 'Bank name,Account holder name,branch name and account number should be filled',
//            'bank_code.array' => 'Invalid bank format',
//            'bank_code.*.required' => 'Bank required',
//            'bank_code.*.distinct' => 'Duplicate Bank Selected',
//            'bank_code.*.exists' => 'Invalid bank',
//            'bank_branch_name.size' => 'Please fill all branch names',
//            'bank_account_no.size' => 'Please fill all account number',
//            'bank_account_holder_name.size' => 'Please fill all account holder name for all banks selected',
        ];
    }

}
