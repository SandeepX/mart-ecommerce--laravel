<?php

namespace App\Modules\Category\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryBrandRequest extends FormRequest
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
           'category_code' => 'required|exists:category_master,category_code',
           'brand_codes' => 'required|array',
            'brand_codes.*' => 'required|exists:brands,brand_code'
        ];
    }
}
