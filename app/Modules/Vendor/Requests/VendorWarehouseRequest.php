<?php

namespace App\Modules\Vendor\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorWarehouseRequest extends FormRequest
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
            'vendor_warehouse_location' => 'required|exists:location_hierarchy,location_code',
            'vendor_warehouse_name' => 'required|max:191',
            'landmark_name' => 'required|max:191',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'remarks' => 'nullable|max:191',
        ];
    }
}
