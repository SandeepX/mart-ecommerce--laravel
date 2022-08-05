<?php

namespace App\Modules\Product\Requests\ProductWarranty;

use Illuminate\Foundation\Http\FormRequest;

class ProductWarrantyDetailRequest extends FormRequest
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
            'warranty_code' => 'required|exists:product_warranties,warranty_code',
            'warranty_policy' => 'required',
        ];
    }
}
