<?php


namespace App\Modules\AlpasalWarehouse\Requests\PreOrder;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehousePreOrderCancelRequest extends FormRequest
{

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
        $rules = [
            'remarks' => ['required','max:80000']
        ];

        return $rules;
    }
}
