<?php

namespace App\Modules\AlpasalWarehouse\Requests\StoreOrder;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WHStoreOrderStatusUpdateRequest extends FormRequest
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
        $deliveryStatus = $this->delivery_status;

        $orderStatusValidations =  [
            'delivery_status' => 'required|in:processing,cancelled,ready_to_dispatch',
            'order_items' => ['nullable','array','min:1'],
            'acceptance_status'=>['nullable','array','min:1'],
            'acceptance_status.*' => ['nullable',Rule::in('pending','accepted','rejected')],
            'dispatchable_quantity'=>['nullable','array','min:1'],
            'dispatchable_quantity.*' => ['nullable','min:1'],
            'remarks' => ['required'],
        ];

//        if($deliveryStatus == 'dispatched'){
//
//                $orderStatusValidations['driver_name'] = 'required|string|max:100';
//                $orderStatusValidations['vehicle_type'] = 'required|string|max:100';
//                $orderStatusValidations['vehicle_number']='required|string|max:100';
//                $orderStatusValidations['contact_number']='required';
//                $orderStatusValidations['expected_delivery_time'] = 'required|date|after_or_equal:'.Carbon::now();
//        }


        return $orderStatusValidations;





    }
}
