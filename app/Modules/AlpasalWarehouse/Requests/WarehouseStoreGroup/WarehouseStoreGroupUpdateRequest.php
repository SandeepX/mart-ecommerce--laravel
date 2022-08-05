<?php


namespace App\Modules\AlpasalWarehouse\Requests\WarehouseStoreGroup;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseStoreGroupUpdateRequest extends FormRequest
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
        $storeGroupCode =$this->route('groupCode');
        return [
            'name' => ['required','max:255',Rule::unique('warehouse_store_groups','name')->ignore($storeGroupCode,'wh_store_group_code')],
            'description' => ['nullable','max:5000'],
            'is_active' => ['required',Rule::in([0,1])]
        ];
    }
}
