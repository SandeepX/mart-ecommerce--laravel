<?php

namespace App\Modules\Cart\Requests;

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Services\ProductService;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartRequest extends FormRequest
{

    private $productService;

    public function __construct(array $query = [], array $request = [],
                                array $attributes = [], array $cookies = [],
                                array $files = [], array $server = [],
                                $content = null, ProductService $productService)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->productService = $productService;
    }

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
            'product_slug' => 'required',
            'quantity' => 'required|integer|min:1',
            'package_code'=>['required',Rule::exists('package_types','package_code')]
           // 'warehouse_code' => ['required', Rule::in(StoreWarehouseHelper::getActiveWarehousesCodeAssociatedWithStore(getAuthStoreCode()))]
        ];

        $product = $this->productService->findOrFailProductBySlug($this->product_slug);

        if ($product->hasVariants()) {
            $rules['combination_name'] = ['required'];
        }
        // $productVariantCodes = $product->productVariants->pluck('product_variant_code')->toArray();

        // if (count($productVariantCodes) > 0){
        //     $rules[ 'product_variant_code'] =[Rule::in($productVariantCodes)];
        // }else{
        //     $rules[ 'product_variant_code'] =[Rule::in(null)];
        // }

        return $rules;
    }
}
