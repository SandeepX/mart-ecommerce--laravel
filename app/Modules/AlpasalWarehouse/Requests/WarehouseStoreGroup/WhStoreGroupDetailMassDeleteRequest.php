<?php


namespace App\Modules\AlpasalWarehouse\Requests\WarehouseStoreGroup;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WhStoreGroupDetailMassDeleteRequest extends FormRequest
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
            'group_detail_codes' => 'required|array',
            'group_detail_codes.*' => ['required'],
        ];
    }
}
