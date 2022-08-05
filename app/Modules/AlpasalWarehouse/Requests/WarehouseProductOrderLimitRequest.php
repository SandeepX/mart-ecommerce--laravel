<?php


namespace App\Modules\AlpasalWarehouse\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseProductOrderLimitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $rules = [
            'warehouse_product_master_code' => 'required',
            'min_order_quantity' => 'nullable|numeric|min:1',
            'max_order_quantity' => ['nullable','numeric','min:1']
        ];
        if($this->filled('min_order_quantity')){
            array_push($rules['max_order_quantity'],'gt:min_order_quantity');
        }

        return $rules;
    }
}








