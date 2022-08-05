<?php


namespace App\Modules\Store\Requests\PreOrder;

use App\Modules\Product\Services\ProductService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePreOrderCreateRequest extends FormRequest
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


    public function rules()
    {
        $rules = [
            'product_slug' => 'required',
            'quantity' => 'required|integer|min:1',
            'package_code'=>['required']
            // 'warehouse_code' => ['required', Rule::in(StoreWarehouseHelper::getActiveWarehousesCodeAssociatedWithStore(getAuthStoreCode()))]
        ];
        return $rules;
    }

}
