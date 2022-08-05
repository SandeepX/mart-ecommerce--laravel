<?php

namespace App\Modules\Product\Requests\ProductSensitivity;

use Illuminate\Foundation\Http\FormRequest;

class ProductSensitivityCreateRequest extends FormRequest
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
            'sensitivity_name' => 'required|max:40',
            'remarks' => 'nullable|max:60'
        ];
    }
}
