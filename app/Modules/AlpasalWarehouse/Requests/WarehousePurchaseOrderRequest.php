<?php

namespace App\Modules\AlpasalWarehouse\Requests;

use App\Modules\Product\Helpers\ProductHelper;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehousePurchaseOrderRequest extends FormRequest
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
      //dd($this->all());
       //dd($this['product_variant_code']);
      // dd($this['product_variant_code']['P1001'][0]);
       $qualifiedProductsCode = ProductHelper::getQualifiedProductsCode(['productVariants']);
        return [
            //'warehouse_code' => 'required|exists:warehouses,warehouse_code',
            'vendor_code' => ['required',Rule::exists('vendors_detail','vendor_code')->where(function($query){
                $query->where('is_active',1);
            })],
            'product_code' => 'required|array',
            'product_code.*' => [
                'required',Rule::in($qualifiedProductsCode)

            ],
            'product_variant_code' => 'nullable|array',
           /// 'product_variant_code.*.*' => 'required|exists:product_variants,product_variant_code',
            'product_variant_code.*' => 'nullable|exists:product_variants,product_variant_code',
            'package_code'=>['required',Rule::exists('package_types','package_code')],
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'submit_type' => 'required|in:draft,sent'
        ];
    }

}
