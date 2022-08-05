<?php


namespace App\Modules\AlpasalWarehouse\Requests;


use App\Modules\Product\Models\ProductMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseProductPackageDisableRequest extends FormRequest
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
            //'product_variant_code' => 'required|array|min:1',
            //'mrp.*' => 'nullable|required_with:admin_margin_value.*,wholesale_margin_value.*,retail_margin_value.*|numeric|min:0',
            'micro_unit_code' => 'nullable|array|min:1',
            'micro_unit_code.*' => ['nullable',Rule::in([0,1])],

            'unit_code' => 'nullable|array|min:1',
            'unit_code.*' => ['nullable',Rule::in([0,1])],

            'macro_unit_code' => 'nullable|array|min:1',
            'macro_unit_code.*' => ['nullable',Rule::in([0,1])],

            'super_unit_code' => 'nullable|array|min:1',
            'super_unit_code.*' => ['nullable',Rule::in([0,1])],
        ];


        $product = ProductMaster::find($this->route('productCode'));
        if($product){
            $variantCodes = $product->productVariants()->pluck('product_variant_code')->toArray();
            if($variantCodes){
                $rules['product_variant_code.*'] =['distinct',Rule::in($variantCodes)];
            }else{
                $rules['product_variant_code.*'] = Rule::in(null);
            }
        }


        return $rules;
    }
}
