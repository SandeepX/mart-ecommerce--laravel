<?php

namespace App\Modules\Brand\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandUpdateRequest extends FormRequest
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
            'brand_name' => 'required|max:12|unique:brands,brand_name,'.$this->route('brand').',brand_code',
            // 'brand_code' => 'required|max:12|unique:brands,brand_code,'.$this->route('brand').',brand_code',
            'brand_logo' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remarks' => 'nullable|max:50',
            'is_featured'=>'nullable|in:1,null',
        ];
    }
}
