<?php


namespace App\Modules\AlpasalWarehouse\Requests\Setting;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MinOrderSettingCreateRequest extends FormRequest
{

    //by warehouse, for warehouse
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
        $rules = [
            'min_order_amount'=>['required','integer','min:1'],
        ];

        return $rules;
    }


}
