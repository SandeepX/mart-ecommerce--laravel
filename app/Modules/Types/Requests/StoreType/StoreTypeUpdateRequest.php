<?php

namespace App\Modules\Types\Requests\StoreType;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypeUpdateRequest extends FormRequest
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
            'store_type_name' => 'required|max:50|unique:store_types,store_type_name,'.$this->route('store_type').',store_type_code',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|dimensions:max_width=350,max_height=200|max:2048',
            'description' => 'nullable',
//            'remarks' => 'nullable|max:40',
        ];
    }
}
