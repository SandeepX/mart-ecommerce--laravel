<?php


namespace App\Modules\AlpasalWarehouse\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseBillMergeStatusUpdateRequest extends FormRequest
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
            'status' => ['required',Rule::in(['cancelled','ready_to_dispatch'])],
            'remarks'=>['required','max:5000']
        ];

//        if($this->status == 'dispatched'){
//            $rules['driver_name'] = ['required'];
//            $rules['vehicle_type'] = ['required'];
//            $rules['vehicle_number'] = ['required'];
//            $rules['contact_number'] = ['required'];
//            $rules['expected_delivery_time'] = ['required'];
//        }

        return $rules;
    }

}
