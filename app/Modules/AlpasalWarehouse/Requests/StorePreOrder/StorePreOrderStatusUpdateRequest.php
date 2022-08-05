<?php


namespace App\Modules\AlpasalWarehouse\Requests\StorePreOrder;


use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePreOrderStatusUpdateRequest extends FormRequest
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
        $deliveryStatus = $this->status;
        $preOrderStatusValidation = [
            //'dispatch_quantity' => 'required|integer|min:0',
            'status' => ['required',Rule::in(['cancelled', 'processing','ready_to_dispatch'])],
            'remarks'=>['required','max:5000']
        ];

//        if($deliveryStatus == 'dispatched'){
//            $preOrderStatusValidation['driver_name'] = 'required|string|max:100';
//            $preOrderStatusValidation['vehicle_type'] = 'required|string|max:100';
//            $preOrderStatusValidation['vehicle_number']='required|string|max:100';
//            $preOrderStatusValidation['contact_number']='required';
//            $preOrderStatusValidation['expected_delivery_time'] = 'required|date|after_or_equal:'.Carbon::now();
//        }

        return $preOrderStatusValidation;
    }

}
