<?php


namespace App\Modules\InventoryManagement\Requests\InventoryStockSales;


use App\Modules\InventoryManagement\Models\StoreInventoryItemDispatched;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryStockSalesUpdateRequest extends FormRequest
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
            'pph_code' => 'required|exists:product_packaging_history,product_packaging_history_code',
            'package_code' => 'required|exists:package_types,package_code',
            'payment_type' => ['required', Rule::in(StoreInventoryItemDispatched::PAYMENT_TYPE)],
            'quantity' => 'required|integer|gt:0',
            'selling_price' => 'required|numeric|gte:0|regex:/^\d+(\.\d{1,2})?$/'
        ];
    }

}


