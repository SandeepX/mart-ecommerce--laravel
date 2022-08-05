<?php

namespace App\Modules\AlpasalWarehouse\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseBillMergeRequest extends FormRequest
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
        return [
            'store_code' => 'required',
            'store_order_code' => 'nullable|array',
            'store_order_code.*' => 'nullable',
            'store_preorder_code' =>'nullable|array',
            'store_preorder_code.*' =>'nullable'
        ];
    }
}
