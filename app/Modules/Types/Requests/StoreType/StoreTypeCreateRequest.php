<?php

namespace App\Modules\Types\Requests\StoreType;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypeCreateRequest extends FormRequest
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
            'store_type_name' => 'required|max:50|unique:store_types,store_type_name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|dimensions:max_width=350,max_height=200|max:2048',
            'description' => 'nullable',
//            'remarks' => 'nullable|max:40',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'store_type_name.required' => 'The store Type Name field is required',
            'image.required' => 'The  Image field is required',
            'image.dimensions' => 'The  Image size should be 350*200',
        ];
    }
}
