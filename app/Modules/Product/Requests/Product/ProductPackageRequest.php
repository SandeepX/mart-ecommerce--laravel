<?php

namespace App\Modules\Product\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductPackageRequest extends FormRequest
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
            'package_code' => 'required|exists:package_types,package_code',
            'package_weight' => 'nullable|numeric',
            'package_height' => 'nullable|numeric',
            'package_length' => 'nullable|numeric',
            'package_width' => 'nullable|numeric',
            'units_per_package' => 'nullable|integer',
        ];
    }
}
