<?php


namespace App\Modules\AlpasalWarehouse\Requests;


use Illuminate\Foundation\Http\FormRequest;

class WarehouseProductChangeStatus extends FormRequest
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
            'product_code' => 'required|exists:warehouse_product_master,product_code',
            'is_active' => 'required|boolean',
        ];
    }
}
