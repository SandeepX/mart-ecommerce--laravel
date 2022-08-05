<?php


namespace App\Modules\AlpasalWarehouse\Requests\StorePreOrder;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePreOrderDetailUpdateRequest extends FormRequest
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
            //'dispatch_quantity' => 'required|integer|min:0',
            'delivery_status' => 'required|in:1,0',
        ];

        if ($this->delivery_status == 1){
            $rules['dispatch_quantity']  =['required','integer','min:1'];
        }
        else{
           $rules['dispatch_quantity']  =['required','integer'];
        }

        return $rules;
    }

    public function messages()
    {
        $minMessage="The dispatch quantity must be at least 1 when delivery status is accepted.";
        if ($this->delivery_status == 0){
            $minMessage="The dispatch quantity must be 0 when delivery status is rejected.";
        }
        return [
            'dispatch_quantity.min' => $minMessage,
            'dispatch_quantity.in' => $minMessage,

        ];
    }
}
