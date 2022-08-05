<?php

namespace App\Modules\Types\Requests\CategoryType;

use Illuminate\Foundation\Http\FormRequest;

class CategoryTypeCreateRequest extends FormRequest
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
            'category_type_name' => 'required|max:50|unique:category_types,category_type_name',
            'remarks' => 'nullable|max:40',
        ];
    }
}
