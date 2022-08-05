<?php

namespace App\Modules\Category\Requests;

use App\Modules\Category\Models\CategoryMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
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
        $category = CategoryMaster::find($this->route('category'));
        return [
            'category_name' => 'required|max:40|unique:category_master,category_name,'.$category->category_name.',category_name',
            'category_type_code' => 'required|array',
            'category_type_code.*' => 'required|exists:category_types,category_type_code',
            'upper_category_code' => $this->upper_category_code === null ? 'nullable' : 'required|exists:category_master,category_code',
            'category_banner' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
          #  'category_banner' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=1125,min_height=235,max_height=1125,max_height=240',
            'remarks' => 'nullable|max:50',
            'category_image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif||max:2048',
           # 'category_image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=60,min_height=80,max_width=85,max_height=100',
            #'category_icon' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=20,min_height=20,max_width=40,max_height=40',
            'category_icon' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',

        ];

    }
}
