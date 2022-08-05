<?php

namespace App\Modules\Product\Requests\ProductCollection;

use Illuminate\Foundation\Http\FormRequest;

class ProductCollectionCreateRequest extends FormRequest
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
            'product_collection_title' => 'required|max:191|unique:product_collections,product_collection_title',
            'product_collection_subtitle' => 'required|max:191',
            'product_collection_image' => 'required|mimes:jpeg,png,gif|max:2048|dimensions:max_width=1125,max_height=240',
            'remarks' => 'nullable|max:191',
        ];

    }
}
