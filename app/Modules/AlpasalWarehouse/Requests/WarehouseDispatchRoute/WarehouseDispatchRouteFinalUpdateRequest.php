<?php


namespace App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute;


use Illuminate\Foundation\Http\FormRequest;

class WarehouseDispatchRouteFinalUpdateRequest extends FormRequest
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
            'vehicle_name' => ['required','max:255'],
            'vehicle_number' => ['required','max:191'],
            'driver_name' => ['required','max:191'],
            'driver_license_number' => ['required','max:191'],
            'driver_contact_primary' => ['required','max:191'],
            'driver_contact_secondary' => ['nullable','max:191'],
        ];
    }
}
