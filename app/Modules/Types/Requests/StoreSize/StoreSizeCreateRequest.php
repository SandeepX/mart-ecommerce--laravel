<?php

namespace App\Modules\Types\Requests\StoreSize;

use Illuminate\Foundation\Http\FormRequest;

class StoreSizeCreateRequest extends FormRequest
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
            'store_size_name' => 'required|max:50|unique:store_sizes,store_size_name',
            'remarks' => 'nullable|max:40',
        ];
    }
}
