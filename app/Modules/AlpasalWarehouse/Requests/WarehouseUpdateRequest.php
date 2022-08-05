<?php

namespace App\Modules\AlpasalWarehouse\Requests;

use App\Modules\AlpasalWarehouse\Models\Warehouse;
use Illuminate\Foundation\Http\FormRequest;

class WarehouseUpdateRequest extends FormRequest
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
        $warehouse = Warehouse::findOrFail($this->route('warehouse'));
        return [
            'warehouse_name' => 'required|unique:warehouses,warehouse_name,'.$warehouse->warehouse_name.',warehouse_name',
            'warehouse_type_code' => 'required|exists:alpasal_warehouse_types,warehouse_type_code',
            'location_code' => 'required|exists:location_hierarchy,location_code',
            'remarks' => 'nullable|max:191',
            'pan_vat_type' => 'required',
            'pan_vat_no' => 'string|nullable',
            'warehouse_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'contact_name'=>'nullable|max:191',
            'contact_email'=>'nullable|max:255',
            'contact_phone_1'=>'required|numeric',
            'contact_phone_2' =>'required|numeric',
            'landmark_name' => 'required|max:191',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ];
    }
}
