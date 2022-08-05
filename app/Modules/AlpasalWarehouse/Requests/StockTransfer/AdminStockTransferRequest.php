<?php


namespace App\Modules\AlpasalWarehouse\Requests\StockTransfer;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminStockTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        $rules['source_warehouse'] = [
            'required',Rule::exists('warehouses','warehouse_code')
                            ->whereNull('deleted_at')
        ];
        $rules['destination_warehouse'] = [
            'required',Rule::exists('warehouses','warehouse_code')
                ->whereNull('deleted_at')
        ];

        return $rules;
    }

}
