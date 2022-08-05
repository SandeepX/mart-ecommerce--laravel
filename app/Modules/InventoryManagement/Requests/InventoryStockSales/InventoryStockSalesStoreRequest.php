<?php


namespace App\Modules\InventoryManagement\Requests\InventoryStockSales;


use App\Modules\InventoryManagement\Models\StoreInventoryItemDispatched;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryStockSalesStoreRequest extends FormRequest
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
            'product_code' => 'required|exists:products_master,product_code',
            'product_variant_code' => 'nullable|exists:product_variants,product_variant_code',
            'siid_code' => 'required|exists:store_inventory_item_detail,siid_code',
            'pph_code' => 'required|exists:product_packaging_history,product_packaging_history_code',
            'payment_type' => ['required',Rule::in(StoreInventoryItemDispatched::PAYMENT_TYPE)],
            'package_code' => 'required|exists:package_types,package_code',
            'quantity' => 'required|integer|gt:0',
            'mrp' => 'required|numeric|gte:1|regex:/^\d+(\.\d{1,2})?$/',
            'selling_price' => 'required|numeric|lte:mrp|regex:/^\d+(\.\d{1,2})?$/',
            'manufacture_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'expiry_date' => 'required|date_format:Y-m-d|after_or_equal:manufacture_date',
        ];
    }

}


