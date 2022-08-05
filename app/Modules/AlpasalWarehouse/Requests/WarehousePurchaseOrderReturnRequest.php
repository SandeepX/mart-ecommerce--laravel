<?php


namespace App\Modules\AlpasalWarehouse\Requests;


use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseReturn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehousePurchaseOrderReturnRequest extends FormRequest
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
            'return_quantity' => 'required|min:1',
            'reason_type' => ['required',Rule::in(WarehousePurchaseReturn::REASON_TYPES)],
            'return_reason_remarks' => 'required|max:5000',
        ];
    }
}
