<?php

namespace App\Modules\Types\Requests\VendorType;

use Illuminate\Foundation\Http\FormRequest;

class VendorTypeUpdateRequest extends FormRequest
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
            'vendor_type_name' => 'required|max:50|unique:vendor_types,vendor_type_name,'.$this->route('vendor_type').',vendor_type_code',
            'remarks' => 'nullable|max:40',
        ];
    }
}
