<?php


namespace App\Modules\InventoryManagement\Requests\InventoryCurrentStock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryCurrentStockQtyDetailUpdateRequest extends FormRequest
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
            'package_code'=>'required|exists:package_types,package_code',
            'pph_code'=>'required|exists:product_packaging_history,product_packaging_history_code',
            'quantity'=>['required','integer','gt:0'],
        ];
    }

}


