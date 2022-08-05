<?php


namespace App\Modules\AlpasalWarehouse\Requests\PreOrder;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehousePreOrderTargetRequest extends FormRequest
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
            'store_type_code' => 'required|array|min:1',
            'store_type_code.*' => 'required|string|min:1',
            'target_group_value' => 'nullable|array|min:1',
            'target_group_value.*' => 'nullable|required_without:target_individual_value.*|integer|min:0',
            'target_individual_value' => 'nullable|array|min:1',
            'target_individual_value.*' => 'nullable|required_without:target_group_value.*|integer|min:0',

        ];
    }
}
