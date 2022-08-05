<?php

namespace App\Modules\Product\Requests\Product;

use App\Modules\Application\Rules\ValidateFileExtension;
use App\Modules\Product\Models\ProductMaster;
use Illuminate\Foundation\Http\FormRequest;

class ProductImageUpdateRequest extends FormRequest
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
        $product = ProductMaster::find($this->route('product'));
        $imageCount = $product->images()->count();

        $newImageCount = 3-$imageCount;
        return [
            'images' => 'nullable|array|max:'.$newImageCount,
            'images.*' => [
                'nullable',
                'image',
                 new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
        ];
    }
}
