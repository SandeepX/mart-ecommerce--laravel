<?php


namespace App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute;


use Illuminate\Foundation\Http\FormRequest;

class WarehouseDispatchRouteMinimalUpdateRequest extends FormRequest
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
        ];
    }
}
