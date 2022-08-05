<?php


namespace App\Modules\AlpasalWarehouse\Requests\WarehouseDispatchRoute;


use Illuminate\Foundation\Http\FormRequest;

class WhDispatchRouteMarkerCreateRequest extends FormRequest
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
            'latitude' => ['required','array','min:1'],
            'latitude.*' => ['required_with:longitude.*'],
            'longitude' => ['required','array','min:1'],
            'longitude.*' => ['required_with:latitude.*'],
        ];
    }
}
