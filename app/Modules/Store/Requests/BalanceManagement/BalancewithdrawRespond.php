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

class BalancewithdrawRespond extends FormRequest
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
            'status' => ['required', Rule::in(StoreBalanceWithdrawRequest::status)],
            //'document'=> ['required_if:status,completed','file','mimes:jpeg,jpg,svg,png,docx,pdf','max:2048'],

            'remarks' => ['required_if:status,rejected', 'max:2000'],
            //'verified_at' => 'nullable|date',

        ];
        if ($this->status == "processing" || $this->status == "completed") {
            $rules['addmore.*.payment_verification_source'] = 'required_with:addmore.*.payment_body_code,addmore.*.amount,addmore.*.proof,addmore.*.remarks|nullable|string';
            $rules['addmore.*.amount'] = 'required_with:addmore.*.payment_verification_source,addmore.*.payment_body_code,addmore.*.proof,addmore.*.remarks|nullable|string';
            $rules['addmore.*.proof'] = 'required_with:addmore.*.payment_verification_source,addmore.*.amount,addmore.*.payment_body_code,addmore.*.remarks|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            $rules['addmore.*.remarks'] = 'required_with:addmore.*.payment_verification_source,addmore.*.amount,addmore.*.proof,addmore.*.payment_body_code|nullable|string';
        }
        return $rules;
    }

}
