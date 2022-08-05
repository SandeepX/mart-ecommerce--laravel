<?php


namespace App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseDispatchRouteCreateRequest extends FormRequest
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
            'route_name' => ['required','max:255'],
            //'route_code' => ['nullable'],
            'description' => ['nullable','max:5000'],
            'store_code' => ['required','array','min:1'],
            'store_code.*' => ['required','distinct'],
            //'sort_order' => ['required','array','min:1'],
            //'sort_order.*' => ['required','distinct'],
        ];
    }
}
