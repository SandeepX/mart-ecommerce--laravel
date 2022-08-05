<?php


namespace App\Modules\AlpasalWarehouse\Requests\PreOrder;


use App\Modules\Product\Helpers\ProductHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehousePrePurchaseOrderRequest extends FormRequest
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
        $qualifiedProductsCode = ProductHelper::getQualifiedProductsCode(['productVariants']);
        return [
            'product_code' => 'required|array',
            'product_code.*' => [
                'required',Rule::in($qualifiedProductsCode)
            ],
            'product_variant_code' => 'nullable|array',
            'product_variant_code.*' => 'nullable|exists:product_variants,product_variant_code',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ];
    }
}
