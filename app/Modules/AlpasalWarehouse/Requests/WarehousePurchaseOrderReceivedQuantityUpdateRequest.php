<?php


namespace App\Modules\AlpasalWarehouse\Requests;


use App\Modules\Product\Helpers\ProductHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehousePurchaseOrderReceivedQuantityUpdateRequest extends FormRequest
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
//            'warehouse_order_detail_code' => 'required|array',
//            'warehouse_order_detail_code.*' => ['required',Rule::exists('warehouse_order_details','warehouse_order_detail_code')],

            'product_code' => 'required|array',
            'product_code.*' => ['required',Rule::exists('products_master','product_code')],

            'product_variant_code' => 'nullable|array',
            'product_variant_code.*' => ['nullable',Rule::exists('product_variants','product_variant_code')],
           /* 'received_quantity' => 'required|array|min:1',
            'received_quantity.*' => 'nullable|integer|min:1',*/

            //'micro_received_quantity' => 'required_without:unit_received_quantity,macro_received_quantity,super_received_quantity|array',
            'micro_received_quantity' => 'nullable|array',
            'micro_received_quantity.*' => 'nullable|required_without_all:unit_received_quantity.*,macro_received_quantity.*,super_received_quantity.*|integer|min:0',

           // 'unit_received_quantity' => 'required_without:micro_received_quantity,macro_received_quantity,super_received_quantity|array',
            'unit_received_quantity' => 'nullable|array',
            'unit_received_quantity.*' => 'nullable|required_without_all:micro_received_quantity.*,macro_received_quantity.*,super_received_quantity.*|integer|min:0',

           // 'macro_received_quantity' => 'required_without:micro_received_quantity,unit_received_quantity,super_received_quantity|array|min:1',
            'macro_received_quantity' => 'nullable|array',
            'macro_received_quantity.*' => 'nullable|required_without_all:micro_received_quantity.*,unit_received_quantity.*,super_received_quantity.*|integer|min:0',

           // 'super_received_quantity' => 'required_without:micro_received_quantity,unit_received_quantity,macro_received_quantity|array|min:1',
            'super_received_quantity' => 'nullable|array',
            'super_received_quantity.*' => 'nullable|required_without_all:micro_received_quantity.*,macro_received_quantity.*,unit_received_quantity.*|integer|min:0',
        ];
    }

}
