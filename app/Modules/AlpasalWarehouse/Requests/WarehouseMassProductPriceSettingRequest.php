<?php


namespace App\Modules\AlpasalWarehouse\Requests;


use App\Modules\Product\Models\ProductMaster;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseMassProductPriceSettingRequest extends FormRequest
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
            'admin_margin_type' => 'nullable|array|min:1',
            'admin_margin_type.*' => 'required|in:p,f',
            'admin_margin_value' => 'nullable|array|min:1',
            'admin_margin_value.*' => 'nullable|required_with:mrp.*,wholesale_margin_value.*,retail_margin_value.*|numeric|min:0',

            'wholesale_margin_type' => 'required|array|min:1',
            'wholesale_margin_type.*' => 'required|in:p,f',
            'wholesale_margin_value' => 'nullable|array|min:1',
            'wholesale_margin_value.*' => 'nullable|required_with:mrp.*,admin_margin_value.*,retail_margin_value.*|numeric|min:0',

            'retail_margin_type' => 'required|array|min:1',
            'retail_margin_type.*' => 'required|in:p,f',
            'retail_margin_value' => 'nullable|array|min:1',
            'retail_margin_value.*' => 'nullable|required_with:mrp.*,admin_margin_value.*,wholesale_margin_value.*|numeric|min:0',

        ];


        //dd(count(array_filter($this->mrp)) );
        if (count(array_filter($this->mrp)) > 0){
            $rules['mrp'] = 'nullable|array|min:1';
            $rules['mrp.*'] ='nullable|required_with:admin_margin_value.*,wholesale_margin_value.*,retail_margin_value.*|numeric|min:0';
        }
        else{
            $rules['mrp'] = 'required|array|min:1';
            $rules['mrp.*'] = 'required|numeric|min:0';
        }
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
