<?php


namespace App\Modules\Vendor\Requests;


use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseReturn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorSalesReturnRespondRequest extends FormRequest
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

        $rules = [
            'status' => ['required',Rule::in('accepted','rejected')],
            'status_remarks'=>['required','max:5000'],
            'accepted_return_quantity'=>['required_if:status,accepted','integer','min:1']

        ];

        return $rules;
    }
}
