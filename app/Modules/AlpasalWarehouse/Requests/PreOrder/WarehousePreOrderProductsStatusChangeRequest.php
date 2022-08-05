<?php


namespace App\Modules\AlpasalWarehouse\Requests\PreOrder;

use Illuminate\Foundation\Http\FormRequest;

class WarehousePreOrderProductsStatusChangeRequest extends FormRequest
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
        return [
            'is_active' => 'required|boolean',
            'warehouse_preorder_listing_code' =>'required|exists:warehouse_preorder_products,warehouse_preorder_listing_code'
        ];
    }
}

