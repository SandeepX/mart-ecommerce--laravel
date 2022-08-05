<?php


namespace App\Modules\AlpasalWarehouse\Requests\Setting;


use App\Modules\AlpasalWarehouse\Models\Setting\InvoiceSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceSettingCreateRequest extends FormRequest
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
            'order_type'=>['required',Rule::in('store_order','store_pre_order')],
            'starting_number'=>['required','integer'],
            'ending_number'=>['required','integer','gt:starting_number']
        ];

        return $rules;
    }


}
