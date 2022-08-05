<?php


namespace App\Modules\AlpasalWarehouse\Requests;


use App\Modules\Product\Models\ProductMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseProductPriceSettingRequest extends FormRequest
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
        $authWarehouseCode = getAuthWarehouseCode();

        $rules = [
            'warehouse_product_master_code' => ['required',
                Rule::exists('warehouse_product_master','warehouse_product_master_code')->where(function($query) use($authWarehouseCode){
                    $query->where('warehouse_code',$authWarehouseCode);
                })
            ],
            'mrp' => 'required|integer|min:1',
            'admin_margin_type' => 'required|in:p,f',
            'admin_margin_value' => 'required|numeric|min:0',

            'wholesale_margin_type' => 'required|in:p,f',
            'wholesale_margin_value' => 'required|numeric|min:0',

            'retail_margin_type' => 'required|in:p,f',
            'retail_margin_value' => 'required|numeric|min:0',
        ];

        return $rules;

    }
}
