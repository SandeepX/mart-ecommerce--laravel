<?php


namespace App\Modules\AlpasalWarehouse\Requests\WarehouseStoreGroup;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseStoreGroupCreateRequest extends FormRequest
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
            'name' => ['required','max:255',Rule::unique('warehouse_store_groups','name')],
            'description' => ['nullable','max:5000'],
            'store_code' => ['required','array','min:1'],
            'store_code.*' => ['required','distinct'],
            //'sort_order' => ['required','array','min:1'],
            //'sort_order.*' => ['required','distinct'],
        ];
    }
}
