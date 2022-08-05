<?php

namespace App\Modules\Product\Requests\ProductCollection;

use Illuminate\Foundation\Http\FormRequest;

class ProductCollectionUpdateRequest extends FormRequest
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
            'product_collection_title' => 'required|max:191',
            'product_collection_subtitle' => 'required|max:191',
            'product_collection_image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=1125,min_height=235,max_height=1125,max_height=240',
            'remarks' => 'nullable|max:191',
        ];
    }

    public function messages()
    {
        return [
            'product_collection_image.dimensions' => 'Uploaded image must be of size (1125 x 240)'

        ];
    }
}
