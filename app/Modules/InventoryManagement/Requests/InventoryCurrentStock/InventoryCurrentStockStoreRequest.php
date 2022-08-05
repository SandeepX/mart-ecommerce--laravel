<?php


namespace App\Modules\InventoryManagement\Requests\InventoryCurrentStock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryCurrentStockStoreRequest extends FormRequest
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
            'vendor_name' => 'required|string',
            'product_code' => 'required|exists:products_master,product_code',
            'product_variant_code' => 'nullable|exists:product_variants,product_variant_code',
            'pph_code' => 'required|exists:product_packaging_history,product_packaging_history_code',
            'package_code' => 'required|exists:package_types,package_code',
            'quantity' => 'required|integer|gt:0',
            'cost_price' => 'required|numeric|gt:0|regex:/^\d+(\.\d{1,2})?$/',
            'mrp' => 'required|numeric|gte:cost_price|regex:/^\d+(\.\d{1,2})?$/',
            'manufacture_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'expiry_date' => 'required|date_format:Y-m-d|after_or_equal:manufacture_date',

        ];
    }

}

