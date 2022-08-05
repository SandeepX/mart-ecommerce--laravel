<?php


namespace App\Modules\AlpasalWarehouse\Requests\PreOrder;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehousePreOrderProductsMicroDisableRequest extends FormRequest
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
            'packaging_status' => ['required',Rule::in([0,1])],
        ];
    }
}
