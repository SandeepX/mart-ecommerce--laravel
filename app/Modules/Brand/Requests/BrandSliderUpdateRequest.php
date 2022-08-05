<?php

namespace App\Modules\Brand\Requests;

use App\Modules\Application\Rules\ValidateFileExtension;
use Illuminate\Foundation\Http\FormRequest;

class BrandSliderUpdateRequest extends FormRequest
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
            'image'=>['sometimes',new ValidateFileExtension(["jpeg","png","jpg","svg","webp"]),'mimes:jpeg,png,jpg,svg,webp','max:50'],
            'description' => 'nullable',
            'brand_code' => 'required|exists:brands,brand_code',
            'is_active' => 'nullable|in:1,null',
        ];
    }
}
