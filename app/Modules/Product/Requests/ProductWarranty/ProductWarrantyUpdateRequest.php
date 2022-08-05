<?php

namespace App\Modules\Product\Requests\ProductWarranty;

use Illuminate\Foundation\Http\FormRequest;

class ProductWarrantyUpdateRequest extends FormRequest
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
            'warranty_name' => 'required|max:40',
            'remarks' => 'nullable|max:30'
        ];
    }
}
