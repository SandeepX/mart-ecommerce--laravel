<?php

namespace App\Modules\Vendor\Requests;

use App\Exceptions\Custom\PermissionDeniedException;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Rule\PriceMarginRule;
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
        //Check Product Belongs To Vendor
        $productCodes = auth()->user()->vendor->products()->pluck('product_code')->toArray();
        if(!in_array($this->route('product'), $productCodes)){
            throw new PermissionDeniedException();
        }
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
            'product_variant_code' => 'nullable|array',
            'mrp' => 'required|array|min:1',
            'mrp.*' => 'required|numeric|min:0',
            'admin_margin_type' => 'bail|required|array|min:0',
            'admin_margin_type.*' => 'bail|required|in:p,f',
            'admin_margin_value' => 'required|array|min:0',
            'admin_margin_value.*' =>['bail','required','numeric','gte:0',new PriceMarginRule($this->mrp,$this->admin_margin_type)],

             'wholesale_margin_type' => 'required|array|min:0',
             'wholesale_margin_type.*' => 'required|in:p,f',
             'wholesale_margin_value' => 'required|array|min:0',
             'wholesale_margin_value.*' => ['bail','required','numeric','gte:0',new PriceMarginRule($this->mrp,$this->wholesale_margin_type)],

             'retail_store_margin_type' => 'required|array|min:0',
             'retail_store_margin_type.*' => 'required|in:p,f',
             'retail_store_margin_value' => 'required|array|min:0',
             'retail_store_margin_value.*' => ['bail','required','numeric','gte:0',new PriceMarginRule($this->mrp,$this->retail_store_margin_type)],
        ];


        $product = ProductMaster::find($this->route('product'));
        if ($product->hasVariants()){
            $variantCodes = $product->productVariants()->pluck('product_variant_code')->toArray();
        }

        if(isset($variantCodes)){
            $rules['product_variant_code.*'] = [Rule::in($variantCodes)];
        }else{
            $rules['product_variant_code.*'] =[Rule::in(null)];
        }

        return $rules;

    }
}
