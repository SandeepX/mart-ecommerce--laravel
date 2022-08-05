<?php


namespace App\Modules\AlpasalWarehouse\Requests\PreOrder;


use App\Modules\Product\Models\ProductMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehousePreOrderProductPriceUpdateRequest extends FormRequest
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
            //'admin_margin_type' => 'nullable|array|min:1',
            'mrp' => 'required|numeric|min:0',
            'admin_margin_type' => 'required|in:p,f',
            //'admin_margin_value' => 'nullable|array|min:1',
            'admin_margin_value' => 'nullable|numeric|min:0',

            //'wholesale_margin_type' => 'required|array|min:1',
            'wholesale_margin_type' => 'required|in:p,f',
            //'wholesale_margin_value' => 'nullable|array|min:1',
            'wholesale_margin_value' => 'nullable|numeric|min:0',

            //'retail_margin_type' => 'required|array|min:1',
            'retail_margin_type' => 'required|in:p,f',
            //'retail_margin_value' => 'nullable|array|min:1',
            'retail_margin_value' => 'nullable|numeric|min:0',

            'min_order_quantity' => 'nullable|numeric|min:1',
            'max_order_quantity' => ['nullable','numeric','min:1']

        ];
        if($this->filled('min_order_quantity')){
            array_push($rules['max_order_quantity'],'gt:min_order_quantity');
        }

        $product = ProductMaster::find($this->route('productCode'));
        if($product){
            $variantCodes = $product->productVariants()->pluck('product_variant_code')->toArray();
            if($variantCodes){
                $rules['product_variant_code'] =[Rule::in($variantCodes)];
            }else{
                $rules['product_variant_code'] = Rule::in(null);
            }
        }


        return $rules;
    }

}
