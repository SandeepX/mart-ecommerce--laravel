<?php

namespace App\Modules\AlpasalWarehouse\Requests\ProductCollection;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductHelper;
use App\Modules\Product\Helpers\ProductHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddProductstoCollectionRequest extends FormRequest
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
//        $activeVerifiedProductsCode= WarehouseProductHelper::getActiveVerifiedProductsCode();
        return [
            'product_codes' => 'required|array',
//            'product_codes.*' => [Rule::in($activeVerifiedProductsCode)],
        ];

    }
}
