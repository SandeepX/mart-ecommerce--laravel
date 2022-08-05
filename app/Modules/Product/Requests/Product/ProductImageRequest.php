<?php

namespace App\Modules\Product\Requests\Product;

use App\Modules\Application\Rules\ValidateFileExtension;
use Illuminate\Foundation\Http\FormRequest;

class ProductImageRequest extends FormRequest
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
            'images' => 'required|array|max:3',
            'images.*' => ['required',
                'image',
                 new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
        ];

    }
}
