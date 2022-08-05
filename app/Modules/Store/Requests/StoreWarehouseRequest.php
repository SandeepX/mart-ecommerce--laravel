<?php

namespace App\Modules\Store\Requests;

use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWarehouseRequest extends FormRequest
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
        $warehouseCodes = (new WarehouseRepository())->getAllWarehousesByType('closed')->pluck('warehouse_code')->toArray();
        return [
            //'store_code' => 'required|exists:stores_detail,store_code',
            'store_code' => ['required', Rule::exists('stores_detail', 'store_code')->where(function ($query) {
                return $query->where('is_active', 1);
            })],
            'warehouse_codes' => 'required|array',
            // 'warehouse_codes.*' => 'required|exists:warehouses,warehouse_code',
            'warehouse_codes.*' => ['required',Rule::in($warehouseCodes)],
        ];
    }
}
