<?php

namespace App\Modules\Product\Requests\ProductPrice;

use App\Modules\Product\Models\ProductMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductPriceRequest extends FormRequest
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
            'product_variant_code' => 'required|array|min:1',
            'mrp' => 'required|array|min:1',
            'mrp.*' => 'numeric|min:1',
            'admin_margin_type' => 'required|array|min:1',
            'admin_margin_type.*' => 'required|in:p,f',
            'admin_margin_value' => 'required|array|min:1',
            'admin_margin_value.*' => 'numeric|min:1',

            'wholesale_margin_type' => 'required|array|min:1',
            'wholesale_margin_type.*' => 'required|in:p,f',
            'wholesale_margin_value' => 'required|array|min:1',
            'wholesale_margin_value.*' => 'numeric|min:1',

            'retail_store_margin_type' => 'required|array|min:1',
            'retail_store_margin_type.*' => 'required|in:p,f',
            'retail_store_margin_value' => 'required|array|min:1',
            'retail_store_margin_value.*' => 'numeric|min:1',
        ];

            
        $product = ProductMaster::find($this->route('product'));
        $variantCodes = $product->productVariants()->pluck('product_variant_code')->toArray();
        if(isset($variantCodes)){
            $rules['product_variant_code.*'] = Rule::in($variantCodes);
        }else{
            $rules['product_variant_code.*'] = Rule::in(null);
        }

        return $rules;

    }
}
