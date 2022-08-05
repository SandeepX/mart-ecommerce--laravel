<?php


namespace App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute;


use Illuminate\Foundation\Http\FormRequest;

class WhDispatchRouteStoreOrderCreateRequest extends FormRequest
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
            'wh_dispatch_route_store_code' => 'required',
            'order_code.*' => ['required'],
        ];
    }
}
