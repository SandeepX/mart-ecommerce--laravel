<?php


namespace App\Modules\AlpasalWarehouse\Requests\StockTransfer;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StockTransferStoreWarehouseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $this->customRules();
        return [
            'warehouse_name' => 'required|warehouse_code_validation',
            'remarks' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'warehouse_name.warehouse_code_validation' => 'You should choose other warehouse',
        ];
    }

    public function customRules()
    {
        Validator::extend('warehouseCodeValidation', function($attribute, $value, $parameters, $validator) {
            if($value == getAuthWarehouseCode())
            {
                return false;
            }
            return true;
        });
    }

}