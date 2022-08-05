<?php


namespace App\Modules\Store\Requests\BalanceReconciliation;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BalanceReconciliationUsagesRemarkCreateRequest extends FormRequest
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
            'remark' => 'required|string|max:2000',
        ];
        return $rules;
    }
}
