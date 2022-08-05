<?php


namespace App\Modules\AlpasalWarehouse\Requests\Setting;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MinOrderSettingUpdateRequest extends FormRequest
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
            'status'=>['required',Rule::in(1,0)],
        ];

        return $rules;
    }


}
